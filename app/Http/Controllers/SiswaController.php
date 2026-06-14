<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index()
    {
        $siswa = Siswa::with(['kelas', 'orangTua'])->latest()->paginate(10);
        return view('siswa.index', compact('siswa'));
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
        $request->validate([
            'nisn' => 'required|digits:10|unique:siswa,nisn',
            'nama_lengkap' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas_id' => 'required|exists:kelas,id',
            'tanggal_lahir' => 'required|date',
            // Tambahkan validasi ini:
            'orang_tua_id' => 'nullable|exists:users,id',
        ]);

        Siswa::create($request->all());

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
        $request->validate([
            // Validasi pengecualian: Boleh pakai NISN yang sama JIKA itu miliknya sendiri
            'nisn' => 'required|digits:10|unique:siswa,nisn,' . $siswa->nisn . ',nisn',
            'nama_lengkap' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas_id' => 'required|exists:kelas,id',
            'tanggal_lahir' => 'required|date',
            'orang_tua_id' => 'nullable|exists:users,id',
        ]);

        $siswa->update($request->all());

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