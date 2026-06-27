<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Pengumuman;
use App\Models\Siswa;
use App\Models\Kelas;

class DashboardController extends Controller
{
    public function dashboard() {
        // 1. Ambil data user login secara eksplisit tanpa fungsi id()
        $currentUser = Auth::user();
        
        // Pengaman pertama jika sesi tiba-tiba terputus
        if (!$currentUser) {
            return redirect()->route('login');
        }

        // Ambil data user dari database yang sudah di-join dengan tabel roles
        $user = User::with('role')->find($currentUser->id); // Menggunakan ->id (tanpa kurung)
        
        // Pengaman kedua jika role belum disetting oleh Admin
        if (!$user || !$user->role) {
            abort(403, 'Akun Anda belum memiliki hak akses (Role). Hubungi Admin.');
        }

        $roleSlug = $user->role->slug;
        
        // Mulai kueri dasar untuk mengambil pengumuman terbaru
        $query = Pengumuman::with('user')->latest();

        // 2. Filter Logika Pengumuman Berdasarkan Role
        if ($roleSlug == 'orang-tua') {
            $siswa = Siswa::where('orang_tua_id', $user->id)->first();
            $kelas_id = $siswa?->kelas_id;

            $query->where(function($q) use ($kelas_id) {
                $q->whereIn('target_type', ['all', 'all-parents'])
                ->orWhere(function($subQ) use ($kelas_id) {
                    if ($kelas_id) {
                        $subQ->where('target_type', 'class-parents')
                            ->where('kelas_id', $kelas_id);
                    } else {
                        $subQ->where('id', 0);
                    }
                });
            });

        } else {
            // Semua role lain: tampilkan pengumuman target 'all' ATAU target role mereka
            $query->where(function($q) use ($roleSlug) {
                $q->where('target_type', 'all')
                ->orWhere('target_type', $roleSlug);
            });
        }

        // Batasi hanya mengambil 5 pengumuman teratas
        $announcements = $query->take(5)->get();

        // 3. Melempar data ke View Dashboard Universal
        return view('dashboard', compact('announcements', 'user'));
    }
}