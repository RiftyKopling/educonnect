<?php

namespace App\Http\Controllers;

use App\Models\Pelanggaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelanggaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!Auth::user()->hasRole('admin-sekolah')) {
            abort(403, 'Akses ditolak.');
        }

        $pelanggarans = Pelanggaran::query()
            ->when($request->search, fn($q) => $q->where('nama_pelanggaran', 'like', '%' . $request->search . '%'))
            ->when($request->kategori, fn($q) => $q->where('kategori', $request->kategori))
            ->paginate(10)
            ->withQueryString();

        return view('pelanggaran.index', compact('pelanggarans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::user()->hasRole('admin-sekolah')) {
            abort(403, 'Akses ditolak.');
        }

        return view('pelanggaran.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->hasRole('admin-sekolah')) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'nama_pelanggaran' => 'required|string|max:255',
            'kategori' => 'required|in:Ringan,Sedang,Berat',
            'deskripsi' => 'nullable|string',
        ]);

        Pelanggaran::create($request->all());

        return redirect()->route('pelanggaran.index')->with('success', 'Data pelanggaran berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pelanggaran $pelanggaran)
    {
        if (!Auth::user()->hasRole('admin-sekolah')) {
            abort(403, 'Akses ditolak.');
        }

        return view('pelanggaran.edit', compact('pelanggaran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pelanggaran $pelanggaran)
    {
        if (!Auth::user()->hasRole('admin-sekolah')) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'nama_pelanggaran' => 'required|string|max:255',
            'kategori' => 'required|in:Ringan,Sedang,Berat',
            'deskripsi' => 'nullable|string',
        ]);

        $pelanggaran->update($request->all());

        return redirect()->route('pelanggaran.index')->with('success', 'Data pelanggaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pelanggaran $pelanggaran)
    {
        if (!Auth::user()->hasRole('admin-sekolah')) {
            abort(403, 'Akses ditolak.');
        }

        $pelanggaran->delete();

        return redirect()->route('pelanggaran.index')->with('success', 'Data pelanggaran berhasil dihapus.');
    }
}
