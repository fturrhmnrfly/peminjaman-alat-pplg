<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Barryvdh\DomPDF\Facade\Pdf;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjaman = Peminjaman::with(['user', 'detailPeminjamans.alatUnit.alat', 'detailPeminjamans.alat'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('peminjaman.index', [
            'peminjaman' => $peminjaman
        ]);
    }

    public function exportPdf()
    {
        $rows = Peminjaman::with(['user', 'detailPeminjamans.alatUnit.alat', 'detailPeminjamans.alat'])
            ->orderBy('created_at', 'desc')
            ->get();

        $pdf = Pdf::loadView('peminjaman.pdf', [
            'rows' => $rows,
            'printedAt' => now('Asia/Jakarta'),
        ])->setPaper('a4', 'landscape');

        $filename = 'laporan_peminjaman_admin_' . now('Asia/Jakarta')->format('Y-m-d_His') . '.pdf';

        return $pdf->download($filename);
    }
}
