<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PendaftaranPenjualController;
use App\Http\Controllers\Admin\LaporanController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\PenjualController   as AdminPenjual;
use App\Http\Controllers\Admin\SiswaController     as AdminSiswa;
use App\Http\Controllers\Admin\LaporanController   as AdminLaporan;
use App\Http\Controllers\Admin\RecycleBinController   as AdminRecycleBin;
use App\Http\Controllers\Penjual\DashboardController as PenjualDashboard;
use App\Http\Controllers\Penjual\MenuController      as PenjualMenu;
use App\Http\Controllers\Penjual\PesananController   as PenjualPesanan;
use App\Http\Controllers\Penjual\RecycleBinController as PenjualRecycleBin;
use App\Http\Controllers\Siswa\DashboardController   as SiswaDashboard;
use App\Http\Controllers\Siswa\PesananController     as SiswaPesanan;

/* ── Public ── */
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/daftar-penjual',  [PendaftaranPenjualController::class, 'create'])->name('pendaftaran-penjual.create');
Route::post('/daftar-penjual', [PendaftaranPenjualController::class, 'store'])->name('pendaftaran-penjual.store');

/* ── Auth ── */
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/* ── Profile ── */
Route::middleware('auth')->prefix('profile')->name('profile.')->group(function () {
    Route::get('/',           [ProfileController::class, 'edit'])->name('edit');
    Route::patch('/',         [ProfileController::class, 'update'])->name('update');
    Route::patch('/password', [ProfileController::class, 'updatePassword'])->name('password');
    Route::delete('/',        [ProfileController::class, 'destroy'])->name('destroy');
});

/* ── Admin ── */
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    Route::resource('penjual', AdminPenjual::class)->except(['show']);
    Route::get('/penjual-pendaftaran',                        [AdminPenjual::class, 'pendaftaran'])->name('penjual.pendaftaran');
    Route::get('/penjual-pendaftaran/{pendaftaran}',          [AdminPenjual::class, 'showPendaftaran'])->name('penjual.pendaftaran.show');
    Route::post('/penjual-pendaftaran/{pendaftaran}/approve', [AdminPenjual::class, 'approvePendaftaran'])->name('penjual.pendaftaran.approve');
    Route::post('/penjual-pendaftaran/{pendaftaran}/reject',  [AdminPenjual::class, 'rejectPendaftaran'])->name('penjual.pendaftaran.reject');

    Route::get('/siswa',                  [AdminSiswa::class, 'index'])->name('siswa.index');
    Route::get('/siswa/{siswa}',          [AdminSiswa::class, 'show'])->name('siswa.show');
    Route::patch('/siswa/{siswa}/toggle', [AdminSiswa::class, 'toggleStatus'])->name('siswa.toggle');
    Route::delete('/siswa/{siswa}',       [AdminSiswa::class, 'destroy'])->name('siswa.destroy');

    Route::get('/laporan', [AdminLaporan::class, 'index'])->name('laporan.index');
    Route::get('/laporan', [AdminLaporan::class, 'index'])
    ->name('laporan.index');

    Route::get('/laporan/export-excel', [AdminLaporan::class, 'exportExcel'])
        ->name('laporan.export-excel');

    Route::get('/laporan/export-pdf', [AdminLaporan::class, 'exportPdf'])
        ->name('laporan.export-pdf');

    /* Recycle Bin (Soft Delete) — akun penjual & siswa yang dihapus */
    Route::get('/recycle-bin',                      [AdminRecycleBin::class, 'index'])->name('recycle-bin.index');
    Route::patch('/recycle-bin/{id}/restore',        [AdminRecycleBin::class, 'restore'])->name('recycle-bin.restore');
    Route::delete('/recycle-bin/{id}/force-delete',  [AdminRecycleBin::class, 'forceDelete'])->name('recycle-bin.force-delete');
    Route::delete('/recycle-bin-kosongkan',          [AdminRecycleBin::class, 'empty'])->name('recycle-bin.empty');
});

/* ── Penjual ── */
Route::middleware(['auth', 'role:penjual'])->prefix('penjual')->name('penjual.')->group(function () {
    Route::get('/dashboard', [PenjualDashboard::class, 'index'])->name('dashboard');

    Route::resource('menu', PenjualMenu::class)->except(['show']);
    Route::patch('/menu/{menu}/toggle', [PenjualMenu::class, 'toggleTersedia'])->name('menu.toggle');

    /* Recycle Bin (Soft Delete) — menu yang dihapus */
    Route::get('/menu-recycle-bin',                     [PenjualRecycleBin::class, 'index'])->name('recycle-bin.index');
    Route::patch('/menu-recycle-bin/{id}/restore',       [PenjualRecycleBin::class, 'restore'])->name('recycle-bin.restore');
    Route::delete('/menu-recycle-bin/{id}/force-delete', [PenjualRecycleBin::class, 'forceDelete'])->name('recycle-bin.force-delete');
    Route::delete('/menu-recycle-bin-kosongkan',         [PenjualRecycleBin::class, 'empty'])->name('recycle-bin.empty');

    Route::get('/pesanan',           [PenjualPesanan::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan/riwayat',   [PenjualPesanan::class, 'riwayat'])->name('pesanan.riwayat');
    Route::get('/pesanan/{pesanan}', [PenjualPesanan::class, 'show'])->name('pesanan.show');

    // FIX #1: updateStatus selalu return JSON
    Route::patch('/pesanan/{pesanan}/status',             [PenjualPesanan::class, 'updateStatus'])->name('pesanan.status');
    // FIX #2: verifikasi bukti transfer
    Route::post('/pesanan/{pesanan}/verifikasi-transfer', [PenjualPesanan::class, 'verifikasiTransfer'])->name('pesanan.verifikasi-transfer');

    // Ulasan (FIX #5)
    Route::get('/ulasan', [PenjualPesanan::class, 'ulasan'])->name('ulasan.index');
    
});

/* ── Siswa ── */
Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [SiswaDashboard::class, 'index'])->name('dashboard');

    Route::get('/keranjang',              [SiswaPesanan::class, 'keranjang'])->name('keranjang');
    Route::post('/keranjang/tambah',      [SiswaPesanan::class, 'tambahKeranjang'])->name('keranjang.tambah');
    Route::patch('/keranjang/update',     [SiswaPesanan::class, 'updateKeranjang'])->name('keranjang.update');
    Route::delete('/keranjang/hapus',     [SiswaPesanan::class, 'hapusKeranjang'])->name('keranjang.hapus');
    Route::delete('/keranjang/kosongkan', [SiswaPesanan::class, 'kosongkanKeranjang'])->name('keranjang.kosongkan');

    Route::get('/checkout',  [SiswaPesanan::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [SiswaPesanan::class, 'prosesPemesanan'])->name('checkout.proses');

    Route::get('/pesanan',                                 [SiswaPesanan::class, 'riwayat'])->name('pesanan.riwayat');
    Route::get('/pesanan/{pesanan}',                       [SiswaPesanan::class, 'detail'])->name('pesanan.detail');
    Route::patch('/pesanan/{pesanan}/batalkan',            [SiswaPesanan::class, 'batalkan'])->name('pesanan.batalkan');
    // FIX #2: upload bukti transfer
    Route::post('/pesanan/{pesanan}/bukti-transfer',       [SiswaPesanan::class, 'uploadBuktiTransfer'])->name('pesanan.bukti-transfer');
    // FIX #5: buat ulasan
    Route::post('/pesanan/{pesanan}/ulasan',               [SiswaPesanan::class, 'buatUlasan'])->name('pesanan.ulasan');
});
