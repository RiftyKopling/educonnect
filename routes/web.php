<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\PengumumanController; // <-- Diperbaiki (App, bukan APP)
use App\Http\Controllers\DashboardController;  // <-- Wajib ditambahkan agar Dashboard jalan
use App\Http\Controllers\PresensiController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// --- Rute Redirect Awal ---
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// --- RUTE UMUM (Untuk Semua yang Sudah Login) ---
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Rute Dashboard dialihkan sepenuhnya ke DashboardController
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    
    // Rute Kotak Masuk Pengumuman (Harus di atas resource)
    Route::get('/pengumuman/masuk', [PengumumanController::class, 'masuk'])->name('pengumuman.masuk');
    
    // Rute Pengumuman diletakkan di sini agar Guru dan Kepala Sekolah bisa mengaksesnya
    Route::resource('pengumuman', PengumumanController::class);

    // --- MODUL PRESENSI ---
    // 1. Rute Laporan (Harus diletakkan di atas rute {id} agar tidak bertabrakan)
    Route::get('/presensi/laporan', [PresensiController::class, 'cetakLaporan'])->name('presensi.report');
    
    // 2. Rute Utama (CRUD)
    Route::get('/presensi', [PresensiController::class, 'index'])->name('presensi.index');
    Route::get('/presensi/create', [PresensiController::class, 'create'])->name('presensi.create');
    Route::post('/presensi', [PresensiController::class, 'store'])->name('presensi.store');
    
    // 3. Rute Edit & Update & Delete
    Route::get('/presensi/{id}/edit', [PresensiController::class, 'edit'])->name('presensi.edit');
    Route::put('/presensi/{id}', [PresensiController::class, 'update'])->name('presensi.update');
    Route::delete('/presensi/{id}', [PresensiController::class, 'destroy'])->name('presensi.destroy');
});

// --- Rute Profil Bawaan Laravel ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- RUTE MASTER DATA (Hanya untuk Admin Sekolah) ---
Route::middleware(['auth', 'role:admin-sekolah'])->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('siswa', SiswaController::class);
    Route::resource('mapel', MapelController::class);
    Route::resource('kelas', KelasController::class)->parameters([
    'kelas' => 'kelas']);
});

require __DIR__.'/auth.php';