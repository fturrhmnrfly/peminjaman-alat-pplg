<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kategori;
use App\Models\Alat;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->role;

        $data = [];

        if ($role === 'admin') {
            $data = [
                'user' => $user,
                'totalUsers' => User::count(),
                'totalKategori' => Kategori::count(),
                'totalAlat' => Alat::count(),
                'totalPeminjaman' => Peminjaman::count(),
            ];
            return view('Dashboard.admin', $data);
        }

        if ($role === 'petugas') {
            return view('Dashboard.petugas', ['user' => $user]);
        }

        if ($role === 'peminjam') {
            $peminjamanUser = Peminjaman::with('detailPeminjamans')
                ->where('user_id', $user->id)
                ->get();

            $data = [
                'user' => $user,
                'totalAlat' => Alat::count(),
                'pendingCount' => $peminjamanUser->where('status', Peminjaman::STATUS_PENDING)->count(),
                'disetujuiCount' => $peminjamanUser->where('status', Peminjaman::STATUS_DISETUJUI)->count(),
                'ditolakCount' => $peminjamanUser->where('status', Peminjaman::STATUS_DITOLAK)->count(),
                'dikembalikanCount' => $peminjamanUser->where('status', Peminjaman::STATUS_DIKEMBALIKAN)->count(),
                'totalDenda' => $peminjamanUser
                    ->filter(function (Peminjaman $peminjaman) {
                        return in_array($peminjaman->status, [
                            Peminjaman::STATUS_PENGEMBALIAN_PENDING,
                            Peminjaman::STATUS_MENUNGGU_PEMERIKSAAN,
                            Peminjaman::STATUS_MENUNGGU_PEMBAYARAN,
                            Peminjaman::STATUS_DIKEMBALIKAN,
                        ], true);
                    })
                    ->sum(function (Peminjaman $peminjaman) {
                        return $peminjaman->sisa_denda;
                    }),
            ];

            return view('Dashboard.peminjam', $data);
        }

        abort(403, 'Role tidak dikenali');
    }
}
