<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController; // <-- PENTING: Import UserController di sini
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MapelController;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
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
    Route::resource('kelas', KelasController::class);
    Route::resource('mapel', MapelController::class);
});
// -----------------------------------------------------------



require __DIR__.'/auth.php';