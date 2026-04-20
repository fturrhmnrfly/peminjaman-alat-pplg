<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Mail\ReturnConfirmedMail;
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
        $peminjaman = Peminjaman::with(['detailPeminjamans.alatUnit.alat', 'detailPeminjamans.alat'])
            ->where('user_id', auth()->id())
            ->where('status', Peminjaman::STATUS_DISETUJUI)
            ->orderBy('tanggal_pinjam', 'desc')
            ->get();

        $riwayatPengembalian = Peminjaman::with(['detailPeminjamans.alatUnit.alat', 'detailPeminjamans.alat'])
            ->where('user_id', auth()->id())
            ->whereIn('status', [
                Peminjaman::STATUS_PENGEMBALIAN_PENDING,
                Peminjaman::STATUS_MENUNGGU_PEMERIKSAAN,
                Peminjaman::STATUS_MENUNGGU_PEMBAYARAN,
                Peminjaman::STATUS_DIKEMBALIKAN,
            ])
            ->orderByDesc('waktu_pengajuan_pengembalian')
            ->orderByDesc('waktu_pengembalian')
            ->orderByDesc('tanggal_pinjam')
            ->get();

        return view('peminjam.pengembalian.index', [
            'peminjaman' => $peminjaman,
            'riwayatPengembalian' => $riwayatPengembalian,
        ]);
    }

    public function update(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->user_id !== auth()->id()) {
            abort(403, 'Tidak diizinkan.');
        }

        $result = DB::transaction(function () use ($peminjaman) {
            $peminjaman = Peminjaman::query()
                ->with(['detailPeminjamans.alatUnit.alat', 'detailPeminjamans.alat'])
                ->lockForUpdate()
                ->findOrFail($peminjaman->id);

            if ($peminjaman->status !== Peminjaman::STATUS_DISETUJUI) {
                if ($peminjaman->status === Peminjaman::STATUS_PENGEMBALIAN_PENDING) {
                    return ['ok' => false, 'message' => 'Pengembalian sudah diajukan.'];
                }
                return ['ok' => false, 'message' => 'Belum bisa dikembalikan.'];
            }

            $waktuPengajuan = Carbon::now('Asia/Jakarta');

            $peminjaman->update([
                'status' => Peminjaman::STATUS_PENGEMBALIAN_PENDING,
                'waktu_pengajuan_pengembalian' => $waktuPengajuan,
                'denda' => $peminjaman->hitungDendaOtomatis($waktuPengajuan),
            ]);

            LogAktivitas::catat(
                'Update',
                'Peminjaman',
                "Pengembalian diajukan untuk peminjaman #{$peminjaman->id}"
            );

            Notification::pushForUser(
                $peminjaman->user_id,
                'Pengembalian Diajukan',
                "Pengembalian untuk peminjaman #{$peminjaman->id} menunggu konfirmasi petugas."
            );
            Notification::pushForRole(
                'petugas',
                'Konfirmasi Pengembalian',
                "Pengembalian untuk peminjaman #{$peminjaman->id} menunggu konfirmasi."
            );
            Notification::pushForRole(
                'admin',
                'Pengembalian Diajukan',
                "Pengembalian untuk peminjaman #{$peminjaman->id} telah diajukan."
            );

            return ['ok' => true];
        });

        if (! $result['ok']) {
            return back()->with('error', $result['message']);
        }

        return back()->with('success', 'Pengembalian diajukan.');
    }

    public function bayarDenda(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->user_id !== auth()->id()) {
            abort(403, 'Tidak diizinkan.');
        }

        $result = DB::transaction(function () use ($peminjaman, $request) {
            $peminjaman = Peminjaman::query()
                ->with(['user', 'detailPeminjamans.alatUnit.alat', 'detailPeminjamans.alat'])
                ->lockForUpdate()
                ->findOrFail($peminjaman->id);

            if ($peminjaman->status !== Peminjaman::STATUS_MENUNGGU_PEMBAYARAN) {
                return ['ok' => false, 'message' => 'Tagihan ini belum siap dibayar atau sudah diselesaikan.'];
            }

            if ($peminjaman->total_denda <= 0) {
                return ['ok' => false, 'message' => 'Tagihan denda tidak ditemukan.'];
            }

            $validated = $request->validate([
                'metode_pembayaran' => ['required', 'in:qris_all_payment,gopay,ovo,dana,shopeepay'],
                'payment_confirmed' => ['required', 'boolean'],
            ]);

            if (! ((bool) $validated['payment_confirmed'])) {
                return ['ok' => false, 'message' => 'Pembayaran belum dikonfirmasi.'];
            }

            $peminjaman->update([
                'status' => Peminjaman::STATUS_DIKEMBALIKAN,
                'metode_pembayaran' => $validated['metode_pembayaran'],
            ]);

            LogAktivitas::catat(
                'Update',
                'Peminjaman',
                "Peminjam menyelesaikan pembayaran denda pengembalian #{$peminjaman->id} via {$peminjaman->metode_pembayaran_label}."
            );

            Notification::pushForUser(
                $peminjaman->user_id,
                'Pembayaran Denda Berhasil',
                "Pembayaran denda untuk peminjaman #{$peminjaman->id} berhasil dicatat via {$peminjaman->metode_pembayaran_label}."
            );
            Notification::pushForRole(
                'petugas',
                'Denda Dibayar Peminjam',
                "Peminjam telah membayar denda untuk peminjaman #{$peminjaman->id} via {$peminjaman->metode_pembayaran_label}."
            );
            Notification::pushForRole(
                'admin',
                'Denda Dibayar Peminjam',
                "Pembayaran denda peminjaman #{$peminjaman->id} sudah selesai via {$peminjaman->metode_pembayaran_label}."
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

            return back()->with('success', 'Pembayaran denda berhasil. Pengembalian sudah selesai dan email konfirmasi sudah dikirim.');
        }

        LogAktivitas::catat('Update', 'Email', "Email konfirmasi akhir pengembalian #{$result['peminjaman']->id} gagal dikirim: {$emailResult['message']}");

        return back()->with('warning', 'Pembayaran denda berhasil, tetapi email konfirmasi belum terkirim. ' . $emailResult['message']);
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
