<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengumumanController extends Controller {
    /* Helper: Mendapatkan user dengan role */
    private function getUserWithRole()
    {
        $user = User::with('role')->find(Auth::id());
        
        if (!$user) {
            abort(403, 'User tidak ditemukan.');
        }
        
        if (!$user->role) {
            abort(403, 'Role pengguna tidak ditemukan.');
        }
        
        return $user;
    }

    /** Helper: Cek kepemilikan atau admin/kepsek*/
    private function canManage($pengumuman, $user)
    {
        $allowedRoles = ['admin-sekolah', 'kepala-sekolah'];
        
        // Jika user adalah admin atau kepala sekolah, bisa manage semua
        if (in_array($user->role->slug, $allowedRoles)) {
            return true;
        }
        
        // Jika bukan, cek kepemilikan
        return $pengumuman->user_id === $user->id;
    }

    public function index(Request $request)
    {
        $user = $this->getUserWithRole();
        $roleSlug = $user->role->slug;

        // Query dasar
        $query = Pengumuman::with('user');

        // Filter berdasarkan role (FOKUS ADMIN DULU)
        if ($roleSlug == 'admin-sekolah') {
            // Admin hanya melihat pengumuman sendiri
            $query->where('user_id', $user->id);
        } 
        // TEMPORARY: Untuk role lain, kita kasih default dulu
        else {
            $query->where('user_id', $user->id);
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

    public function create() 
    {
        $user = $this->getUserWithRole();
        $roleSlug = $user->role->slug;

        // Siapkan data target berdasarkan role
        $targets = [];
        $kelas_diampu = null;

        if ($roleSlug == 'admin-sekolah') {
            // Admin hanya bisa kirim ke 'all'
            $targets = [
                'all' => 'Semua User'
            ];
        } 
        // TEMPORARY untuk role lain
        else {
            $targets = [
                'all' => 'Semua User'
            ];
        }

        // Jika wali kelas, ambil kelas yang diampu
        if ($roleSlug == 'wali-kelas') {
            $kelas_diampu = Kelas::where('wali_kelas_id', $user->id)->first();
        }

        return view('pengumuman.create', compact('user', 'kelas_diampu', 'targets'));
    }
    
    public function store(Request $request) 
    {
        $user = $this->getUserWithRole();
        $roleSlug = $user->role->slug;

        // Validasi
        $request->validate([
            'judul' => 'required|max:255',
            'konten' => 'required',
            'target' => 'required',
            'file_lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4000' // 2MB
        ]);

        $target_type = $request->target;
        $kelas_id = null;
        $filePath = null;

        // LOGIKA BERDASARKAN ROLE (FOKUS ADMIN)
        if ($roleSlug == 'admin-sekolah') {
            $target_type = 'all';
        } 
        // TEMPORARY untuk role lain
        else {
            $target_type = 'all';
        }

        // Upload file jika ada
        if ($request->hasFile('file_lampiran')) {
            $file = $request->file('file_lampiran');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('pengumuman/lampiran', $filename, 'public');
        }

        // Simpan pengumuman
        Pengumuman::create([
            'user_id' => $user->id,
            'judul' => $request->judul,
            'konten' => $request->konten,
            'target_type' => $target_type,
            'kelas_id' => $kelas_id,
            'file_lampiran' => $filePath,
        ]);

        return redirect()
            ->route('pengumuman.index')
            ->with('success', 'Pengumuman berhasil dikirim.');
    }

    public function edit(int $id)
    {
        $user = $this->getUserWithRole();
        $pengumuman = Pengumuman::findOrFail($id);

        // Cek apakah user bisa mengelola pengumuman ini
        if (!$this->canManage($pengumuman, $user)) {
            return redirect()
                ->route('pengumuman.index')
                ->with('error_modal', 'Anda tidak memiliki izin untuk mengedit pengumuman ini.');
        }

        $roleSlug = $user->role->slug;
        $kelas_diampu = null;

        // Siapkan target options
        $targets = [];
        if ($roleSlug == 'admin-sekolah') {
            $targets = ['all' => 'Semua User'];
        } else {
            $targets = ['all' => 'Semua User'];
        }

        if ($roleSlug == 'wali-kelas') {
            $kelas_diampu = Kelas::where('wali_kelas_id', $user->id)->first();
        }

        return view('pengumuman.edit', compact('pengumuman', 'user', 'kelas_diampu', 'targets'));
    }

    public function update(Request $request, int $id)
    {
        $user = $this->getUserWithRole();
        $pengumuman = Pengumuman::findOrFail($id);

        if (!$this->canManage($pengumuman, $user)) {
            return redirect()
                ->route('pengumuman.index')
                ->with('error_modal', 'Anda tidak memiliki izin untuk mengedit pengumuman ini.');
        }

        $request->validate([
            'judul' => 'required|max:255',
            'konten' => 'required',
            'target' => 'required',
            'file_lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4000'
        ]);

        $roleSlug = $user->role->slug;
        $target_type = $request->target;
        $kelas_id = null;

        if ($roleSlug == 'admin-sekolah') {
            $target_type = 'all';
        } else {
            $target_type = 'all';
        }

        $data = [
            'judul' => $request->judul,
            'konten' => $request->konten,
            'target_type' => $target_type,
            'kelas_id' => $kelas_id,
        ];

        // Upload file baru jika ada
        if ($request->hasFile('file_lampiran')) {
            // Hapus file lama jika ada
            if ($pengumuman->file_lampiran && Storage::disk('public')->exists($pengumuman->file_lampiran)) {
                Storage::disk('public')->delete($pengumuman->file_lampiran);
            }
            
            $file = $request->file('file_lampiran');
            $filename = time() . '_' . $file->getClientOriginalName();
            $data['file_lampiran'] = $file->storeAs('pengumuman/lampiran', $filename, 'public');
        }

        $pengumuman->update($data);

        return redirect()
            ->route('pengumuman.index')
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(int $id) 
    {
        $user = $this->getUserWithRole();
        $pengumuman = Pengumuman::findOrFail($id);

        // Cek apakah user bisa menghapus
        if (!$this->canManage($pengumuman, $user)) {
            return redirect()
                ->route('pengumuman.index')
                ->with('error_modal', 'Anda tidak memiliki izin untuk menghapus pengumuman ini.');
        }

        $pengumuman->delete();

        return redirect()
            ->route('pengumuman.index')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }

        /**
     * Papan Pengumuman - Melihat pengumuman yang masuk
     */
    public function masuk(Request $request) 
    {
        $user = $this->getUserWithRole();
        $roleSlug = $user->role->slug;
        
        $query = Pengumuman::with('user');

        // LOGIKA UNTUK ADMIN (FOKUS)
        if ($roleSlug == 'admin-sekolah') {
            // Admin melihat: pengumuman sendiri + pengumuman Kepsek dengan target 'all'
            $kepsekIds = User::where('role_id', 4)->pluck('id'); // role_id 4 = kepala-sekolah
            
            $query->where(function($q) use ($user, $kepsekIds) {
                // 1. Pengumuman sendiri
                $q->where('user_id', $user->id);
                
                // 2. Pengumuman Kepala Sekolah dengan target 'all'
                $q->orWhere(function($subQ) use ($kepsekIds) {
                    $subQ->whereIn('user_id', $kepsekIds)
                         ->where('target_type', 'all');
                });
            });
        } 
        // TEMPORARY untuk role lain
        else {
            $query->where('user_id', $user->id);
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

        return view('pengumuman.masuk', compact('data_pengumuman'));
    }
}