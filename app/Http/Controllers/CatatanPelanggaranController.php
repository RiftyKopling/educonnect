<?php

namespace App\Http\Controllers;

use App\Models\CatatanPelanggaran;
use App\Models\Kelas;
use App\Models\Pelanggaran;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CatatanPelanggaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = CatatanPelanggaran::with(['siswa', 'pelanggaran', 'guruBk'])
        ->when($request->bulan, fn($q) => $q->whereMonth('tanggal', $request->bulan))
        ->when($request->tahun, fn($q) => $q->whereYear('tanggal', $request->tahun))
        ->when($request->pelanggaran_id, fn($q) => $q->where('pelanggaran_id', $request->pelanggaran_id))
        ->when($request->kelas_id, fn($q) => $q->whereHas('siswa', fn($q2) => $q2->where('kelas_id', $request->kelas_id)))
        ->latest('tanggal');

        if ($user->hasRole('guru-bk')) {
            // Melihat semua
        } elseif ($user->hasRole('wali-kelas')) {
            $kelasId = $user->kelasDiampu->id ?? null;
            $query->whereHas('siswa', function ($q) use ($kelasId) {
                $q->where('kelas_id', $kelasId);
            });
        } elseif ($user->hasRole('orang-tua')) {
            $query->whereHas('siswa', function ($q) use ($user) {
                $q->where('orang_tua_id', $user->id);
            });
        } else {
            abort(403, 'Akses ditolak.');
        }
    
        $catatanPelanggarans = $query->paginate(15)->withQueryString();
        $pelanggaranList = Pelanggaran::orderBy('nama_pelanggaran')->get();
        $kelasList = Kelas::orderBy('nama_kelas')->get();

        return view('catatan_pelanggaran.index', compact('catatanPelanggarans', 'pelanggaranList', 'kelasList'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::user()->hasRole('guru-bk')) {
            abort(403, 'Akses ditolak.');
        }

        $kelasList = Kelas::all();
        $pelanggarans = Pelanggaran::all();
        return view('catatan_pelanggaran.create', compact('kelasList', 'pelanggarans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->hasRole('guru-bk')) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'tanggal' => 'required|date',
            'siswa_nisn' => 'required|exists:siswa,nisn',
            'pelanggaran_id' => 'required|exists:pelanggarans,id',
            'keterangan' => 'nullable|string',
        ],
        [
            'siswa_nisn.required'     => 'Siswa wajib dipilih.',
            'pelanggaran_id.required' => 'Jenis pelanggaran wajib dipilih.',
            'tanggal.required'        => 'Tanggal insiden wajib diisi.',
        ]);

        CatatanPelanggaran::create([
            'tanggal' => $request->tanggal,
            'siswa_nisn' => $request->siswa_nisn,
            'pelanggaran_id' => $request->pelanggaran_id,
            'keterangan' => $request->keterangan,
            'guru_bk_id' => Auth::id(),
        ]);

        return redirect()->route('catatan-pelanggaran.index')->with('success', 'Catatan pelanggaran berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CatatanPelanggaran $catatanPelanggaran)
    {
        $user = Auth::user();
        
        // Authorization Check
        $allowed = false;
        if ($user->hasRole('guru-bk')) {
            $allowed = true;
        } elseif ($user->hasRole('wali-kelas') && $user->kelasDiampu) {
            if ($catatanPelanggaran->siswa->kelas_id == $user->kelasDiampu->id) {
                $allowed = true;
            }
        } elseif ($user->hasRole('orang-tua')) {
            if ($catatanPelanggaran->siswa->orang_tua_id == $user->id) {
                $allowed = true;
            }
        }

        if (!$allowed) {
            abort(403, 'Akses ditolak.');
        }

        return view('catatan_pelanggaran.show', compact('catatanPelanggaran'));
    }

    /**
     * API Endpoint for cascading dropdown
     */
    public function getSiswaByKelas($kelasId)
    {
        $siswa = Siswa::where('kelas_id', $kelasId)->get(['nisn', 'nama_lengkap']);
        return response()->json($siswa);
    }
    
    public function cetak()
    {
        // Simple report generation
        $user = Auth::user();
        $query = CatatanPelanggaran::with(['siswa.kelas', 'pelanggaran', 'guruBk']);

        if ($user->hasRole('guru-bk')) {
            // all
        } elseif ($user->hasRole('wali-kelas')) {
            $kelasId = $user->kelasDiampu->id ?? null;
            $query->whereHas('siswa', function ($q) use ($kelasId) {
                $q->where('kelas_id', $kelasId);
            });
        } elseif ($user->hasRole('orang-tua')) {
            $query->whereHas('siswa', function ($q) use ($user) {
                $q->where('orang_tua_id', $user->id);
            });
        } else {
            abort(403);
        }

        $data = $query->orderBy('tanggal', 'desc')->get();
        return view('catatan_pelanggaran.report', compact('data'));
    }

    public function edit(CatatanPelanggaran $catatanPelanggaran)
    {
        if (!Auth::user()->hasRole('guru-bk')) {
            abort(403, 'Akses ditolak.');
        }

        $kelasList = Kelas::all();
        $pelanggarans = Pelanggaran::all();
        return view('catatan_pelanggaran.edit', compact('catatanPelanggaran', 'kelasList', 'pelanggarans'));
    }

    public function update(Request $request, CatatanPelanggaran $catatanPelanggaran)
    {
        if (!Auth::user()->hasRole('guru-bk')) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'tanggal'        => 'required|date',
            'siswa_nisn'     => 'required|exists:siswa,nisn',
            'pelanggaran_id' => 'required|exists:pelanggarans,id',
            'keterangan'     => 'nullable|string',
        ], [
            'siswa_nisn.required'     => 'Siswa wajib dipilih.',
            'pelanggaran_id.required' => 'Jenis pelanggaran wajib dipilih.',
            'tanggal.required'        => 'Tanggal insiden wajib diisi.',
        ]);

        $catatanPelanggaran->update([
            'tanggal'        => $request->tanggal,
            'siswa_nisn'     => $request->siswa_nisn,
            'pelanggaran_id' => $request->pelanggaran_id,
            'keterangan'     => $request->keterangan,
        ]);

        return redirect()->route('catatan-pelanggaran.index')->with('success', 'Catatan pelanggaran berhasil diperbarui.');
    }

    public function destroy(CatatanPelanggaran $catatanPelanggaran)
    {
        if (!Auth::user()->hasRole('guru-bk')) {
            abort(403, 'Akses ditolak.');
        }

        $catatanPelanggaran->delete();
        return redirect()->route('catatan-pelanggaran.index')->with('success', 'Catatan pelanggaran berhasil dihapus.');
    }
}
