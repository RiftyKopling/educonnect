<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresensiController extends Controller
{
    /**
     * Tampilkan riwayat presensi berdasarkan Role
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // 1. Blokir Admin Sekolah
        if ($user->hasRole('admin-sekolah')) {
            abort(403, 'Admin Sekolah tidak memiliki akses untuk mengelola presensi harian.');
        }

        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        $query = Presensi::with(['kelas', 'mapel', 'guru'])
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun);

        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }
        if ($request->filled('mapel_id')) {
            $query->where('mapel_id', $request->mapel_id);
        }

        $isGrouped = false;

        // 2. Filter berdasarkan Role
        if ($user->hasRole('guru-mapel')) {
            $query->where('guru_id', $user->id);
            $isGrouped = true;
        } elseif ($user->hasRole('wali-kelas')) {
            $kelasDiampu = $user->kelasDiampu;
            if ($kelasDiampu) {
                $query->where('kelas_id', $kelasDiampu->id);
            } else {
                $query->where('kelas_id', 0); 
            }
            $isGrouped = true;
        } elseif ($user->hasRole('orang-tua')) {
            $anakIds = $user->anak->pluck('nisn');
            $query->whereIn('siswa_nisn', $anakIds);
            if ($request->filled('mapel_id')) {
                $query->where('mapel_id', $request->mapel_id);
            }
            $isOrangTua = true;
        }

        if ($isGrouped) {
            $query->select('tanggal', 'mapel_id', 'kelas_id', 'guru_id')
                  ->selectRaw("SUM(CASE WHEN status = 'H' THEN 1 ELSE 0 END) as total_hadir")
                  ->selectRaw("SUM(CASE WHEN status != 'H' THEN 1 ELSE 0 END) as total_absen")
                  ->groupBy('tanggal', 'mapel_id', 'kelas_id', 'guru_id')
                  ->orderBy('tanggal', 'desc');
        } else {
            $query->with('siswa')->orderBy('tanggal', 'desc');
        }

        $presensis = $query->paginate(20)->withQueryString();

        // Hitung total hadir/absen untuk seluruh bulan (bukan hanya halaman saat ini)
        $totalHadirBulan = 0;
        $totalAbsenBulan = 0;
        if (isset($isOrangTua) && $isOrangTua) {
            $anakIds = $user->anak->pluck('nisn');
            $baseCount = \App\Models\Presensi::whereIn('siswa_nisn', $anakIds)
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun);
            if ($request->filled('mapel_id')) {
                $baseCount->where('mapel_id', $request->mapel_id);
            }
            $totalHadirBulan = (clone $baseCount)->where('status', 'H')->count();
            $totalAbsenBulan = (clone $baseCount)->where('status', '!=', 'H')->count();
        }

        $kelasList = [];
        if ($user->hasRole('guru-mapel')) {
            $mapelList = $user->mapels()->orderBy('nama_mapel')->get();
        } elseif ($user->hasRole('orang-tua')) {
            // Hanya tampilkan mapel yang pernah diikuti anak
            $anakIds = $user->anak->pluck('nisn');
            $mapelIds = \App\Models\Presensi::whereIn('siswa_nisn', $anakIds)
                ->distinct()->pluck('mapel_id');
            $mapelList = \App\Models\Mapel::whereIn('id', $mapelIds)->orderBy('nama_mapel')->get();
        } else {
            $mapelList = \App\Models\Mapel::orderBy('nama_mapel')->get();
        }
        $bulanList = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
            '04' => 'April', '05' => 'Mei', '06' => 'Juni',
            '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
            '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];

        return view('presensi.index', compact('presensis', 'isGrouped', 'kelasList', 'mapelList', 'bulanList', 'bulan', 'tahun', 'totalHadirBulan', 'totalAbsenBulan'));
    }

    /**
     * Tampilkan detail sesi presensi (untuk Guru & Wali Kelas)
     */
    public function show($id)
    {
        $user = Auth::user();
        if ($user->hasRole('admin-sekolah') || $user->hasRole('orang-tua')) {
            abort(403);
        }

        // Format $id: "YYYY-MM-DD_mapelId_kelasId"
        $parts = explode('_', $id);
        if (count($parts) !== 3) {
            abort(404);
        }

        $tanggal = $parts[0];
        $mapel_id = $parts[1];
        $kelas_id = $parts[2];

        // Validasi RBAC tambahan
        if ($user->hasRole('guru-mapel')) {
            if (!$user->mapels->contains('id', $mapel_id)) abort(403);
        } elseif ($user->hasRole('wali-kelas')) {
            $kelasDiampu = $user->kelasDiampu;
            if (!$kelasDiampu || $kelasDiampu->id != $kelas_id) abort(403);
        }

        $mapel = \App\Models\Mapel::findOrFail($mapel_id);
        $kelas = \App\Models\Kelas::findOrFail($kelas_id);

        $presensis = Presensi::with('siswa')
            ->where('tanggal', $tanggal)
            ->where('mapel_id', $mapel_id)
            ->where('kelas_id', $kelas_id)
            ->get();

        return view('presensi.show', compact('presensis', 'tanggal', 'mapel', 'kelas'));
    }

    /**
     * Tampilkan halaman selector & bulk input
     */
    public function create(Request $request)
    {
        $user = Auth::user();

        // Hanya Guru Mapel yang bisa input presensi baru
        if (!$user->hasRole('guru-mapel')) {
            abort(403, 'Hanya Guru Mata Pelajaran yang diizinkan melakukan input presensi.');
        }

        // Ambil data untuk dropdown Mapel (HANYA YANG DIAJARKAN GURU TERSEBUT)
        $mapels = $user->mapels;
        $kelasList = Kelas::all();

        if ($request->hasAny(['tanggal', 'mapel_id', 'kelas_id'])) {
            $request->validate([
                'tanggal' => 'required|date',
                'mapel_id' => 'required|exists:mata_pelajaran,id',
                'kelas_id' => 'required|exists:kelas,id',
            ], [
                'tanggal.required' => 'Kolom Tanggal wajib diisi.',
                'mapel_id.required' => 'Kolom Mata Pelajaran wajib diisi.',
                'kelas_id.required' => 'Kolom Kelas wajib diisi.',
            ]);
        }

        // Variabel untuk menampilkan form bulk input jika mapel & kelas sudah dipilih
        $siswaList = [];
        $selectedMapel = null;
        $selectedKelas = null;
        $selectedTanggal = $request->get('tanggal', date('Y-m-d'));

        if ($request->filled('tanggal') &&
        $request->filled('mapel_id') &&
        $request->filled('kelas_id')) {
            $selectedMapel = Mapel::findOrFail($request->mapel_id);
            $selectedKelas = Kelas::findOrFail($request->kelas_id);
            
            // Validasi tambahan (Keamanan): Pastikan guru ini benar mengajar mapel tersebut
            if (!$mapels->contains('id', $selectedMapel->id)) {
                return back()
                    ->withInput()
                    ->with('error', 'Anda tidak ditugaskan mengajar mata pelajaran tersebut.');
            }
            // Mengambil daftar siswa berdasarkan kelas
            $siswaList = Siswa::where('kelas_id', $selectedKelas->id)->get();
            
            // Mengambil data presensi yang mungkin sudah diinput sebelumnya (untuk default radio button)
            $existingPresensi = Presensi::where('tanggal', $selectedTanggal)
                ->where('mapel_id', $selectedMapel->id)
                ->where('kelas_id', $selectedKelas->id)
                ->get()
                ->keyBy('siswa_nisn');

            // Kita attach data existing ke object siswa sementara
            foreach ($siswaList as $siswa) {
                $siswa->current_status = isset($existingPresensi[$siswa->nisn]) ? $existingPresensi[$siswa->nisn]->status : 'H';
                $siswa->current_catatan = isset($existingPresensi[$siswa->nisn]) ? $existingPresensi[$siswa->nisn]->catatan : '';
            }
        }

        return view('presensi.create', compact(
            'mapels', 'kelasList', 'siswaList', 'selectedMapel', 'selectedKelas', 'selectedTanggal'
        ));
    }

    /**
     * Simpan data bulk input (updateOrCreate)
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasRole('guru-mapel')) {
            abort(403, 'Akses Ditolak. Hanya Guru Mata Pelajaran yang dapat mengakses fitur ini.');
        }

        $request->validate([
            'tanggal' => 'required|date',
            'mapel_id' => 'required|exists:mata_pelajaran,id',
            'kelas_id' => 'required|exists:kelas,id',
            'presensi' => 'required|array',
        ]);

        $tanggal = $request->tanggal;
        $mapel_id = $request->mapel_id;
        $kelas_id = $request->kelas_id;

        // Pastikan guru mengajar mapel ini
        if (!$user->mapels->contains('id', $mapel_id)) {
            return redirect()
                ->route('presensi.create')
                ->withInput()
                ->with('error', 'Anda tidak ditugaskan mengajar mata pelajaran tersebut.');
        }

        foreach ($request->presensi as $nisn => $data) {
            // Gunakan updateOrCreate untuk menghindari duplikasi sesuai PRD
            Presensi::updateOrCreate(
                [
                    'tanggal' => $tanggal,
                    'mapel_id' => $mapel_id,
                    'kelas_id' => $kelas_id,
                    'siswa_nisn' => $nisn,
                ],
                [
                    'guru_id' => $user->id,
                    'status' => $data['status'] ?? 'H',
                    'catatan' => $data['catatan'] ?? null,
                ]
            );
        }

        return redirect()->route('presensi.index')->with('success', 'Data presensi berhasil disimpan secara massal.');
    }

    /**
     * Tampilkan form edit individual
     */
    public function edit(int $id)
    {
        $presensi = Presensi::findOrFail($id);
        $user = Auth::user();

        // Pengecekan RBAC Edit
        if ($user->hasRole('guru-mapel')) {
            if ($presensi->guru_id !== $user->id) {
                abort(403, 'Guru Mapel hanya dapat mengubah data presensi yang diinput olehnya sendiri.');
            }
        } elseif ($user->hasRole('wali-kelas')) {
            $kelasDiampu = $user->kelasDiampu;
            if (!$kelasDiampu || $presensi->kelas_id !== $kelasDiampu->id) {
                abort(403, 'Wali Kelas hanya dapat mengubah data presensi siswa yang ada di kelas ampuannya.');
            }
        } else {
            abort(403, 'Role Anda tidak diizinkan mengakses halaman ini.');
        }

        return view('presensi.edit', compact('presensi'));
    }

    /**
     * Update presensi individual
     */
    public function update(Request $request, $id)
    {
        $presensi = Presensi::findOrFail($id);
        $user = Auth::user();

        // Pengecekan ulang RBAC
        if ($user->hasRole('guru-mapel')) {
            if ($presensi->guru_id !== $user->id) abort(403, 'Akses ditolak. Anda hanya dapat mengakses data presensi yang Anda buat sendiri.');
        } elseif ($user->hasRole('wali-kelas')) {
            $kelasDiampu = $user->kelasDiampu;
            if (!$kelasDiampu || $presensi->kelas_id !== $kelasDiampu->id) abort(403, 'Akses ditolak. Anda hanya dapat mengakses data presensi pada kelas yang Anda ampu.');
        } else {
           abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk melihat halaman presensi.');
        }

        $request->validate([
            'status' => 'required|in:H,S,I,A,D',
            'catatan' => 'nullable|string|max:255',
        ]);

        $presensi->update([
            'status' => $request->status,
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('presensi.index')->with('success', 'Data presensi berhasil diperbarui.');
    }

    /**
     * Hapus presensi (Hanya pembuat/guru mapel yang bisa)
     */
    public function destroy($id)
    {
        $presensi = Presensi::findOrFail($id);
        $user = Auth::user();

        if ($user->hasRole('guru-mapel') && $presensi->guru_id === $user->id) {
            $presensi->delete();
            return redirect()->route('presensi.index')->with('success', 'Data presensi berhasil dihapus.');
        }

        // Wali kelas tidak bisa menghapus, hanya bisa edit (sesuai instruksi)
        abort(403, 'Hanya Guru yang membuat presensi ini yang dapat menghapusnya.');
    }

    /**
     * Cetak Laporan (Khusus Orang Tua / Wali Kelas / Admin / Guru)
     */
    public function cetakLaporan(Request $request)
    {
        $user = Auth::user();
        $query = Presensi::with(['siswa', 'kelas', 'mapel', 'guru'])->latest('tanggal');

        // Batasi akses jika orang tua
        if ($user->hasRole('orang-tua')) {
            $anakIds = $user->anak->pluck('nisn');
            $query->whereIn('siswa_nisn', $anakIds);
        } elseif ($user->hasRole('wali-kelas')) {
            if ($user->kelasDiampu) {
                $query->where('kelas_id', $user->kelasDiampu->id);
            }
        }

        $presensis = $query->get();

        // Me-return view cetak polos tanpa layout utama agar mudah di-print
        return view('presensi.cetak', compact('presensis'));
    }
}
