<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AlatController as AdminAlatController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\PeminjamanController as AdminPeminjamanController;
use App\Http\Controllers\Admin\PengembalianController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Petugas\DashboardController as PetugasDashboard;
use App\Http\Controllers\Petugas\ApprovalController;
use App\Http\Controllers\Petugas\MonitorController;
use App\Http\Controllers\Petugas\LaporanController;
use App\Http\Controllers\Peminjam\DashboardController as PeminjamDashboard;
use App\Http\Controllers\Peminjam\AlatController as PeminjamAlatController;
use App\Http\Controllers\Peminjam\PinjamController;

use App\Http\Controllers\Peminjam\PengembalianController as PeminjamPengembalianController;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => redirect()->route('login'));
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    // CRUD Users
    Route::resource('users', UserController::class)->except(['show']);

    // CRUD Alat
    Route::resource('alat', AdminAlatController::class);

    // CRUD Kategori
    Route::resource('kategori', KategoriController::class)->except(['show']);

    // CRUD Peminjaman
    Route::resource('peminjaman', AdminPeminjamanController::class);

    // CRUD Pengembalian
    Route::resource('pengembalian', PengembalianController::class)->except(['show', 'edit', 'update']);

    // Log Aktivitas
    Route::get('/log', [LogController::class, 'index'])->name('log.index');
});

/*
|--------------------------------------------------------------------------
| Petugas Routes
|--------------------------------------------------------------------------
*/
Route::prefix('petugas')->name('petugas.')->middleware(['auth', 'role:petugas'])->group(function () {
    Route::get('/dashboard', [PetugasDashboard::class, 'index'])->name('dashboard');

    // Approval Peminjaman
    Route::get('/approval', [ApprovalController::class, 'index'])->name('approval.index');
    Route::get('/approval/{peminjaman}', [ApprovalController::class, 'show'])->name('approval.show');
    Route::post('/approval/{peminjaman}/approve', [ApprovalController::class, 'approve'])->name('approval.approve');
    Route::post('/approval/{peminjaman}/reject', [ApprovalController::class, 'reject'])->name('approval.reject');
    Route::post('/approval/{peminjaman}/accept-return', [ApprovalController::class, 'acceptReturn'])->name('approval.acceptReturn');
    Route::post('/approval/{peminjaman}/reject-return', [ApprovalController::class, 'rejectReturn'])->name('approval.rejectReturn');

    // Monitor Pengembalian
    Route::get('/monitor', [MonitorController::class, 'index'])->name('monitor.index');
    Route::get('/monitor/{peminjaman}/return', [MonitorController::class, 'returnForm'])->name('monitor.return');
    Route::post('/monitor/{peminjaman}/return', [MonitorController::class, 'processReturn'])->name('monitor.processReturn');

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/print', [LaporanController::class, 'print'])->name('laporan.print');
    Route::get('/laporan/pdf', [LaporanController::class, 'downloadPdf'])->name('laporan.pdf');
});

/*
|--------------------------------------------------------------------------
| Peminjam Routes
|--------------------------------------------------------------------------
*/
Route::prefix('peminjam')->name('peminjam.')->middleware(['auth', 'role:peminjam'])->group(function () {
    Route::get('/dashboard', [PeminjamDashboard::class, 'index'])->name('dashboard');

    // Lihat Daftar Alat
    Route::get('/alat', [PeminjamAlatController::class, 'index'])->name('alat.index');
    Route::get('/alat/{alat}', [PeminjamAlatController::class, 'show'])->name('alat.show');

    // Peminjaman
    Route::get('/pinjam', [PinjamController::class, 'index'])->name('pinjam.index');
    Route::get('/pinjam/create', [PinjamController::class, 'create'])->name('pinjam.create');
    Route::post('/pinjam', [PinjamController::class, 'store'])->name('pinjam.store');
    Route::get('/pinjam/{peminjaman}', [PinjamController::class, 'show'])->name('pinjam.show');
    Route::post('/pinjam/{peminjaman}/cancel', [PinjamController::class, 'cancel'])->name('pinjam.cancel');

    // Pengembalian
    Route::get('/pinjam/{peminjaman}/return', [PinjamController::class, 'returnForm'])->name('pinjam.return');
    Route::post('/pinjam/{peminjaman}/return', [PinjamController::class, 'processReturn'])->name('pinjam.processReturn');

    // Riwayat Pengembalian
    Route::get('/pengembalian', [PeminjamPengembalianController::class, 'index'])->name('pengembalian.index');

    // Denda / Pembayaran
    Route::get('/denda', [\App\Http\Controllers\Peminjam\DendaController::class, 'index'])->name('denda.index');
    Route::get('/denda/{pengembalian}/bayar', [\App\Http\Controllers\Peminjam\DendaController::class, 'pay'])->name('denda.pay');
    Route::post('/denda/{pengembalian}/bayar', [\App\Http\Controllers\Peminjam\DendaController::class, 'processPayment'])->name('denda.process');
});
