<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Menampilkan daftar user
    public function index()
    {
        $users = User::orderBy('id', 'asc')->paginate(10);
        return view('user.index', compact('users'));
    }

    // Menampilkan form tambah user
    public function create()
    {
        return view('user.create');
    }

    // Menyimpan user baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username|max:255',
            'password' => 'required|min:6',
            'nis' => 'nullable|string|max:255',
            'role' => 'required|in:admin,petugas,peminjam',
        ]);

        $user = User::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'nis' => $request->nis,
            'role' => $request->role,
        ]);
        LogAktivitas::catat('Create', 'User', "Menambah user: {$user->nama}");

        return redirect()->route('admin.user.index')->with('success', 'User berhasil ditambahkan!');
    }

    // Menampilkan form edit user
    public function edit(User $user)
    {
        return view('user.edit', compact('user'));
    }

    // Update user
    public function update(Request $request, User $user)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username,' . $user->id . '|max:255',
            'nis' => 'nullable|string|max:255',
            'role' => 'required|in:admin,petugas,peminjam',
        ]);

        $data = [
            'nama' => $request->nama,
            'username' => $request->username,
            'nis' => $request->nis,
            'role' => $request->role,
        ];

        // Update password hanya jika diisi
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        LogAktivitas::catat('Update', 'User', "Update user: {$user->nama}");

        return redirect()->route('admin.user.index')->with('success', 'User berhasil diupdate!');
    }

    // Hapus user
    public function destroy(User $user)
    {
        // Jangan hapus user yang sedang login
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.user.index')->with('error', 'Tidak dapat menghapus user yang sedang login!');
        }

        $namaUser = $user->nama;
        $user->delete();
        LogAktivitas::catat('Delete', 'User', "Menghapus user: {$namaUser}");
        return redirect()->route('admin.user.index')->with('success', 'User berhasil dihapus!');
    }
}
