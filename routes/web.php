<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SesiController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\JadwalKapalController;
use App\Http\Controllers\KapalController;
use App\Http\Controllers\PelabuhanController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\PenumpangController;
use App\Http\Controllers\TiketController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ── Halaman Login / Logout ──────────────────────────────────────────────
Route::get('/', [SesiController::class, 'index'])->name('login');
Route::post('/login', [SesiController::class, 'login']);
Route::get('/logout', [SesiController::class, 'logout'])->name('logout');

// ── Route Admin & Kasir ─────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin,kasir'])->group(function () {

    // Beranda (dashboard statistik)
    Route::get('/beranda', [BerandaController::class, 'index'])->name('beranda');

    // ── Kapal ───────────────────────────────────────────────────────────
    Route::resource('kapal', KapalController::class);

    // ── Pelabuhan ───────────────────────────────────────────────────────
    Route::resource('pelabuhan', PelabuhanController::class);

    // ── Jadwal Kapal ────────────────────────────────────────────────────
    Route::resource('jadwal', JadwalKapalController::class);

    // ── Penumpang ───────────────────────────────────────────────────────
    Route::resource('penumpang', PenumpangController::class);

    // ── Pemesanan ───────────────────────────────────────────────────────
    Route::resource('pemesanan', PemesananController::class);

    // ── Pembayaran ──────────────────────────────────────────────────────
    // Route::resource('pembayaran', PembayaranController::class);

    // ── Tiket ───────────────────────────────────────────────────────────
    Route::resource('tiket', TiketController::class);
    Route::post('tiket/{tiket}/cetak', [TiketController::class, 'cetak'])->name('tiket.cetak');

});

// ── Route Admin Only ────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->group(function () {

    // ── Manajemen User / Kasir ──────────────────────────────────────────
    Route::resource('users', UserController::class);

});

// ── Route Kasir Only ────────────────────────────────────────────────────
Route::middleware(['auth', 'role:kasir'])->group(function () {

    // ── Sesi Kasir ──────────────────────────────────────────────────────
    // Route::get('sesi/buka', [SesiKasirController::class, 'buka'])->name('sesi.buka');
    // Route::post('sesi/tutup/{sesi}', [SesiKasirController::class, 'tutup'])->name('sesi.tutup');

});
