<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\PelanggaranController;
use App\Http\Controllers\CatatanPelanggaranController;
use App\Http\Controllers\KonselingController;
use App\Http\Controllers\MateriAjarController;
use App\Http\Controllers\ProfilKelasController;
use App\Http\Controllers\MonitoringController;
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
    
    // Rute Presensi (diakses oleh Guru Mapel, Wali Kelas, Orang Tua)
    Route::get('presensi/cetak', [PresensiController::class, 'cetakLaporan'])->name('presensi.cetak');
    Route::resource('presensi', PresensiController::class);
    
    // Rute Nilai (diakses oleh Guru Mapel, Wali Kelas, Orang Tua)
    Route::get('nilai/cetak', [NilaiController::class, 'cetakLaporan'])->name('nilai.cetak');
    Route::resource('nilai', NilaiController::class);

    // Manajemen Konseling
    Route::resource('pelanggaran', PelanggaranController::class)->except(['show']);
    
    Route::get('catatan-pelanggaran/cetak', [CatatanPelanggaranController::class, 'cetak'])->name('catatan-pelanggaran.cetak');
    Route::resource('catatan-pelanggaran', CatatanPelanggaranController::class);
    
    Route::get('konseling/cetak', [KonselingController::class, 'cetak'])->name('konseling.cetak');
    Route::resource('konseling', KonselingController::class);

    // Manajemen Materi Ajar
    Route::resource('materi-ajar', MateriAjarController::class);

    // Profil Kelas (Wali Kelas)
    Route::get('profil-kelas', [ProfilKelasController::class, 'index'])->name('profil-kelas.index');

    // Endpoint AJAX untuk Cascading Dropdown Kelas -> Siswa
    Route::get('api/kelas/{kelasId}/siswa', [CatatanPelanggaranController::class, 'getSiswaByKelas'])->name('api.kelas.siswa');
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
    Route::get('mapel/{id}/assign', [MapelController::class, 'assignGuru'])->name('mapel.assign');
    Route::post('mapel/{id}/assign', [MapelController::class, 'storeAssignGuru'])->name('mapel.storeAssign');
    Route::resource('mapel', MapelController::class);
    Route::resource('kelas', KelasController::class)->parameters([
    'kelas' => 'kelas']);
});

// --- RUTE MONITORING (Hanya untuk Kepala Sekolah) ---
Route::middleware(['auth', 'role:kepala-sekolah'])->group(function () {
    Route::get('monitoring/kedisiplinan', [MonitoringController::class, 'kedisiplinan'])->name('monitoring.kedisiplinan');
    Route::get('monitoring/akademik', [MonitoringController::class, 'akademik'])->name('monitoring.akademik');
    Route::get('monitoring/cetak-kedisiplinan', [MonitoringController::class, 'cetakKedisiplinan'])->name('monitoring.cetak-kedisiplinan');
    Route::get('monitoring/cetak-akademik', [MonitoringController::class, 'cetakAkademik'])->name('monitoring.cetak-akademik');
});

require __DIR__.'/auth.php';