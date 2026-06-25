<?php

namespace App\Http\Controllers;

use App\Models\Konseling;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KonselingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        $query = Konseling::with(['siswa', 'guruBk']);

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
            abort(403, 'Akses ditolak.');
        }

        $konselings = $query->orderBy('tanggal', 'desc')->get();

        // Privacy Filter
        if (!$user->hasRole('guru-bk')) {
            foreach ($konselings as $konseling) {
                if ($konseling->jenis_layanan === 'Konseling Pribadi') {
                    $konseling->deskripsi_kasus = '*** Dirahasiakan demi privasi siswa ***';
                    $konseling->tindak_lanjut = '*** Dirahasiakan demi privasi siswa ***';
                }
            }
        }

        return view('konseling.index', compact('konselings'));
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
        return view('konseling.create', compact('kelasList'));
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
            'jenis_layanan' => 'required|string|max:255',
            'topik' => 'required|string|max:255',
            'deskripsi_kasus' => 'nullable|string',
            'tindak_lanjut' => 'nullable|string',
            'status' => 'required|in:Terjadwal,Selesai,Batal',
        ]);

        Konseling::create([
            'tanggal' => $request->tanggal,
            'siswa_nisn' => $request->siswa_nisn,
            'guru_bk_id' => Auth::id(),
            'jenis_layanan' => $request->jenis_layanan,
            'topik' => $request->topik,
            'deskripsi_kasus' => $request->deskripsi_kasus,
            'tindak_lanjut' => $request->tindak_lanjut,
            'status' => $request->status,
        ]);

        return redirect()->route('konseling.index')->with('success', 'Jadwal Konseling berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Konseling $konseling)
    {
        $user = Auth::user();
        
        // Authorization Check
        $allowed = false;
        if ($user->hasRole('guru-bk')) {
            $allowed = true;
        } elseif ($user->hasRole('wali-kelas') && $user->kelasDiampu) {
            if ($konseling->siswa->kelas_id == $user->kelasDiampu->id) {
                $allowed = true;
            }
        } elseif ($user->hasRole('orang-tua')) {
            if ($konseling->siswa->orang_tua_id == $user->id) {
                $allowed = true;
            }
        }

        if (!$allowed) {
            abort(403, 'Akses ditolak.');
        }

        // Privacy filter for show
        if (!$user->hasRole('guru-bk')) {
            if ($konseling->jenis_layanan === 'Konseling Pribadi') {
                $konseling->deskripsi_kasus = '*** Dirahasiakan demi privasi siswa ***';
                $konseling->tindak_lanjut = '*** Dirahasiakan demi privasi siswa ***';
            }
        }

        return view('konseling.show', compact('konseling'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Konseling $konseling)
    {
        if (!Auth::user()->hasRole('guru-bk')) {
            abort(403, 'Akses ditolak.');
        }

        return view('konseling.edit', compact('konseling'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Konseling $konseling)
    {
        if (!Auth::user()->hasRole('guru-bk')) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'tanggal' => 'required|date',
            'jenis_layanan' => 'required|string|max:255',
            'topik' => 'required|string|max:255',
            'deskripsi_kasus' => 'nullable|string',
            'tindak_lanjut' => 'nullable|string',
            'status' => 'required|in:Terjadwal,Selesai,Batal',
        ]);

        $konseling->update($request->only(['tanggal', 'jenis_layanan', 'topik', 'deskripsi_kasus', 'tindak_lanjut', 'status']));

        return redirect()->route('konseling.index')->with('success', 'Konseling berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Konseling $konseling)
    {
        if (!Auth::user()->hasRole('guru-bk')) {
            abort(403, 'Akses ditolak.');
        }

        $konseling->delete();

        return redirect()->route('konseling.index')->with('success', 'Konseling berhasil dihapus.');
    }

    public function cetak()
    {
        $user = Auth::user();
        $query = Konseling::with(['siswa.kelas', 'guruBk']);

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

        // Privacy filter for report
        if (!$user->hasRole('guru-bk')) {
            foreach ($data as $konseling) {
                if ($konseling->jenis_layanan === 'Konseling Pribadi') {
                    $konseling->deskripsi_kasus = '*** Dirahasiakan demi privasi siswa ***';
                    $konseling->tindak_lanjut = '*** Dirahasiakan demi privasi siswa ***';
                }
            }
        }

        return view('konseling.report', compact('data'));
    }
}
