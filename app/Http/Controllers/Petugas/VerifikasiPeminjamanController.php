<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;

class VerifikasiPeminjamanController extends Controller
{
    public function index(){
        return view('petugas.peminjaman.index',[
            'data'=>Peminjaman::where('status','pending')->get()
        ]);
    }

    public function setujui(Peminjaman $peminjaman){
        $peminjaman->update(['status'=>'disetujui']);
        return back();
    }

    public function tolak(Peminjaman $peminjaman){
        $peminjaman->update(['status'=>'ditolak']);
        return back();
    }
}
