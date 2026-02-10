<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Kategori;
use Illuminate\Http\Request;

class AlatController extends Controller
{
    public function index(Request $request)
    {
        $kategori = Kategori::orderByRaw(
            "CASE
                WHEN LOWER(nama_kategori) = 'laptop' THEN 0
                WHEN LOWER(nama_kategori) = 'proyektor' THEN 1
                ELSE 2
             END, nama_kategori"
        )->get();

        $alatQuery = Alat::with('kategori')->orderBy('nama_alat');

        if ($request->filled('kategori_id')) {
            $alatQuery->where('kategori_id', $request->kategori_id);
        }

        if ($request->filled('kondisi')) {
            $alatQuery->where('kondisi', $request->kondisi);
        }

        $alat = $alatQuery->get();

        return view('peminjam.alat.index', [
            'alat' => $alat,
            'kategori' => $kategori,
            'selectedKategori' => $request->kategori_id,
            'selectedKondisi' => $request->kondisi,
        ]);
    }
}
