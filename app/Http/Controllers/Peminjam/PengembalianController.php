<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Models\Notification;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengembalianController extends Controller
{
    public function index()
    {
        $peminjaman = Peminjaman::with(['detailPeminjamans.alatUnit.alat', 'detailPeminjamans.alat'])
            ->where('user_id', auth()->id())
            ->where('status', 'disetujui')
            ->orderBy('tanggal_pinjam', 'desc')
            ->get();

        return view('peminjam.pengembalian.index', [
            'peminjaman' => $peminjaman,
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
                    return ['ok' => false, 'message' => 'Pengembalian sudah diajukan dan menunggu konfirmasi petugas.'];
                }
                return ['ok' => false, 'message' => 'Peminjaman ini belum dapat dikembalikan.'];
            }

            $peminjaman->update([
                'status' => 'pengembalian_pending',
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

        return back()->with('success', 'Pengembalian diajukan dan menunggu konfirmasi petugas.');
    }
}
