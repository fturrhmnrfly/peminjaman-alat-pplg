<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjaman = Peminjaman::with(['user', 'detailPeminjamans.alat'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('peminjaman.index', [
            'peminjaman' => $peminjaman
        ]);
    }
}
