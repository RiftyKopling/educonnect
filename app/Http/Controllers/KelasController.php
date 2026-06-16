<?php
namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        $query = Kelas::with('waliKelas');

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama_kelas', 'like', '%' . $request->search . '%')
                ->orWhereHas('waliKelas', function($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                });
            });
        }

        // Filter tingkat
        if ($request->filled('tingkat')) {
            $query->where('tingkat', $request->tingkat);
        }

        // Filter tahun ajaran
        if ($request->filled('tahun_ajaran')) {
            $query->where('tahun_ajaran', $request->tahun_ajaran);
        }

        // Sorting
        $sort = $request->input('sort', 'nama_kelas');
        $direction = $request->input('direction', 'asc');

        $allowedSorts = ['nama_kelas', 'tingkat', 'tahun_ajaran'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('nama_kelas', 'asc');
        }

        $data_kelas = $query->paginate(10)->withQueryString();

        return view('kelas.index', compact('data_kelas'));
    }

    public function create()
    {
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

    public function edit(Kelas $kelas)
    {
        $data_guru = User::whereHas('role', function($q) {
            $q->where('slug', 'wali-kelas');
        })->get();

        return view('kelas.edit', compact('kelas', 'data_guru'));
    }

    public function show(Kelas $kelas)
    {
        $kelas->load(['siswa', 'waliKelas']);
        return view('kelas.show', compact('kelas'));
    }

    public function update(Request $request, Kelas $kelas)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:10|unique:kelas,nama_kelas,' . $kelas->id,
            'tingkat' => 'required|integer|between:7,9',
            'tahun_ajaran' => 'required|string|max:9',
            'wali_kelas_id' => 'nullable|exists:users,id',
        ]);

        $kelas->update($request->all());

        return redirect()->route('kelas.index')->with('success', 'Data kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kelas)
    {
        $kelas->delete();
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dihapus.');
    }
}