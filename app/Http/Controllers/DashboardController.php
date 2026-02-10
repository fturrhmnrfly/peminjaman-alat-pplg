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
            $data = [
                'user' => $user,
                'totalAlat' => Alat::count(),
                'pendingCount' => Peminjaman::where('user_id', $user->id)->where('status', 'pending')->count(),
                'disetujuiCount' => Peminjaman::where('user_id', $user->id)->where('status', 'disetujui')->count(),
                'ditolakCount' => Peminjaman::where('user_id', $user->id)->where('status', 'ditolak')->count(),
                'dikembalikanCount' => Peminjaman::where('user_id', $user->id)->where('status', 'dikembalikan')->count(),
            ];

            return view('Dashboard.peminjam', $data);
        }

        abort(403, 'Role tidak dikenali');
    }
}
