<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $result = DB::transaction(function () use ($peminjaman) {
            $peminjaman = Peminjaman::query()
                ->with(['detailPeminjamans.alat'])
                ->lockForUpdate()
                ->findOrFail($peminjaman->id);

            if ($peminjaman->status !== 'disetujui') {
                return ['ok' => false, 'message' => 'Peminjaman ini belum dapat dikembalikan.'];
            }

            foreach ($peminjaman->detailPeminjamans as $detail) {
                $detail->alat()->increment('jumlah', $detail->jumlah_pinjam);
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

            return ['ok' => true];
        });

        if (! $result['ok']) {
            return back()->with('error', $result['message']);
        }

        return back()->with('success', 'Pengembalian berhasil diajukan.');
    }
}
