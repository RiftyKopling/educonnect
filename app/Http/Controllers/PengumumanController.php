<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengumumanController extends Controller {
    
    public function index(Request $request)
    {
        $currentUserId = Auth::id();
        
        if (!$currentUserId) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }
    
        $user = User::with('role')->find($currentUserId);
        
        if (!$user || !$user->role) {
            abort(403, 'Role pengguna tidak ditemukan.');
        }
        
        // Filter berdasarkan role
        if ($user->role->slug == 'kepala-sekolah') {
            $query = Pengumuman::with('user');
        } else {
            $query = Pengumuman::with('user')->where('user_id', $user->id);
        }

        // SEARCH
        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        // FILTER TARGET
        if ($request->filled('target')) {
            $query->where('target_type', $request->target);
        }

        // SORTING
        $sort = $request->input('sort', 'created_at');
        $direction = $request->input('direction', 'desc');

        $allowedSorts = ['created_at', 'judul'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $data_pengumuman = $query->paginate(10)->withQueryString();
        
        return view('pengumuman.index', compact('data_pengumuman'));
    }

    public function create() {
        $currentUserId = Auth::id();
        
        if (!$currentUserId) {
            return redirect()->route('login');
        }

        $user = User::with('role')->find($currentUserId);
        
        if (!$user || !$user->role) {
            abort(403, 'Role pengguna tidak ditemukan.');
        }

        $kelas_diampu = null;

        if ($user->role->slug == 'wali-kelas') {
            $kelas_diampu = Kelas::where('wali_kelas_id', $user->id)->first();
        }

        return view('pengumuman.create', compact('user', 'kelas_diampu'));
    }

    public function store(Request $request) {
        $currentUserId = Auth::id();
        
        if (!$currentUserId) {
            return redirect()->route('login');
        }

        $user = User::with('role')->find($currentUserId);
        
        if (!$user || !$user->role) {
            abort(403, 'Role pengguna tidak ditemukan.');
        }

        $role = $user->role->slug;

        $request->validate([
            'judul' => 'required|max:255',
            'konten' => 'required',
            'target' => 'required'
        ]);

        $target_type = $request->target;
        $kelas_id = null;

        // --- BACKEND SECURITY & AUTO-ASSIGNMENT ---
        if ($role == 'kepala-sekolah') {
            $target_type = 'all';
            
        } elseif ($role == 'wali-kelas') {
            $kelas = Kelas::where('wali_kelas_id', $user->id)->first();
            
            if ($target_type == 'class-parents') {
                if (!$kelas) {
                    return redirect()->back()->withInput()->withErrors(['target' => 'Anda belum terdaftar sebagai wali kelas di kelas mana pun.']);
                }
                $kelas_id = $kelas->id;
            } else {
                $target_type = 'kepala-sekolah';
            }
            
        } elseif (in_array($role, ['guru-mapel', 'guru-bk'])) {
            if ($target_type !== 'kepala-sekolah') {
                $target_type = 'all-parents';
            }
        } else {
            abort(403, 'Peran Anda tidak memiliki otoritas untuk membuat pengumuman.');
        }

        Pengumuman::create([
            'user_id' => $user->id,
            'judul' => $request->judul,
            'konten' => $request->konten,
            'target_type' => $target_type,
            'kelas_id' => $kelas_id,
        ]);

        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil dikirim.');
    }  

    public function edit(int $id)
    {
        $currentUserId = Auth::id();
        
        if (!$currentUserId) {
            return redirect()->route('login');
        }

        $user = User::with('role')->find($currentUserId);
        
        if (!$user || !$user->role) {
            abort(403, 'Role pengguna tidak ditemukan.');
        }

        $pengumuman = Pengumuman::findOrFail($id);
        
        // Cek kepemilikan
        if ($pengumuman->user_id !== $user->id && $user->role->slug !== 'kepala-sekolah') {
            abort(403, 'Anda tidak berwenang mengedit pengumuman ini.');
        }

        $kelas_diampu = null;
        if ($user->role->slug == 'wali-kelas') {
            $kelas_diampu = Kelas::where('wali_kelas_id', $user->id)->first();
        }

        return view('pengumuman.edit', compact('pengumuman', 'user', 'kelas_diampu'));
    }

    public function update(Request $request, int $id)
    {
        $currentUserId = Auth::id();
        
        if (!$currentUserId) {
            return redirect()->route('login');
        }

        $user = User::with('role')->find($currentUserId);
        
        if (!$user || !$user->role) {
            abort(403, 'Role pengguna tidak ditemukan.');
        }

        $pengumuman = Pengumuman::findOrFail($id);
        
        // Cek kepemilikan
        if ($pengumuman->user_id !== $user->id && $user->role->slug !== 'kepala-sekolah') {
            abort(403, 'Anda tidak berwenang mengedit pengumuman ini.');
        }

        $request->validate([
            'judul' => 'required|max:255',
            'konten' => 'required',
            'target' => 'required'
        ]);

        $target_type = $request->target;
        $kelas_id = null;

        // Logic target sama seperti di store
        $role = $user->role->slug;
        
        if ($role == 'kepala-sekolah') {
            $target_type = 'all';
        } elseif ($role == 'wali-kelas') {
            $kelas = Kelas::where('wali_kelas_id', $user->id)->first();
            if ($target_type == 'class-parents') {
                if (!$kelas) {
                    return redirect()->back()->withInput()->withErrors(['target' => 'Anda belum terdaftar sebagai wali kelas.']);
                }
                $kelas_id = $kelas->id;
            } else {
                $target_type = 'kepala-sekolah';
            }
        } elseif (in_array($role, ['guru-mapel', 'guru-bk'])) {
            if ($target_type !== 'kepala-sekolah') {
                $target_type = 'all-parents';
            }
        } else {
            abort(403, 'Peran Anda tidak memiliki otoritas.');
        }

        $pengumuman->update([
            'judul' => $request->judul,
            'konten' => $request->konten,
            'target_type' => $target_type,
            'kelas_id' => $kelas_id,
        ]);

        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(int $id) {
        $pengumuman = Pengumuman::findOrFail($id);
        $currentUserId = Auth::id();

        $user = User::with('role')->find($currentUserId);
        
        if (!$user || !$user->role) {
            abort(403, 'Role pengguna tidak ditemukan.');
        }
        
        // Pengaman ID pembuat atau Kepala Sekolah
        if ($pengumuman->user_id !== $currentUserId && $user->role->slug !== 'kepala-sekolah') {
            abort(403, 'Anda tidak berwenang menghapus pengumuman ini.');
        }
        
        $pengumuman->delete();
        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil dihapus.');
    }

    public function masuk(Request $request) {
        // 1. Samakan sistem keamanannya dengan fungsi lain (Gunakan Auth::id)
        $currentUserId = Auth::id();
        
        if (!$currentUserId) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // 2. Ambil user sekaligus relasi role-nya agar tidak perlu loadMissing lagi
        $user = User::with('role')->find($currentUserId);
        
        if (!$user || !$user->role) {
            abort(403, 'Role pengguna tidak ditemukan.');
        }

        $roleSlug = $user->role->slug;
        $query = Pengumuman::with('user');

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%'); 
        }

        if ($request->filled('target')) {
            $query->where('target_type', $request->target);
        }

        // Menyaring pengumuman sesuai hak akses pembaca
        if ($roleSlug == 'orang-tua') {
            $siswa = \App\Models\Siswa::where('orang_tua_id', $user->id)->first();
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
        } elseif ($roleSlug == 'kepala-sekolah') {
            $query->whereIn('target_type', ['all', 'kepala-sekolah']);
        } else {
            $query->where('target_type', 'all');
        }

        $sort = $request->input('sort', 'created_at');
        $direction = $request->input('direction', 'desc');

        $allowedSorts = ['created_at', 'judul'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Gunakan pagination 10 per halaman
        $data_pengumuman = $query->paginate(10)->withQueryString();

        return view('pengumuman.masuk', compact('data_pengumuman'));
    }
}