<?php

namespace App\Http\Controllers;

use App\Models\MateriAjar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MateriAjarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        // RBAC: Only Guru Mapel and Admin Sekolah allowed
        if (!$user->hasRole('guru-mapel') && !$user->hasRole('admin-sekolah')) {
            abort(403, 'Akses Ditolak: Halaman ini hanya untuk Guru Mata Pelajaran.');
        }

        $query = MateriAjar::with(['guru', 'mapel', 'kelas'])->latest();

        // Guru Mapel can only see their own materials
        if ($user->hasRole('guru-mapel')) {
            $query->where('guru_id', $user->id);
        }

        $materiAjars = $query->paginate(15);

        return view('materi_ajar.index', compact('materiAjars'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        // Admin cannot create, only view. Only Guru Mapel can create.
        if (!$user->hasRole('guru-mapel')) {
            abort(403, 'Akses Ditolak: Hanya Guru Mata Pelajaran yang dapat mengunggah materi.');
        }

        // Strict filtering: Only fetch Mapel assigned to this Guru, but allow them to pick any Kelas
        $mapels = $user->mapels;
        $kelas = \App\Models\Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();

        if ($mapels->isEmpty()) {
            return redirect()->route('materi-ajar.index')->with('error', 'Anda belum ditugaskan ke Mata Pelajaran mana pun. Silakan hubungi Admin.');
        }

        return view('materi_ajar.create', compact('mapels', 'kelas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasRole('guru-mapel')) {
            abort(403, 'Akses Ditolak.');
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'mapel_id' => 'required|exists:mata_pelajaran,id',
            'kelas_id' => 'required|exists:kelas,id',
            'tipe_materi' => 'required|in:File,Link URL',
            'file_upload' => 'nullable|required_if:tipe_materi,File|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx|max:10240',
            'url_link' => 'nullable|required_if:tipe_materi,Link URL|url',
        ]);

        // Strict authorization check: Ensure the selected mapel belong to the teacher
        if (!$user->mapels->contains('id', $request->mapel_id)) {
            abort(403, 'Anda tidak ditugaskan untuk mata pelajaran ini.');
        }

        $data = $request->only(['judul', 'deskripsi', 'tipe_materi', 'url_link', 'mapel_id', 'kelas_id']);
        $data['guru_id'] = $user->id;

        if ($request->tipe_materi === 'File' && $request->hasFile('file_upload')) {
            $file = $request->file('file_upload');
            // Store file securely in public/materi_ajar directory
            $path = $file->store('materi_ajar', 'public');
            $data['file_path'] = $path;
            $data['url_link'] = null; // Reset link if file
        } else {
            $data['file_path'] = null; // Reset file path if link
        }

        MateriAjar::create($data);

        return redirect()->route('materi-ajar.index')->with('success', 'Materi berhasil diunggah!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MateriAjar $materiAjar)
    {
        $user = Auth::user();

        if (!$user->hasRole('guru-mapel') || $materiAjar->guru_id !== $user->id) {
            abort(403, 'Akses Ditolak: Anda hanya dapat mengubah materi yang Anda unggah sendiri.');
        }

        $mapels = $user->mapels;
        $kelas = \App\Models\Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();

        return view('materi_ajar.edit', compact('materiAjar', 'mapels', 'kelas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MateriAjar $materiAjar)
    {
        $user = Auth::user();

        if (!$user->hasRole('guru-mapel') || $materiAjar->guru_id !== $user->id) {
            abort(403, 'Akses Ditolak.');
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'mapel_id' => 'required|exists:mata_pelajaran,id',
            'kelas_id' => 'required|exists:kelas,id',
            'tipe_materi' => 'required|in:File,Link URL',
            'file_upload' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx|max:10240',
            'url_link' => 'nullable|required_if:tipe_materi,Link URL|url',
        ]);

        if (!$user->mapels->contains('id', $request->mapel_id)) {
            abort(403, 'Anda tidak ditugaskan untuk mata pelajaran ini.');
        }

        $materiAjar->judul = $request->judul;
        $materiAjar->deskripsi = $request->deskripsi;
        $materiAjar->mapel_id = $request->mapel_id;
        $materiAjar->kelas_id = $request->kelas_id;
        $materiAjar->tipe_materi = $request->tipe_materi;

        if ($request->tipe_materi === 'File') {
            $materiAjar->url_link = null;
            if ($request->hasFile('file_upload')) {
                // Delete old file if exists
                if ($materiAjar->file_path && Storage::disk('public')->exists($materiAjar->file_path)) {
                    Storage::disk('public')->delete($materiAjar->file_path);
                }
                $path = $request->file('file_upload')->store('materi_ajar', 'public');
                $materiAjar->file_path = $path;
            }
        } else {
            // If switched to Link
            if ($materiAjar->file_path && Storage::disk('public')->exists($materiAjar->file_path)) {
                Storage::disk('public')->delete($materiAjar->file_path);
            }
            $materiAjar->file_path = null;
            $materiAjar->url_link = $request->url_link;
        }

        $materiAjar->save();

        return redirect()->route('materi-ajar.index')->with('success', 'Materi berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MateriAjar $materiAjar)
    {
        $user = Auth::user();

        // Both Admin and the specific Guru Mapel can delete
        if ($user->hasRole('admin-sekolah') || ($user->hasRole('guru-mapel') && $materiAjar->guru_id === $user->id)) {
            
            // Delete physical file
            if ($materiAjar->file_path && Storage::disk('public')->exists($materiAjar->file_path)) {
                Storage::disk('public')->delete($materiAjar->file_path);
            }

            $materiAjar->delete();
            return redirect()->route('materi-ajar.index')->with('success', 'Materi berhasil dihapus!');
        }

        abort(403, 'Akses Ditolak: Anda tidak memiliki wewenang untuk menghapus materi ini.');
    }
}
