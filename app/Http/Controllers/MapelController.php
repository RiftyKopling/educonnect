<?php

namespace App\Http\Controllers;

use App\Models\Mapel;
use App\Models\User;
use Illuminate\Http\Request;

class MapelController extends Controller
{
    public function index()
    {
        $data_mapel = Mapel::latest()->paginate(10);
        return view('mapel.index', compact('data_mapel'));
    }

    public function create()
    {
        return view('mapel.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_mapel' => 'required|string|max:10|unique:mata_pelajaran,kode_mapel',
            'nama_mapel' => 'required|string|max:100',
        ]);

        Mapel::create([
            'kode_mapel' => strtoupper($request->kode_mapel),
            'nama_mapel' => $request->nama_mapel,
        ]);

        return redirect()->route('mapel.index')->with('success', 'Mata Pelajaran berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $mapel = Mapel::findOrFail($id);
        return view('mapel.edit', compact('mapel'));
    }

    public function update(Request $request, $id)
    {
        $mapel = Mapel::findOrFail($id);

        $request->validate([
            'kode_mapel' => 'required|string|max:10|unique:mata_pelajaran,kode_mapel,' . $id,
            'nama_mapel' => 'required|string|max:100',
        ]);

        $mapel->update([
            'kode_mapel' => strtoupper($request->kode_mapel),
            'nama_mapel' => $request->nama_mapel,
        ]);

        return redirect()->route('mapel.index')->with('success', 'Mata Pelajaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $mapel = Mapel::findOrFail($id);
        $mapel->delete();

        return redirect()->route('mapel.index')->with('success', 'Mata Pelajaran berhasil dihapus.');
    }

    public function assignGuru($id)
    {
        $mapel = Mapel::findOrFail($id);
        $gurus = User::whereHas('role', function($q) {
            $q->where('slug', 'guru-mapel');
        })->get();
        
        return view('mapel.assign', compact('mapel', 'gurus'));
    }

    public function storeAssignGuru(Request $request, $id)
    {
        $mapel = Mapel::findOrFail($id);
        
        $request->validate([
            'guru_ids' => 'array',
            'guru_ids.*' => 'exists:users,id',
        ]);

        $mapel->gurus()->sync($request->guru_ids ?? []);

        return redirect()->route('mapel.index')->with('success', 'Penugasan guru pada mata pelajaran berhasil diperbarui.');
    }
}