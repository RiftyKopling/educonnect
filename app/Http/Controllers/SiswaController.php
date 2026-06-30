<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::with(['kelas', 'orangTua']);

        if ($request->filled('search')) {

            $query->where(function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->search . '%')
                ->orWhere('nisn', 'like', '%' . $request->search . '%');
            });
        }

        // Filter kelas
        if ($request->filled('kelas')) {
            $query->where('kelas_id', $request->kelas);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $sort = $request->input('sort', 'nisn'); 
        $direction = $request->input('direction', 'asc');

        $allowedSorts = ['nisn', 'nama_lengkap', 'kelas_id', 'status'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('nisn', 'asc');
        }
        
        $siswa = $query->paginate(10)->withQueryString();
        $kelas = Kelas::orderBy('nama_kelas')->get();
        return view('siswa.index', compact('siswa', 'kelas'));
    }

    public function create()
    {
        $data_kelas = Kelas::all();
        
        // Perbaikan: Menggunakan 'slug' agar sesuai dengan struktur database kita
        $data_ortu = User::whereHas('role', function($q){
            $q->where('slug', 'orang-tua');
        })->get();
        
        return view('siswa.create', compact('data_kelas', 'data_ortu'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nisn' => 'required|digits:10|unique:siswa,nisn',
            'nama_lengkap' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas_id' => 'required|exists:kelas,id',
            'tanggal_lahir' => 'required|date|before:today',
            'tempat_lahir' => 'required|string|max:50',
            'alamat' => 'nullable|string',
            'orang_tua_id' => 'nullable|exists:users,id',
            'status' => 'nullable|in:aktif,lulus,pindah,keluar',
        ]);

        Siswa::create($validated);

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    /**
     * Menampilkan formulir edit data siswa
     */
    public function edit(Siswa $siswa)
    {
        $data_kelas = Kelas::all();
        
        // Mengambil daftar orang tua seperti di fungsi create
        $data_ortu = User::whereHas('role', function($q){
            $q->where('slug', 'orang-tua');
        })->get();
        
        return view('siswa.edit', compact('siswa', 'data_kelas', 'data_ortu'));
    }

    /**
     * Memproses pembaruan data ke database
     */
    public function update(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            // Validasi pengecualian: Boleh pakai NISN yang sama JIKA itu miliknya sendiri
            'nisn' => 'required|digits:10|unique:siswa,nisn,' . $siswa->nisn . ',nisn',
            'nama_lengkap' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas_id' => 'required|exists:kelas,id',
            'tempat_lahir' => 'required|string|max:50',
            'tanggal_lahir' => 'required|date|before:today',
            'orang_tua_id' => 'nullable|exists:users,id',
            'status' => 'nullable|in:aktif,lulus,pindah,keluar',
        ]);

        $siswa->update($validated);

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    /**
     * Menghapus data siswa dari database
     */
    public function destroy(Siswa $siswa)
    {
        $siswa->delete();
        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil dihapus.');
    }
}