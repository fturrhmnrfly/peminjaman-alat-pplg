<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Mail\ReturnDamageNoticeMail;
use App\Mail\ReturnConfirmedMail;
use App\Models\DetailPeminjaman;
use App\Models\LogAktivitas;
use App\Models\Notification;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class PengembalianController extends Controller
{
    public function index()
    {
        $peminjaman = Peminjaman::with(['user', 'detailPeminjamans.alatUnit.alat', 'detailPeminjamans.alat'])
            ->whereIn('status', [
                Peminjaman::STATUS_PENGEMBALIAN_PENDING,
                Peminjaman::STATUS_MENUNGGU_PEMERIKSAAN,
                Peminjaman::STATUS_MENUNGGU_PEMBAYARAN,
            ])
            ->orderBy('updated_at', 'asc')
            ->paginate(15);

        return view('petugas.pengembalian.index', [
            'peminjaman' => $peminjaman,
        ]);
    }

    public function konfirmasi(Request $request, Peminjaman $peminjaman)
    {
        $result = DB::transaction(function () use ($peminjaman, $request) {
            $peminjaman = Peminjaman::query()
                ->with(['detailPeminjamans.alatUnit.alat', 'detailPeminjamans.alat'])
                ->lockForUpdate()
                ->findOrFail($peminjaman->id);

            if ($peminjaman->status !== Peminjaman::STATUS_MENUNGGU_PEMERIKSAAN) {
                return ['ok' => false, 'message' => 'Pengembalian ini belum siap diperiksa atau sudah diproses.'];
            }

            $validated = $request->validate([
                'items' => ['required', 'array', 'min:1'],
                'items.*.kondisi_pengembalian' => ['required', 'in:baik,rusak_ringan,rusak_berat,hilang'],
                'items.*.denda_kerusakan' => ['nullable', 'integer', 'min:0'],
                'items.*.detail_kerusakan' => ['nullable', 'string', 'max:1000'],
            ]);

            $detailMap = collect($validated['items']);
            $totalDendaKerusakan = 0;

            foreach ($peminjaman->detailPeminjamans as $detail) {
                $item = $detailMap->get((string) $detail->id) ?? $detailMap->get($detail->id);

                if (! $item) {
                    return ['ok' => false, 'message' => 'Semua kondisi barang wajib diperiksa sebelum pengembalian dikonfirmasi.'];
                }

                $kondisiPengembalian = $item['kondisi_pengembalian'];
                $dendaKerusakan = (int) ($item['denda_kerusakan'] ?? 0);
                $detailKerusakan = trim((string) ($item['detail_kerusakan'] ?? ''));

                $detail->update([
                    'kondisi_pengembalian' => $kondisiPengembalian,
                    'denda_kerusakan' => $dendaKerusakan,
                    'detail_kerusakan' => $detailKerusakan !== '' ? $detailKerusakan : null,
                ]);

                $totalDendaKerusakan += $dendaKerusakan;

                if ($detail->alat_unit_id) {
                    $unit = $detail->alatUnit()->lockForUpdate()->first();
                    if ($unit) {
                        $unit->update($this->unitStateForReturn($kondisiPengembalian, $peminjaman, $detail));
                    }
                } elseif ($kondisiPengembalian === 'baik') {
                    $detail->alat()->increment('jumlah', $detail->jumlah_pinjam);
                }
            }

            $now = Carbon::now('Asia/Jakarta');
            $waktuDiterima = $peminjaman->waktu_pengajuan_pengembalian ?? $now;
            $totalDenda = (int) (($peminjaman->denda ?? 0) + $totalDendaKerusakan);
            $nextStatus = $totalDenda > 0
                ? Peminjaman::STATUS_MENUNGGU_PEMBAYARAN
                : Peminjaman::STATUS_DIKEMBALIKAN;

            $peminjaman->update([
                'status' => $nextStatus,
                'tanggal_kembali' => $waktuDiterima->toDateString(),
                'waktu_pengembalian' => $waktuDiterima,
                'metode_pembayaran' => $nextStatus === Peminjaman::STATUS_DIKEMBALIKAN ? null : $peminjaman->metode_pembayaran,
            ]);

            $logMessage = $nextStatus === Peminjaman::STATUS_MENUNGGU_PEMBAYARAN
                ? "Pemeriksaan pengembalian #{$peminjaman->id} selesai. Menunggu pembayaran denda Rp " . number_format($totalDenda, 0, ',', '.')
                : "Pemeriksaan pengembalian #{$peminjaman->id} selesai tanpa tagihan tambahan.";

            LogAktivitas::catat(
                'Update',
                'Peminjaman',
                $logMessage
            );

            if ($nextStatus === Peminjaman::STATUS_MENUNGGU_PEMBAYARAN) {
                Notification::pushForUser(
                    $peminjaman->user_id,
                    'Hasil Pemeriksaan Pengembalian',
                    "Petugas menemukan tagihan pada peminjaman #{$peminjaman->id}. Total yang harus diselesaikan: Rp " . number_format($totalDenda, 0, ',', '.')
                );
                Notification::pushForRole(
                    'admin',
                    'Tagihan Pengembalian Dibuat',
                    "Peminjaman #{$peminjaman->id} menunggu pembayaran denda sebesar Rp " . number_format($totalDenda, 0, ',', '.')
                );
            } else {
                Notification::pushForUser(
                    $peminjaman->user_id,
                    'Pengembalian Dikonfirmasi',
                    "Pengembalian peminjaman #{$peminjaman->id} telah selesai diproses petugas."
                );
                Notification::pushForRole(
                    'admin',
                    'Pengembalian Dikonfirmasi',
                    "Pengembalian peminjaman #{$peminjaman->id} telah selesai tanpa tagihan tambahan."
                );
            }

            return [
                'ok' => true,
                'awaiting_payment' => $nextStatus === Peminjaman::STATUS_MENUNGGU_PEMBAYARAN,
                'total_denda_kerusakan' => $totalDendaKerusakan,
                'total_denda' => $totalDenda,
                'peminjaman' => $peminjaman->loadMissing(['user', 'detailPeminjamans.alatUnit.alat', 'detailPeminjamans.alat']),
            ];
        });

        if (! $result['ok']) {
            return back()->with('error', $result['message']);
        }

        $emailResult = $result['awaiting_payment']
            ? $this->sendDamageNoticeEmail($result['peminjaman'])
            : $this->sendReturnEmail($result['peminjaman']);

        $baseMessage = $result['awaiting_payment']
            ? 'Hasil pemeriksaan disimpan. Tagihan denda sudah dibuat sebesar Rp ' . number_format($result['total_denda'], 0, ',', '.')
            : 'Pengembalian selesai diproses tanpa tagihan tambahan.';
        $response = back()->with('success', $baseMessage);

        if ($emailResult['sent']) {
            $emailLog = $result['awaiting_payment']
                ? "Email pemberitahuan denda pengembalian #{$result['peminjaman']->id} berhasil dikirim."
                : "Email konfirmasi pengembalian #{$result['peminjaman']->id} berhasil dikirim.";
            LogAktivitas::catat('Update', 'Email', $emailLog);

            return $response->with('success', $baseMessage . ' Detailnya juga sudah dikirim ke email peminjam.');
        }

        $failedEmailLog = $result['awaiting_payment']
            ? "Email pemberitahuan denda pengembalian #{$result['peminjaman']->id} gagal dikirim: {$emailResult['message']}"
            : "Email konfirmasi pengembalian #{$result['peminjaman']->id} gagal dikirim: {$emailResult['message']}";
        LogAktivitas::catat('Update', 'Email', $failedEmailLog);

        return $response->with('warning', $baseMessage . ' Namun email notifikasi belum terkirim. ' . $emailResult['message']);
    }

    public function terima(Peminjaman $peminjaman)
    {
        $result = DB::transaction(function () use ($peminjaman) {
            $peminjaman = Peminjaman::query()
                ->with(['user', 'detailPeminjamans.alatUnit.alat', 'detailPeminjamans.alat'])
                ->lockForUpdate()
                ->findOrFail($peminjaman->id);

            if ($peminjaman->status !== Peminjaman::STATUS_PENGEMBALIAN_PENDING) {
                return ['ok' => false, 'message' => 'Pengembalian ini sudah dikonfirmasi atau sedang diproses.'];
            }

            $waktuDiterima = $peminjaman->waktu_pengajuan_pengembalian ?? Carbon::now('Asia/Jakarta');

            $peminjaman->update([
                'status' => Peminjaman::STATUS_MENUNGGU_PEMERIKSAAN,
                'tanggal_kembali' => $waktuDiterima->toDateString(),
                'waktu_pengembalian' => $waktuDiterima,
            ]);

            LogAktivitas::catat(
                'Update',
                'Peminjaman',
                "Pengembalian #{$peminjaman->id} diterima petugas dan menunggu pemeriksaan barang."
            );

            Notification::pushForUser(
                $peminjaman->user_id,
                'Pengembalian Diterima Petugas',
                "Pengembalian peminjaman #{$peminjaman->id} sudah diterima petugas dan sedang menunggu pemeriksaan barang."
            );

            Notification::pushForRole(
                'admin',
                'Pengembalian Menunggu Pemeriksaan',
                "Peminjaman #{$peminjaman->id} sudah diterima petugas dan sedang menunggu pemeriksaan."
            );

            return ['ok' => true];
        });

        if (! $result['ok']) {
            return back()->with('error', $result['message']);
        }

        return back()->with('success', 'Pengembalian sudah dikonfirmasi diterima. Selanjutnya petugas bisa memeriksa kondisi barang.');
    }

    public function konfirmasiPembayaran(Request $request, Peminjaman $peminjaman)
    {
        $result = DB::transaction(function () use ($peminjaman, $request) {
            $peminjaman = Peminjaman::query()
                ->with(['user', 'detailPeminjamans.alatUnit.alat', 'detailPeminjamans.alat'])
                ->lockForUpdate()
                ->findOrFail($peminjaman->id);

            if ($peminjaman->status !== Peminjaman::STATUS_MENUNGGU_PEMBAYARAN) {
                return ['ok' => false, 'message' => 'Tagihan pengembalian ini tidak sedang menunggu pembayaran.'];
            }

            $validated = $request->validate([
                'metode_pembayaran' => ['required', 'in:tunai,qris_all_payment'],
                'payment_confirmed' => ['nullable', 'boolean'],
            ]);

            if (
                $validated['metode_pembayaran'] === 'qris_all_payment'
                && ! ((bool) ($validated['payment_confirmed'] ?? false))
            ) {
                return ['ok' => false, 'message' => 'Pembayaran QRIS belum dikonfirmasi.'];
            }

            $peminjaman->update([
                'status' => Peminjaman::STATUS_DIKEMBALIKAN,
                'metode_pembayaran' => $validated['metode_pembayaran'],
            ]);

            LogAktivitas::catat(
                'Update',
                'Peminjaman',
                "Pembayaran denda pengembalian #{$peminjaman->id} dikonfirmasi via {$peminjaman->metode_pembayaran_label}."
            );

            Notification::pushForUser(
                $peminjaman->user_id,
                'Pembayaran Denda Dikonfirmasi',
                "Pembayaran denda untuk peminjaman #{$peminjaman->id} sudah dikonfirmasi petugas."
            );
            Notification::pushForRole(
                'admin',
                'Pembayaran Denda Dikonfirmasi',
                "Pembayaran denda untuk peminjaman #{$peminjaman->id} sudah dikonfirmasi."
            );

            return [
                'ok' => true,
                'peminjaman' => $peminjaman,
            ];
        });

        if (! $result['ok']) {
            return back()->with('error', $result['message']);
        }

        $emailResult = $this->sendReturnEmail($result['peminjaman']);

        if ($emailResult['sent']) {
            LogAktivitas::catat('Update', 'Email', "Email konfirmasi akhir pengembalian #{$result['peminjaman']->id} berhasil dikirim.");

            return back()->with('success', 'Pembayaran denda berhasil dikonfirmasi dan email penyelesaian sudah dikirim.');
        }

        LogAktivitas::catat('Update', 'Email', "Email konfirmasi akhir pengembalian #{$result['peminjaman']->id} gagal dikirim: {$emailResult['message']}");

        return back()->with('warning', 'Pembayaran denda berhasil dikonfirmasi, tetapi email penyelesaian belum terkirim. ' . $emailResult['message']);
    }

    private function unitStateForReturn(string $kondisiPengembalian, Peminjaman $peminjaman, DetailPeminjaman $detail): array
    {
        $namaKondisi = match ($kondisiPengembalian) {
            'rusak_ringan' => 'Rusak ringan',
            'rusak_berat' => 'Rusak berat',
            'hilang' => 'Hilang',
            default => 'Baik',
        };

        if ($kondisiPengembalian === 'baik') {
            return [
                'status' => 'tersedia',
                'kondisi' => 'baik',
                'keterangan' => null,
            ];
        }

        return [
            'status' => 'tidak_tersedia',
            'kondisi' => $kondisiPengembalian === 'hilang' ? 'hilang' : 'rusak',
            'keterangan' => "Hasil pengembalian #{$peminjaman->id} - {$namaKondisi} pada unit {$detail->alatUnit?->kode_unik}",
        ];
    }

    private function sendReturnEmail(Peminjaman $peminjaman): array
    {
        if (! $peminjaman->user?->email) {
            return [
                'sent' => false,
                'message' => 'Email peminjam belum tersedia.',
            ];
        }

        try {
            Mail::to($peminjaman->user->email)->send(new ReturnConfirmedMail($peminjaman));

            return [
                'sent' => true,
                'message' => 'Email konfirmasi berhasil dikirim.',
            ];
        } catch (\Throwable $e) {
            return [
                'sent' => false,
                'message' => 'Email konfirmasi gagal dikirim.',
            ];
        }
    }

    private function sendDamageNoticeEmail(Peminjaman $peminjaman): array
    {
        if (! $peminjaman->user?->email) {
            return [
                'sent' => false,
                'message' => 'Email peminjam belum tersedia.',
            ];
        }

        try {
            Mail::to($peminjaman->user->email)->send(new ReturnDamageNoticeMail($peminjaman));

            return [
                'sent' => true,
                'message' => 'Email pemberitahuan denda berhasil dikirim.',
            ];
        } catch (\Throwable $e) {
            return [
                'sent' => false,
                'message' => 'Email pemberitahuan denda gagal dikirim.',
            ];
        }
    }
}
