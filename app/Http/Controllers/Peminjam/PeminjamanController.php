<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function index(){
        return view('peminjam.peminjaman.index',[
            'peminjaman'=>Peminjaman::where('user_id',auth()->id())->get()
        ]);
    }

    public function store(Request $request){
        Peminjaman::create([
            'user_id'=>auth()->id(),
            'tanggal_pinjam'=>$request->tanggal_pinjam,
            'status'=>'pending'
        ]);

        return back()->with('success','Pengajuan dikirim');
    }
}
