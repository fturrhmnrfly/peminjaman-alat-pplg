<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\AlatUnit;
use App\Models\DetailPeminjaman;
use App\Models\LogAktivitas;
use App\Models\Notification;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjaman = Peminjaman::with(['detailPeminjamans.alatUnit.alat', 'detailPeminjamans.alat'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        $alatUnits = AlatUnit::with('alat')
            ->where('status', 'tersedia')
            ->orderBy('kode_unik')
            ->get();

        return view('peminjam.peminjaman.index', [
            'peminjaman' => $peminjaman,
            'alatUnits' => $alatUnits,
        ]);
    }

    public function store(Request $request)
    {
        $now = Carbon::now('Asia/Jakarta');
        $start = $now->copy()->setTime(7, 0, 0);
        $end = $now->copy()->setTime(15, 0, 0);

        if ($now->lt($start) || $now->gt($end)) {
            return back()
                ->withErrors(['items' => 'Peminjaman hanya dibuka pukul 07:00 - 15:00 WIB.'])
                ->withInput();
        }

        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.alat_unit_id' => [
                'required',
                'distinct',
                Rule::exists('alat_units', 'id')->where(function ($query) {
                    $query->where('status', 'tersedia');
                }),
            ],
        ]);

        $peminjamanId = null;
        $totalItem = count($validated['items']);
        DB::transaction(function () use ($validated, &$peminjamanId) {
            $peminjaman = Peminjaman::create([
                'user_id' => auth()->id(),
                'tanggal_pinjam' => $now->toDateString(),
                'waktu_pinjam' => $now,
                'batas_kembali' => $end,
                'status' => 'pending',
            ]);
            $peminjamanId = $peminjaman->id;

            foreach ($validated['items'] as $row) {
                $unit = AlatUnit::with('alat')->find($row['alat_unit_id']);
                if (! $unit) {
                    continue;
                }
                DetailPeminjaman::create([
                    'peminjaman_id' => $peminjaman->id,
                    'alat_id' => $unit->alat_id,
                    'alat_unit_id' => $unit->id,
                    'jumlah_pinjam' => 1,
                ]);
            }
        });

        if ($peminjamanId) {
            LogAktivitas::catat(
                'Create',
                'Peminjaman',
                "Pengajuan peminjaman #{$peminjamanId} ({$totalItem} item)"
            );

            Notification::pushForUser(
                auth()->id(),
                'Pengajuan Peminjaman Dikirim',
                "Pengajuan peminjaman #{$peminjamanId} berhasil dikirim."
            );
            Notification::pushForRole(
                'petugas',
                'Pengajuan Peminjaman Baru',
                "Ada pengajuan peminjaman baru #{$peminjamanId} yang menunggu verifikasi."
            );
            Notification::pushForRole(
                'admin',
                'Pengajuan Peminjaman Baru',
                "Pengajuan peminjaman baru #{$peminjamanId} telah dibuat."
            );
        }

        return back()->with('success', 'Pengajuan dikirim');
    }
}
