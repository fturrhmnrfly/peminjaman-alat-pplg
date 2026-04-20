<?php
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\AlatController;
use App\Http\Controllers\Admin\LogAktivitasController;
use App\Http\Controllers\Petugas\VerifikasiPeminjamanController;
use App\Http\Controllers\Petugas\LaporanController as PetugasLaporanController;
use App\Http\Controllers\Petugas\PengembalianController as PetugasPengembalianController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Peminjam\PeminjamanController;
use App\Http\Controllers\Peminjam\AlatController as PeminjamAlatController;
use App\Http\Controllers\Peminjam\PengembalianController as PeminjamPengembalianController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifikasi/{notification}/read', [NotificationController::class, 'markAsRead'])
        ->name('notifications.read');
    Route::patch('/notifikasi/read-all', [NotificationController::class, 'markAllAsRead'])
        ->name('notifications.read-all');
    Route::delete('/notifikasi', [NotificationController::class, 'destroyAll'])
        ->name('notifications.destroy-all');
    Route::delete('/notifikasi/{notification}', [NotificationController::class, 'destroy'])
        ->name('notifications.destroy');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('user', UserController::class);
    Route::resource('kategori', KategoriController::class);
    Route::resource('alat', AlatController::class);
    Route::resource('log', LogAktivitasController::class)->only(['index']);
    Route::get('log/export', [LogAktivitasController::class, 'export'])->name('log.export');
    Route::get('log/export-pdf', [LogAktivitasController::class, 'exportPdf'])->name('log.export-pdf');
    Route::get('peminjaman/export-pdf', [\App\Http\Controllers\Admin\PeminjamanController::class, 'exportPdf'])->name('peminjaman.export-pdf');
    Route::resource('peminjaman', \App\Http\Controllers\Admin\PeminjamanController::class)->only(['index']);
});

// Petugas Routes
Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->group(function () {
    Route::get('/verifikasi', [VerifikasiPeminjamanController::class, 'index'])->name('verifikasi');
    Route::patch('/peminjaman/{peminjaman}/setujui', [VerifikasiPeminjamanController::class, 'setujui'])->name('peminjaman.setujui');
    Route::patch('/peminjaman/{peminjaman}/tolak', [VerifikasiPeminjamanController::class, 'tolak'])->name('peminjaman.tolak');
    Route::get('/laporan', [PetugasLaporanController::class, 'index'])->name('petugas.laporan');
    Route::get('/laporan/export-pdf', [PetugasLaporanController::class, 'exportPdf'])->name('petugas.laporan.export-pdf');
    Route::get('/pengembalian', [PetugasPengembalianController::class, 'index'])->name('petugas.pengembalian');
    Route::patch('/pengembalian/{peminjaman}/terima', [PetugasPengembalianController::class, 'terima'])
        ->name('petugas.pengembalian.terima');
    Route::patch('/pengembalian/{peminjaman}/konfirmasi', [PetugasPengembalianController::class, 'konfirmasi'])
        ->name('petugas.pengembalian.konfirmasi');
    Route::patch('/pengembalian/{peminjaman}/pembayaran', [PetugasPengembalianController::class, 'konfirmasiPembayaran'])
        ->name('petugas.pengembalian.pembayaran');
});

// Peminjam Routes
Route::middleware(['auth', 'role:peminjam'])->prefix('peminjam')->group(function () {
    Route::get('alat', [PeminjamAlatController::class, 'index'])->name('peminjam.alat.index');
    Route::get('alat/{alat}', [PeminjamAlatController::class, 'show'])->name('peminjam.alat.show');
    Route::resource('peminjaman', PeminjamanController::class)->only(['index', 'store']);
    Route::get('pengembalian', [PeminjamPengembalianController::class, 'index'])->name('peminjam.pengembalian.index');
    Route::patch('pengembalian/{peminjaman}', [PeminjamPengembalianController::class, 'update'])
        ->name('peminjam.pengembalian.update');
    Route::patch('pengembalian/{peminjaman}/bayar-denda', [PeminjamPengembalianController::class, 'bayarDenda'])
        ->name('peminjam.pengembalian.bayar-denda');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
