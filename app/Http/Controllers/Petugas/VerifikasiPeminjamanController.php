<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\DB;

class VerifikasiPeminjamanController extends Controller
{
    public function index()
    {
        return view('petugas.peminjaman.index', [
            'data' => Peminjaman::with(['user', 'detailPeminjamans.alat'])
                ->where('status', 'pending')
                ->orderBy('created_at', 'asc')
                ->get(),
        ]);
    }

    public function setujui(Peminjaman $peminjaman)
    {
        $result = DB::transaction(function () use ($peminjaman) {
            $peminjaman = Peminjaman::query()
                ->with(['detailPeminjamans.alat'])
                ->lockForUpdate()
                ->findOrFail($peminjaman->id);

            if ($peminjaman->status !== 'pending') {
                return ['ok' => false, 'message' => 'Pengajuan ini sudah diproses sebelumnya.'];
            }

            foreach ($peminjaman->detailPeminjamans as $detail) {
                $alat = $detail->alat()->lockForUpdate()->first();
                $namaAlat = $alat?->nama_alat ?? 'tidak diketahui';

                if (! $alat || $alat->kondisi !== 'baik') {
                    return ['ok' => false, 'message' => "Alat {$namaAlat} tidak bisa dipinjam."];
                }

                if ($detail->jumlah_pinjam > $alat->jumlah) {
                    return ['ok' => false, 'message' => "Stok {$alat->nama_alat} tidak cukup untuk disetujui."];
                }
            }

            foreach ($peminjaman->detailPeminjamans as $detail) {
                $detail->alat()->decrement('jumlah', $detail->jumlah_pinjam);
            }

            $peminjaman->update(['status' => 'disetujui']);
            LogAktivitas::catat('Update', 'Peminjaman', "Menyetujui peminjaman #{$peminjaman->id}");

            return ['ok' => true];
        });

        if (! $result['ok']) {
            return back()->with('error', $result['message']);
        }

        return back()->with('success', 'Peminjaman berhasil disetujui.');
    }

    public function tolak(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $peminjaman->update(['status' => 'ditolak']);
        LogAktivitas::catat('Update', 'Peminjaman', "Menolak peminjaman #{$peminjaman->id}");
        return back()->with('success', 'Peminjaman berhasil ditolak.');
    }
}
