<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Mapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresensiController extends Controller
{
    public function index()
    {
        // 1. Tarik user utuh dari Model beserta relasinya menggunakan ID
        $user = \App\Models\User::with('role')->find(Auth::id());
        
        // 2. Pengaman ekstra jika sesi login terputus atau role kosong
        if (!$user || !$user->role) {
            return redirect()->route('login')->with('error', 'Sesi tidak valid.');
        }

        $role = $user->role->slug;
        
        $query = Presensi::with(['siswa', 'kelas', 'mapel', 'guru'])->latest('tanggal');

        // ... sisa kode if-else Anda di bawahnya tetap sama persis ...

        // Filter berdasarkan peran pengguna
        if ($role == 'guru-mapel') {
            $query->where('guru_id', $user->id);
            
        } elseif ($role == 'wali-kelas') {
            // Menggunakan relasi kelasDiampu sesuai model User.php Anda
            $kelasId = $user->kelasDiampu?->id; 
            $query->where('kelas_id', $kelasId);
            
        } elseif ($role == 'orang-tua') {
            // Ambil semua NISN anak dari user orang tua
            $nisn_anak = Siswa::where('orang_tua_id', $user->id)->pluck('nisn')->toArray();
            $query->whereIn('siswa_nisn', $nisn_anak); // Menggunakan siswa_nisn
            
        } elseif ($role == 'admin-sekolah') {
            abort(403, 'Admin tidak mengelola transaksi presensi harian.');
        }
        // Kepala Sekolah otomatis melewati semua IF ini sehingga melihat seluruh data

        $data_presensi = $query->paginate(20);
        return view('presensi.index', compact('data_presensi', 'role'));
    }

    public function create(Request $request)
    {
        $this->authorizeAccess();
        
        $kelas = Kelas::all();
        $mapel = Mapel::all();
        $siswa = [];

        // Mengambil data siswa jika form kelas sudah dipilih (bisa dikombinasikan via route/GET parameter)
        if ($request->has('kelas_id')) {
            $siswa = Siswa::where('kelas_id', $request->kelas_id)->orderBy('nama_lengkap')->get();
        }

        return view('presensi.create', compact('kelas', 'mapel', 'siswa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kelas_id' => 'required',
            'mapel_id' => 'required',
            'status' => 'required|array',
        ]);

        foreach ($request->status as $nisn => $val) {
            // updateOrCreate akan menimpa data jika di hari, jam, mapel, dan nisn yang sama sudah ada
            Presensi::updateOrCreate(
                [
                    'tanggal' => $request->tanggal,
                    'siswa_nisn' => $nisn, // Menggunakan siswa_nisn
                    'mapel_id' => $request->mapel_id,
                ],
                [
                    'kelas_id' => $request->kelas_id,
                    'guru_id' => Auth::id(),
                    'status' => $val,
                    'catatan' => $request->catatan[$nisn] ?? null,
                ]
            );
        }

        return redirect()->route('presensi.index')->with('success', 'Presensi berhasil disimpan.');
    }

    public function cetakLaporan()
    {
        $user = Auth::user();
        if ($user->role->slug != 'orang-tua') abort(403, 'Hanya Orang Tua yang dapat mencetak laporan ini.');

        // Mengambil seluruh anak, berjaga-jaga jika orang tua punya anak lebih dari 1
        $data_anak = Siswa::where('orang_tua_id', $user->id)->get();
        $nisn_anak = $data_anak->pluck('nisn')->toArray();

        // Mengambil presensi menggunakan siswa_nisn
        $presensi = Presensi::whereIn('siswa_nisn', $nisn_anak)
            ->with(['mapel', 'siswa'])
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('presensi.report', compact('data_anak', 'presensi'));
    }

    public function edit(int $id)
    {
        $presensi = Presensi::with(['siswa', 'mapel'])->findOrFail($id);
        
        // Gunakan tarikan data utuh seperti di fungsi index sebelumnya
        $user = \App\Models\User::with('role')->find(Auth::id());
        $role = $user->role->slug;

        // PENGAMAN 1: Guru Mapel HANYA bisa mengedit presensi yang dia buat sendiri
        if ($role == 'guru-mapel' && $presensi->guru_id != $user->id) {
            abort(403, 'Anda tidak berhak mengubah presensi buatan guru lain.');
        }

        // PENGAMAN 2: Wali Kelas HANYA bisa mengedit presensi di kelas yang dia ampu
        if ($role == 'wali-kelas' && $presensi->kelas_id != $user->kelasDiampu?->id) {
            abort(403, 'Anda hanya berhak mengubah presensi siswa di kelas ampuannya Anda.');
        }

        // Jika Orang Tua atau Admin Sekolah memaksa masuk via URL
        if (!in_array($role, ['guru-mapel', 'wali-kelas'])) {
            abort(403, 'Akses ditolak.');
        }

        return view('presensi.edit', compact('presensi'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'status' => 'required|in:H,S,I,A,D',
            'catatan' => 'nullable|string|max:255'
        ]);

        $presensi = Presensi::findOrFail($id);
        
        // Pengecekan keamanan ulang sebelum menyimpan (mencegah bypass form)
        $user = \App\Models\User::with('role')->find(Auth::id());
        $role = $user->role->slug;

        if ($role == 'guru-mapel' && $presensi->guru_id != $user->id) abort(403);
        if ($role == 'wali-kelas' && $presensi->kelas_id != $user->kelasDiampu?->id) abort(403);

        // Eksekusi Update
        $presensi->update([
            'status' => $request->status,
            'catatan' => $request->catatan
        ]);

        return redirect()->route('presensi.index')->with('success', 'Data presensi berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $presensi = Presensi::findOrFail($id);
        
        $user = \App\Models\User::with('role')->find(Auth::id());
        $role = $user->role->slug;

        // PENGAMAN: Hanya Guru Mapel pembuat aslinya yang boleh menghapus
        if ($role != 'admin-sekolah' && $presensi->guru_id != $user->id) {
            abort(403, 'Anda tidak memiliki otoritas untuk menghapus data presensi ini.');
        }

        $presensi->delete();

        return redirect()->route('presensi.index')->with('success', 'Data presensi berhasil dihapus.');
    }

    private function authorizeAccess() {
        $role = Auth::user()->role->slug;
        if (!in_array($role, ['guru-mapel', 'wali-kelas'])) abort(403, 'Anda tidak memiliki hak akses ke halaman ini.');
    }
}