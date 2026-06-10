<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController; // <-- PENTING: Import UserController di sini
use App\Http\Controllers\SiswaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- RUTE MANAJEMEN PENGGUNA (Hanya untuk Admin Sekolah) ---
Route::middleware(['auth', 'role:admin-sekolah'])->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('siswa', SiswaController::class); // <-- Tambahkan baris ini
});
// -----------------------------------------------------------



require __DIR__.'/auth.php';