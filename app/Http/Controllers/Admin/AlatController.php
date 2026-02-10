<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Kategori;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlatController extends Controller
{
    // Menampilkan daftar alat
    public function index()
    {
        $alats = Alat::with('kategori')->orderBy('created_at', 'desc')->paginate(10);
        return view('alat.index', compact('alats'));
    }

    // Menampilkan form tambah alat
    public function create()
    {
        $kategoris = Kategori::orderBy('nama_kategori', 'asc')->get();
        return view('alat.create', compact('kategoris'));
    }

    // Menyimpan alat baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_alat' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'jumlah' => 'required|integer|min:0',
            'kondisi' => 'required|in:baik,rusak,hilang',
            'keterangan' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'nama_alat' => $request->nama_alat,
            'kategori_id' => $request->kategori_id,
            'jumlah' => $request->jumlah,
            'kondisi' => $request->kondisi,
            'keterangan' => $request->keterangan,
        ];

        // Upload foto jika ada
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $data['foto'] = $file->storeAs('alat', $filename, 'public');
        }

        $alat = Alat::create($data);
        LogAktivitas::catat('Create', 'Alat', "Menambah alat: {$alat->nama_alat}");

        return redirect()->route('admin.alat.index')->with('success', 'Alat berhasil ditambahkan!');
    }

    // Menampilkan form edit alat
    public function edit(Alat $alat)
    {
        $kategoris = Kategori::orderBy('nama_kategori', 'asc')->get();
        return view('alat.edit', compact('alat', 'kategoris'));
    }

    // Update alat
    public function update(Request $request, Alat $alat)
    {
        $request->validate([
            'nama_alat' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'jumlah' => 'required|integer|min:0',
            'kondisi' => 'required|in:baik,rusak,hilang',
            'keterangan' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'nama_alat' => $request->nama_alat,
            'kategori_id' => $request->kategori_id,
            'jumlah' => $request->jumlah,
            'kondisi' => $request->kondisi,
            'keterangan' => $request->keterangan,
        ];

        // Upload foto baru jika ada
        if ($request->hasFile('foto')) {
            // Hapus foto lama
            if ($alat->foto) {
                Storage::disk('public')->delete($alat->foto);
            }

            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $data['foto'] = $file->storeAs('alat', $filename, 'public');
        }

        $alat->update($data);
        LogAktivitas::catat('Update', 'Alat', "Update alat: {$alat->nama_alat}");

        return redirect()->route('admin.alat.index')->with('success', 'Alat berhasil diupdate!');
    }

    // Hapus alat
    public function destroy(Alat $alat)
    {
        // Hapus foto jika ada
        if ($alat->foto) {
            Storage::disk('public')->delete($alat->foto);
        }

        $namaAlat = $alat->nama_alat;
        $alat->delete();
        LogAktivitas::catat('Delete', 'Alat', "Menghapus alat: {$namaAlat}");
        return redirect()->route('admin.alat.index')->with('success', 'Alat berhasil dihapus!');
    }
}
