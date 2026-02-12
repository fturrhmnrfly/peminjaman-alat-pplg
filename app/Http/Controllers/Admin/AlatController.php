<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\AlatUnit;
use App\Models\Kategori;
use App\Models\LogAktivitas;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlatController extends Controller
{
    // Menampilkan daftar alat
    public function index()
    {
        $alats = Alat::with(['kategori', 'units'])->orderBy('id', 'asc')->paginate(10);
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
            'kode_unik_list' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $kodeRaw = $request->input('kode_unik_list');
        $kodeList = collect(preg_split("/\r\n|\n|\r/", (string) $kodeRaw))
            ->map(function ($item) {
                return trim($item);
            })
            ->filter()
            ->values();

        if ($request->filled('kode_unik_list') && $kodeList->isEmpty()) {
            return back()
                ->withErrors(['kode_unik_list' => 'Kode unik tidak boleh kosong.'])
                ->withInput();
        }

        if ($kodeList->count() !== $kodeList->unique()->count()) {
            return back()
                ->withErrors(['kode_unik_list' => 'Kode unik tidak boleh duplikat.'])
                ->withInput();
        }

        if ($kodeList->isNotEmpty() && AlatUnit::whereIn('kode_unik', $kodeList)->exists()) {
            return back()
                ->withErrors(['kode_unik_list' => 'Kode unik sudah digunakan.'])
                ->withInput();
        }

        $kategori = Kategori::find($request->kategori_id);
        if ($kategori && strtolower($kategori->nama_kategori) === 'laptop' && $kodeList->isEmpty()) {
            return back()
                ->withErrors(['kode_unik_list' => 'Kode unik wajib diisi untuk kategori laptop.'])
                ->withInput();
        }

        $data = [
            'nama_alat' => $request->nama_alat,
            'kategori_id' => $request->kategori_id,
            'jumlah' => $request->jumlah,
            'kondisi' => $request->kondisi,
            'keterangan' => $request->keterangan,
        ];

        if ($kodeList->isNotEmpty()) {
            $data['jumlah'] = $kodeList->count();
        }

        // Upload foto jika ada
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $data['foto'] = $file->storeAs('alat', $filename, 'public');
        }

        $alat = Alat::create($data);

        if ($kodeList->isNotEmpty()) {
            $alat->units()->createMany(
                $kodeList->map(function ($kode) {
                    return [
                        'kode_unik' => $kode,
                        'kondisi' => 'baik',
                        'status' => 'tersedia',
                    ];
                })->all()
            );
        }

        LogAktivitas::catat('Create', 'Alat', "Menambah alat: {$alat->nama_alat}");
        Notification::pushForRole('petugas', 'Alat Baru Ditambahkan', "Alat {$alat->nama_alat} telah ditambahkan.");
        Notification::pushForRole('peminjam', 'Alat Baru Tersedia', "Alat {$alat->nama_alat} kini tersedia untuk dipinjam.");

        return redirect()->route('admin.alat.index')->with('success', 'Alat berhasil ditambahkan!');
    }

    // Menampilkan form edit alat
    public function edit(Alat $alat)
    {
        $kategoris = Kategori::orderBy('nama_kategori', 'asc')->get();
        $alat->load('units');
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
            'kode_unik_tambah' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $kodeRaw = $request->input('kode_unik_tambah');
        $kodeList = collect(preg_split("/\r\n|\n|\r/", (string) $kodeRaw))
            ->map(function ($item) {
                return trim($item);
            })
            ->filter()
            ->values();

        if ($request->filled('kode_unik_tambah') && $kodeList->isEmpty()) {
            return back()
                ->withErrors(['kode_unik_tambah' => 'Kode unik tidak boleh kosong.'])
                ->withInput();
        }

        if ($kodeList->count() !== $kodeList->unique()->count()) {
            return back()
                ->withErrors(['kode_unik_tambah' => 'Kode unik tidak boleh duplikat.'])
                ->withInput();
        }

        if ($kodeList->isNotEmpty() && AlatUnit::whereIn('kode_unik', $kodeList)->exists()) {
            return back()
                ->withErrors(['kode_unik_tambah' => 'Kode unik sudah digunakan.'])
                ->withInput();
        }

        $data = [
            'nama_alat' => $request->nama_alat,
            'kategori_id' => $request->kategori_id,
            'jumlah' => $request->jumlah,
            'kondisi' => $request->kondisi,
            'keterangan' => $request->keterangan,
        ];

        $existingUnitCount = $alat->units()->count();
        if ($existingUnitCount > 0 || $kodeList->isNotEmpty()) {
            $data['jumlah'] = $existingUnitCount + $kodeList->count();
        }

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

        if ($kodeList->isNotEmpty()) {
            $alat->units()->createMany(
                $kodeList->map(function ($kode) {
                    return [
                        'kode_unik' => $kode,
                        'kondisi' => 'baik',
                        'status' => 'tersedia',
                    ];
                })->all()
            );
        }

        LogAktivitas::catat('Update', 'Alat', "Update alat: {$alat->nama_alat}");
        Notification::pushForRole('petugas', 'Data Alat Diperbarui', "Data alat {$alat->nama_alat} telah diperbarui.");

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
        Notification::pushForRole('petugas', 'Alat Dihapus', "Alat {$namaAlat} telah dihapus dari sistem.");
        return redirect()->route('admin.alat.index')->with('success', 'Alat berhasil dihapus!');
    }
}
