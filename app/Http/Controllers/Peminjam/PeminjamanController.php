<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\DetailPeminjaman;
use App\Models\LogAktivitas;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjaman = Peminjaman::with('detailPeminjamans.alat')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        $alat = Alat::with('kategori')->orderBy('nama_alat')->get();

        return view('peminjam.peminjaman.index', [
            'peminjaman' => $peminjaman,
            'alat' => $alat,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_pinjam' => ['required', 'date', 'after_or_equal:today'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.alat_id' => [
                'required',
                Rule::exists('alats', 'id')->where(function ($query) {
                    $query->where('kondisi', 'baik')->where('jumlah', '>', 0);
                }),
            ],
            'items.*.jumlah_pinjam' => ['required', 'integer', 'min:1'],
        ]);

        $items = collect($validated['items'])
            ->groupBy('alat_id')
            ->map(function ($rows) {
                return $rows->sum('jumlah_pinjam');
            });

        $alatMap = Alat::whereIn('id', $items->keys())->get()->keyBy('id');

        foreach ($items as $alatId => $jumlah) {
            $alatItem = $alatMap->get($alatId);
            if (! $alatItem) {
                return back()->withErrors(['items' => 'Alat tidak ditemukan.'])->withInput();
            }

            if ($alatItem->kondisi !== 'baik' || $alatItem->jumlah <= 0) {
                return back()->withErrors([
                    'items' => "Alat {$alatItem->nama_alat} tidak bisa dipinjam."
                ])->withInput();
            }

            if ($jumlah > $alatItem->jumlah) {
                return back()->withErrors([
                    'items' => "Stok {$alatItem->nama_alat} tidak cukup. Tersedia {$alatItem->jumlah}."
                ])->withInput();
            }
        }

        $peminjamanId = null;
        $totalItem = $items->sum();
        DB::transaction(function () use ($validated, $items, &$peminjamanId) {
            $peminjaman = Peminjaman::create([
                'user_id' => auth()->id(),
                'tanggal_pinjam' => $validated['tanggal_pinjam'],
                'status' => 'pending',
            ]);
            $peminjamanId = $peminjaman->id;

            foreach ($items as $alatId => $jumlah) {
                DetailPeminjaman::create([
                    'peminjaman_id' => $peminjaman->id,
                    'alat_id' => $alatId,
                    'jumlah_pinjam' => $jumlah,
                ]);
            }
        });

        if ($peminjamanId) {
            LogAktivitas::catat(
                'Create',
                'Peminjaman',
                "Pengajuan peminjaman #{$peminjamanId} ({$totalItem} item)"
            );
        }

        return back()->with('success', 'Pengajuan dikirim');
    }
}
