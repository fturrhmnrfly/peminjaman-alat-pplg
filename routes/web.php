<?php
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\AlatController;
use App\Http\Controllers\Admin\LogAktivitasController;
use App\Http\Controllers\Petugas\VerifikasiPeminjamanController;
use App\Http\Controllers\Peminjam\PeminjamanController;
use App\Http\Controllers\Peminjam\AlatController as PeminjamAlatController;
use App\Http\Controllers\Peminjam\PengembalianController as PeminjamPengembalianController;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Admin Routes - TAMBAHKAN ->name('admin.')
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('user', UserController::class);
    Route::resource('kategori', KategoriController::class);
    Route::resource('alat', AlatController::class);
    Route::resource('log', LogAktivitasController::class)->only(['index']);
    Route::get('log/export', [LogAktivitasController::class, 'export'])->name('log.export');
    Route::resource('peminjaman', \App\Http\Controllers\Admin\PeminjamanController::class)->only(['index']);
});

// Petugas Routes
Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->group(function () {
    Route::get('/verifikasi', [VerifikasiPeminjamanController::class, 'index'])->name('verifikasi');
    Route::patch('/peminjaman/{peminjaman}/setujui', [VerifikasiPeminjamanController::class, 'setujui'])->name('peminjaman.setujui');
    Route::patch('/peminjaman/{peminjaman}/tolak', [VerifikasiPeminjamanController::class, 'tolak'])->name('peminjaman.tolak');
    Route::get('/laporan', function () {
        return view('petugas.laporan.index');
    })->name('petugas.laporan');
    Route::get('/pengembalian', function () {
        $peminjaman = Peminjaman::with(['user', 'detailPeminjamans.alat'])
            ->where('status', 'disetujui')
            ->orderBy('tanggal_pinjam', 'desc')
            ->get();

        return view('petugas.pengembalian.index', [
            'peminjaman' => $peminjaman,
        ]);
    })->name('petugas.pengembalian');
});

// Peminjam Routes
Route::middleware(['auth', 'role:peminjam'])->prefix('peminjam')->group(function () {
    Route::get('alat', [PeminjamAlatController::class, 'index'])->name('peminjam.alat.index');
    Route::resource('peminjaman', PeminjamanController::class);
    Route::get('pengembalian', [PeminjamPengembalianController::class, 'index'])->name('peminjam.pengembalian.index');
    Route::patch('pengembalian/{peminjaman}', [PeminjamPengembalianController::class, 'update'])
        ->name('peminjam.pengembalian.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
