<?php

namespace App\Http\Controllers;

use App\Models\Mapel;
use App\Models\User;
use Illuminate\Http\Request;

class MapelController extends Controller
{
    public function index()
    {
        $query = Mapel::with('gurus')->latest();

        if (request('search')) {
            $query->where(function($q) {
                $q->where('kode_mapel', 'like', '%' . request('search') . '%')
                ->orWhere('nama_mapel', 'like', '%' . request('search') . '%');
            });
        }

        if (request('tahun_ajaran')) {
            $query->where('tahun_ajaran', request('tahun_ajaran'));
        }

        $data_mapel = $query->paginate(10)->withQueryString();
        return view('mapel.index', compact('data_mapel'));
    }

    public function create()
    {
        $tahunAjaran = $this->generateTahunAjaran();
        return view('mapel.create', compact('tahunAjaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_mapel'   => 'required|string|max:10',
            'nama_mapel'   => 'required|string|max:100',
            'tahun_ajaran' => 'required|string|max:9',
        ]);

        // cek unik per kode + tahun ajaran (beda tahun boleh kode sama)
        $exists = Mapel::where('kode_mapel', strtoupper($request->kode_mapel))
                       ->where('tahun_ajaran', $request->tahun_ajaran)
                       ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'Mata pelajaran dengan kode dan tahun ajaran ini sudah ada.');
        }

        Mapel::create([
            'kode_mapel'   => strtoupper($request->kode_mapel),
            'nama_mapel'   => $request->nama_mapel,
            'tahun_ajaran' => $request->tahun_ajaran,
        ]);

        return redirect()->route('mapel.index')->with('success', 'Mata Pelajaran berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $mapel = Mapel::findOrFail($id);
        $tahunAjaran = $this->generateTahunAjaran();
        return view('mapel.edit', compact('mapel', 'tahunAjaran'));
    }

    public function update(Request $request, int $id)
    {
        $mapel = Mapel::findOrFail($id);

        $request->validate([
            'kode_mapel'   => 'required|string|max:10',
            'nama_mapel'   => 'required|string|max:100',
            'tahun_ajaran' => 'required|string|max:9',
        ]);

        $exists = Mapel::where('kode_mapel', strtoupper($request->kode_mapel))
                       ->where('tahun_ajaran', $request->tahun_ajaran)
                       ->where('id', '!=', $id)
                       ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'Mata pelajaran dengan kode dan tahun ajaran ini sudah ada.');
        }

        $mapel->update([
            'kode_mapel'   => strtoupper($request->kode_mapel),
            'nama_mapel'   => $request->nama_mapel,
            'tahun_ajaran' => $request->tahun_ajaran,
        ]);

        return redirect()->route('mapel.index')->with('success', 'Mata Pelajaran berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $mapel = Mapel::findOrFail($id);
        $mapel->delete();
        return redirect()->route('mapel.index')->with('success', 'Mata Pelajaran berhasil dihapus.');
    }

    public function assignGuru(int $id)
    {
        $mapel = Mapel::findOrFail($id);
        $gurus = User::whereHas('role', function($q) {
            $q->where('slug', 'guru-mapel');
        })->get();
        return view('mapel.assign', compact('mapel', 'gurus'));
    }

    public function storeAssignGuru(Request $request, int $id)
    {
        $mapel = Mapel::findOrFail($id);
        $request->validate([
            'guru_ids'   => 'array',
            'guru_ids.*' => 'exists:users,id',
        ]);
        $mapel->gurus()->sync($request->guru_ids ?? []);
        return redirect()->route('mapel.index')->with('success', 'Penugasan guru berhasil diperbarui.');
    }

    // Helper: generate daftar tahun ajaran
    private function generateTahunAjaran(): array
    {
        $tahun = (int) date('Y');
        $list = [];
        for ($i = -1; $i <= 4; $i++) {
            $list[] = ($tahun + $i) . '/' . ($tahun + $i + 1);
        }
        return $list;
        // contoh hasil: ['2024/2025', '2025/2026', '2026/2027', ...]
    }
}