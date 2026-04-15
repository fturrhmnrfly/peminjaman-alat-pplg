<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
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
            ->where('status', 'pengembalian_pending')
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

            if ($peminjaman->status !== 'pengembalian_pending') {
                return ['ok' => false, 'message' => 'Pengembalian ini sudah diproses.'];
            }

            $validated = $request->validate([
                'items' => ['required', 'array', 'min:1'],
                'items.*.kondisi_pengembalian' => ['required', 'in:baik,rusak_ringan,rusak_berat,hilang'],
                'items.*.denda_kerusakan' => ['nullable', 'integer', 'min:0'],
                'items.*.detail_kerusakan' => ['nullable', 'string', 'max:1000'],
                'metode_pembayaran' => ['nullable', 'in:tunai,qris_all_payment'],
                'payment_confirmed' => ['nullable', 'boolean'],
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
                } elseif ($kondisiPengembalian !== 'hilang') {
                    $detail->alat()->increment('jumlah', $detail->jumlah_pinjam);
                }
            }

            $totalDenda = (int) (($peminjaman->denda ?? 0) + $totalDendaKerusakan);
            $metodePembayaran = $totalDenda > 0 ? ($validated['metode_pembayaran'] ?? null) : null;

            if ($totalDenda > 0 && ! $metodePembayaran) {
                return ['ok' => false, 'message' => 'Pilih metode pembayaran terlebih dahulu karena pengembalian ini memiliki denda.'];
            }

            if ($totalDenda > 0 && $metodePembayaran === 'qris_all_payment' && ! ((bool) ($validated['payment_confirmed'] ?? false))) {
                return ['ok' => false, 'message' => 'Pembayaran QRIS belum dikonfirmasi. Silakan selesaikan pembayaran QRIS terlebih dahulu.'];
            }

            $now = Carbon::now('Asia/Jakarta');

            $peminjaman->update([
                'status' => 'dikembalikan',
                'tanggal_kembali' => $now->toDateString(),
                'waktu_pengembalian' => $now,
                'metode_pembayaran' => $metodePembayaran,
            ]);

            LogAktivitas::catat(
                'Update',
                'Peminjaman',
                "Konfirmasi pengembalian #{$peminjaman->id} dengan denda kerusakan Rp " . number_format($totalDendaKerusakan, 0, ',', '.') . " via {$peminjaman->metode_pembayaran_label}"
            );
            Notification::pushForUser(
                $peminjaman->user_id,
                'Pengembalian Dikonfirmasi',
                "Pengembalian peminjaman #{$peminjaman->id} telah dikonfirmasi oleh petugas. Total denda: Rp " . number_format($peminjaman->total_denda, 0, ',', '.')
            );
            Notification::pushForRole(
                'admin',
                'Pengembalian Dikonfirmasi',
                "Pengembalian peminjaman #{$peminjaman->id} telah dikonfirmasi. Total denda: Rp " . number_format($peminjaman->total_denda, 0, ',', '.')
            );

            return [
                'ok' => true,
                'total_denda_kerusakan' => $totalDendaKerusakan,
                'total_denda' => $peminjaman->total_denda,
                'peminjaman' => $peminjaman->loadMissing(['user', 'detailPeminjamans.alatUnit.alat', 'detailPeminjamans.alat']),
            ];
        });

        if (! $result['ok']) {
            return back()->with('error', $result['message']);
        }

        $emailResult = $this->sendReturnEmail($result['peminjaman']);
        $baseMessage = 'Pengembalian berhasil dikonfirmasi. Total denda: Rp ' . number_format($result['total_denda'], 0, ',', '.');
        $response = back()->with('success', $baseMessage);

        if ($emailResult['sent']) {
            LogAktivitas::catat('Update', 'Email', "Email konfirmasi pengembalian #{$result['peminjaman']->id} berhasil dikirim.");

            return $response->with('success', $baseMessage . ' Detail pengembalian juga sudah dikirim ke email peminjam.');
        }

        LogAktivitas::catat('Update', 'Email', "Email konfirmasi pengembalian #{$result['peminjaman']->id} gagal dikirim: {$emailResult['message']}");

        return $response->with('warning', 'Pengembalian berhasil dikonfirmasi, tetapi email notifikasi belum terkirim. ' . $emailResult['message']);
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
}
