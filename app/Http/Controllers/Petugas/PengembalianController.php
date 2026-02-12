<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Models\Notification;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\DB;
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

    public function konfirmasi(Peminjaman $peminjaman)
    {
        $result = DB::transaction(function () use ($peminjaman) {
            $peminjaman = Peminjaman::query()
                ->with(['detailPeminjamans.alatUnit.alat', 'detailPeminjamans.alat'])
                ->lockForUpdate()
                ->findOrFail($peminjaman->id);

            if ($peminjaman->status !== 'pengembalian_pending') {
                return ['ok' => false, 'message' => 'Pengembalian ini sudah diproses.'];
            }

            foreach ($peminjaman->detailPeminjamans as $detail) {
                if ($detail->alat_unit_id) {
                    $unit = $detail->alatUnit()->lockForUpdate()->first();
                    if ($unit) {
                        $unit->update(['status' => 'tersedia']);
                    }
                } else {
                    $detail->alat()->increment('jumlah', $detail->jumlah_pinjam);
                }
            }

            $now = Carbon::now('Asia/Jakarta');

            $peminjaman->update([
                'status' => 'dikembalikan',
                'tanggal_kembali' => $now->toDateString(),
                'waktu_pengembalian' => $now,
            ]);

            LogAktivitas::catat('Update', 'Peminjaman', "Konfirmasi pengembalian #{$peminjaman->id}");
            Notification::pushForUser(
                $peminjaman->user_id,
                'Pengembalian Dikonfirmasi',
                "Pengembalian peminjaman #{$peminjaman->id} telah dikonfirmasi oleh petugas."
            );
            Notification::pushForRole(
                'admin',
                'Pengembalian Dikonfirmasi',
                "Pengembalian peminjaman #{$peminjaman->id} telah dikonfirmasi."
            );

            return ['ok' => true];
        });

        if (! $result['ok']) {
            return back()->with('error', $result['message']);
        }

        return back()->with('success', 'Pengembalian berhasil dikonfirmasi.');
    }
}
