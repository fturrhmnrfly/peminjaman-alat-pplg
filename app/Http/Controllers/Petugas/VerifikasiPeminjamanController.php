<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
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
        LogAktivitas::catat('Update', 'Peminjaman', "Menyetujui peminjaman #{$peminjaman->id}");
        return back();
    }

    public function tolak(Peminjaman $peminjaman){
        $peminjaman->update(['status'=>'ditolak']);
        LogAktivitas::catat('Update', 'Peminjaman', "Menolak peminjaman #{$peminjaman->id}");
        return back();
    }
}
