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
        $kategori = Kategori::query()
            ->select(['id', 'nama_kategori'])
            ->orderByRaw(
            "CASE
                WHEN LOWER(nama_kategori) = 'laptop' THEN 0
                WHEN LOWER(nama_kategori) = 'proyektor' THEN 1
                ELSE 2
             END, nama_kategori"
        )->get();

        $alatQuery = Alat::query()
            ->select(['id', 'kategori_id', 'nama_alat', 'keterangan', 'foto', 'jumlah', 'kondisi'])
            ->with([
                'kategori:id,nama_kategori',
                'units' => function ($query) {
                    $query->select(['id', 'alat_id', 'kode_unik', 'status'])
                        ->orderBy('kode_unik');
                },
            ])
            ->orderBy('nama_alat');

        if ($request->filled('kategori_id')) {
            $alatQuery->where('kategori_id', $request->kategori_id);
        }

        $alat = $alatQuery->get();

        return view('peminjam.alat.index', [
            'alat' => $alat,
            'kategori' => $kategori,
            'selectedKategori' => $request->kategori_id,
        ]);
    }

    public function show(Alat $alat)
    {
        $alat->load([
            'kategori:id,nama_kategori',
            'units' => function ($query) {
                $query->select(['id', 'alat_id', 'kode_unik', 'status', 'kondisi', 'keterangan'])
                    ->orderBy('kode_unik');
            },
        ]);

        return view('peminjam.alat.show', [
            'alat' => $alat,
        ]);
    }
}
