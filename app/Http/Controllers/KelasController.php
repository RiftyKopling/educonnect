<?php
namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        // Eager loading waliKelas untuk efisiensi query
        $data_kelas = Kelas::with('waliKelas')->latest()->paginate(10);
        return view('kelas.index', compact('data_kelas'));
    }

    public function create()
    {
        // Ambil guru yang memiliki role 'wali-kelas'
        $data_guru = User::whereHas('role', function($q) {
            $q->where('slug', 'wali-kelas');
        })->get();

        return view('kelas.create', compact('data_guru'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:10|unique:kelas,nama_kelas',
            'tingkat' => 'required|integer|between:7,9',
            'tahun_ajaran' => 'required|string|max:9',
            'wali_kelas_id' => 'nullable|exists:users,id',
        ]);

        Kelas::create($request->all());

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dibuat.');
    }

    public function edit(Kelas $kela) // Laravel resource routing default parameter adalah singular 'kela' dari 'kelas'
    {
        $kelas = $kela;
        $data_guru = User::whereHas('role', function($q) {
            $q->where('slug', 'wali-kelas');
        })->get();

        return view('kelas.edit', compact('kelas', 'data_guru'));
    }

    public function update(Request $request, Kelas $kela)
    {
        $kelas = $kela;
        $request->validate([
            'nama_kelas' => 'required|string|max:10|unique:kelas,nama_kelas,'.$kelas->id,
            'tingkat' => 'required|integer|between:7,9',
            'tahun_ajaran' => 'required|string|max:9',
            'wali_kelas_id' => 'nullable|exists:users,id',
        ]);

        $kelas->update($request->all());

        return redirect()->route('kelas.index')->with('success', 'Data kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kela)
    {
        $kela->delete();
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dihapus.');
    }
}