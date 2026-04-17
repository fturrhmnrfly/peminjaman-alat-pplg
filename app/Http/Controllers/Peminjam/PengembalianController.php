<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Models\Notification;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PengembalianController extends Controller
{
    public function index()
    {
        $peminjaman = Peminjaman::with(['detailPeminjamans.alatUnit.alat', 'detailPeminjamans.alat'])
            ->where('user_id', auth()->id())
            ->where('status', 'disetujui')
            ->orderBy('tanggal_pinjam', 'desc')
            ->get();

        $riwayatPengembalian = Peminjaman::with(['detailPeminjamans.alatUnit.alat', 'detailPeminjamans.alat'])
            ->where('user_id', auth()->id())
            ->whereIn('status', ['pengembalian_pending', 'dikembalikan'])
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

            if ($peminjaman->status !== 'disetujui') {
                if ($peminjaman->status === 'pengembalian_pending') {
                    return ['ok' => false, 'message' => 'Pengembalian sudah diajukan.'];
                }
                return ['ok' => false, 'message' => 'Belum bisa dikembalikan.'];
            }

            $waktuPengajuan = Carbon::now('Asia/Jakarta');

            $peminjaman->update([
                'status' => 'pengembalian_pending',
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
}
