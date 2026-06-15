<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\PengumumanController; // <-- Diperbaiki (App, bukan APP)
use App\Http\Controllers\DashboardController;  // <-- Wajib ditambahkan agar Dashboard jalan
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
    Route::resource('kelas', KelasController::class);
    Route::resource('mapel', MapelController::class);
});

require __DIR__.'/auth.php';