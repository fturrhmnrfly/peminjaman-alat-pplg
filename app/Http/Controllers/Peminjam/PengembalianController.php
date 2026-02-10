<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class PengembalianController extends Controller
{
    public function index()
    {
        $peminjaman = Peminjaman::with('detailPeminjamans.alat')
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

        if ($peminjaman->status !== 'disetujui') {
            return back()->with('error', 'Peminjaman ini belum dapat dikembalikan.');
        }

        $peminjaman->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => now()->toDateString(),
        ]);

        LogAktivitas::catat(
            'Update',
            'Peminjaman',
            "Pengembalian diajukan untuk peminjaman #{$peminjaman->id}"
        );

        return back()->with('success', 'Pengembalian berhasil diajukan.');
    }
}
