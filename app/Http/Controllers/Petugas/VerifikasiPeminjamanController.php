<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Models\Notification;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\DB;

class VerifikasiPeminjamanController extends Controller
{
    public function index()
    {
        return view('petugas.peminjaman.index', [
            'data' => Peminjaman::with(['user', 'detailPeminjamans.alatUnit.alat', 'detailPeminjamans.alat'])
                ->where('status', 'pending')
                ->orderBy('created_at', 'asc')
                ->get(),
        ]);
    }

    public function setujui(Peminjaman $peminjaman)
    {
        $result = DB::transaction(function () use ($peminjaman) {
            $peminjaman = Peminjaman::query()
                ->with(['detailPeminjamans.alatUnit.alat', 'detailPeminjamans.alat'])
                ->lockForUpdate()
                ->findOrFail($peminjaman->id);

            if ($peminjaman->status !== 'pending') {
                return ['ok' => false, 'message' => 'Pengajuan ini sudah diproses sebelumnya.'];
            }

            foreach ($peminjaman->detailPeminjamans as $detail) {
                if ($detail->alat_unit_id) {
                    $unit = $detail->alatUnit()->lockForUpdate()->first();
                    $namaAlat = $unit?->alat?->nama_alat ?? 'tidak diketahui';
                    $kodeUnik = $unit?->kode_unik ?? '-';

                    if (! $unit || $unit->status !== 'tersedia') {
                        return ['ok' => false, 'message' => "Unit {$namaAlat} ({$kodeUnik}) tidak tersedia untuk dipinjam."];
                    }
                } else {
                    $alat = $detail->alat()->lockForUpdate()->first();
                    $namaAlat = $alat?->nama_alat ?? 'tidak diketahui';

                    if ($detail->jumlah_pinjam > $alat->jumlah) {
                        return ['ok' => false, 'message' => "Stok {$alat->nama_alat} tidak cukup untuk disetujui."];
                    }
                }
            }

            foreach ($peminjaman->detailPeminjamans as $detail) {
                if ($detail->alat_unit_id) {
                    $unit = $detail->alatUnit()->lockForUpdate()->first();
                    if ($unit) {
                        $unit->update(['status' => 'dipinjam']);
                    }
                } else {
                    $detail->alat()->decrement('jumlah', $detail->jumlah_pinjam);
                }
            }

            $peminjaman->update(['status' => 'disetujui']);
            LogAktivitas::catat('Update', 'Peminjaman', "Menyetujui peminjaman #{$peminjaman->id}");

            Notification::pushForUser(
                $peminjaman->user_id,
                'Peminjaman Disetujui',
                "Pengajuan peminjaman #{$peminjaman->id} telah disetujui."
            );
            Notification::pushForRole(
                'admin',
                'Peminjaman Disetujui',
                "Peminjaman #{$peminjaman->id} telah disetujui oleh petugas."
            );

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
        Notification::pushForUser(
            $peminjaman->user_id,
            'Peminjaman Ditolak',
            "Pengajuan peminjaman #{$peminjaman->id} ditolak."
        );
        Notification::pushForRole(
            'admin',
            'Peminjaman Ditolak',
            "Peminjaman #{$peminjaman->id} ditolak oleh petugas."
        );
        return back()->with('success', 'Peminjaman berhasil ditolak.');
    }
}
