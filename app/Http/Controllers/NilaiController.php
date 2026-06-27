<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NilaiController extends Controller
{
    /**
     * Tampilkan riwayat nilai berdasarkan Role
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Blokir Admin Sekolah
        if ($user->hasRole('admin-sekolah')) {
            abort(403, 'Admin Sekolah tidak memiliki akses untuk mengelola nilai akademik harian.');
        }

        $query = Nilai::with(['siswa', 'kelas', 'mapel', 'guru'])->latest('updated_at');

        // Filter berdasarkan Role
        if ($user->hasRole('guru-mapel')) {
            $query->where('guru_id', $user->id);
        } elseif ($user->hasRole('wali-kelas')) {
            $kelasDiampu = $user->kelasDiampu;
            if ($kelasDiampu) {
                $query->where('kelas_id', $kelasDiampu->id);
            } else {
                $query->where('kelas_id', 0); // Tidak tampil apa-apa jika belum punya kelas
            }
        } elseif ($user->hasRole('orang-tua')) {
            $anakIds = $user->anak->pluck('nisn');
            $query->whereIn('siswa_nisn', $anakIds);
        }

        if($request->filled('search')){
            $search = $request->search;
            $query->where(function($q) use($search){
                $q->whereHas('siswa',function($s) use($search){
                    $s->where('nama_lengkap','like',"%{$search}%")
                    ->orWhere('nisn','like',"%{$search}%");
                });

                $q->orWhereHas('mapel',function($m) use($search){
                    $m->where('nama_mapel','like',"%{$search}%");
                });
            });
        }

        if($request->filled('tahun_ajaran')){
            $query->where('tahun_ajaran',$request->tahun_ajaran);
        }

        if($request->filled('kelas_id')){
            $query->where('kelas_id',$request->kelas_id);
        }

        $kelasList = Kelas::orderBy('nama_kelas')->get();

        $tahunAjaran = Nilai::select('tahun_ajaran')
            ->distinct()
            ->orderByDesc('tahun_ajaran')
            ->pluck('tahun_ajaran');

        $nilais = $query->paginate(20)->withQueryString();

        return view('nilai.index', compact(
            'nilais',
            'kelasList',
            'tahunAjaran'
        ));
    }

    /**
     * Tampilkan halaman selector & bulk input nilai
     */
    public function create(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasRole('guru-mapel')) {
            abort(403, 'Hanya Guru Mata Pelajaran yang diizinkan melakukan input nilai.');
        }

        // Ambil data untuk dropdown Mapel (HANYA YANG DIAJARKAN GURU TERSEBUT)
        $mapels = $user->mapels;
        $kelasList = Kelas::all();

        $siswaList = [];
        $selectedMapel = null;
        $selectedKelas = null;
        $selectedSemester = $request->get('semester', 'Ganjil');
        $selectedTahun = $request->get('tahun_ajaran', date('Y') . '/' . (date('Y') + 1));

        if ($request->filled('mapel_id') && $request->filled('kelas_id')) {

            $selectedMapel = Mapel::findOrFail($request->mapel_id);
            $selectedKelas = Kelas::findOrFail($request->kelas_id);

            // Pastikan guru memang mengajar mapel tersebut
            if (!$mapels->contains('id', $selectedMapel->id)) {
                abort(403, 'Akses Ditolak: Anda tidak ditugaskan untuk mengajar Mata Pelajaran ini.');
            }

            // Ambil daftar siswa
            $siswaList = Siswa::where('kelas_id', $selectedKelas->id)->get();

            // Nilai yang sudah pernah diinput
            $existingNilai = Nilai::where('semester', $selectedSemester)
                ->where('tahun_ajaran', $selectedTahun)
                ->where('mapel_id', $selectedMapel->id)
                ->where('kelas_id', $selectedKelas->id)
                ->get()
                ->keyBy('siswa_nisn');

            foreach ($siswaList as $siswa) {
                $siswa->current_tugas = $existingNilai[$siswa->nisn]->tugas ?? 0;
                $siswa->current_kuis = $existingNilai[$siswa->nisn]->kuis ?? 0;
                $siswa->current_uts = $existingNilai[$siswa->nisn]->uts ?? 0;
                $siswa->current_uas = $existingNilai[$siswa->nisn]->uas ?? 0;
                $siswa->current_catatan = $existingNilai[$siswa->nisn]->catatan ?? '';
            }

        } elseif (
            $request->filled('tahun_ajaran') ||
            $request->filled('semester')
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'mapel_id' => 'Silakan pilih Mata Pelajaran dan Kelas terlebih dahulu.'
                ]);
        }

        return view('nilai.create', compact(
            'mapels', 'kelasList', 'siswaList', 'selectedMapel', 'selectedKelas', 'selectedSemester', 'selectedTahun'
        ));
    }

    /**
     * Simpan data bulk input (updateOrCreate horizontal)
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasRole('guru-mapel')) {
            abort(403, 'Akses Ditolak.');
        }

        $request->validate(
        [
            'semester' => 'required|in:Ganjil,Genap',
            'tahun_ajaran' => 'required|string',
            'mapel_id' => 'required|exists:mata_pelajaran,id',
            'kelas_id' => 'required|exists:kelas,id',

            'nilai' => 'required|array',

            'nilai.*.tugas' => 'nullable|integer|min:0|max:100',
            'nilai.*.kuis' => 'nullable|integer|min:0|max:100',
            'nilai.*.uts' => 'nullable|integer|min:0|max:100',
            'nilai.*.uas' => 'nullable|integer|min:0|max:100',
            'nilai.*.catatan' => 'nullable|string|max:255',
        ],
            [
            'semester.required' => 'Semester wajib dipilih.',
            'semester.in' => 'Semester harus Ganjil atau Genap.',
            'tahun_ajaran.required' => 'Tahun ajaran wajib diisi.',
            'mapel_id.required' => 'Mata pelajaran wajib dipilih.',
            'mapel_id.exists' => 'Mata pelajaran yang dipilih tidak valid.',
            'kelas_id.required' => 'Kelas wajib dipilih.',
            'kelas_id.exists' => 'Kelas yang dipilih tidak valid.',
            'nilai.required' => 'Data nilai wajib diisi.',
            'nilai.array' => 'Format data nilai tidak valid.',
            'nilai.*.tugas.integer' => 'Nilai tugas harus berupa angka.',
            'nilai.*.tugas.min' => 'Nilai tugas tidak boleh kurang dari 0.',
            'nilai.*.tugas.max' => 'Nilai tugas tidak boleh lebih dari 100.',
            'nilai.*.kuis.integer' => 'Nilai kuis harus berupa angka.',
            'nilai.*.kuis.min' => 'Nilai kuis tidak boleh kurang dari 0.',
            'nilai.*.kuis.max' => 'Nilai kuis tidak boleh lebih dari 100.',
            'nilai.*.uts.integer' => 'Nilai UTS harus berupa angka.',
            'nilai.*.uts.min' => 'Nilai UTS tidak boleh kurang dari 0.',
            'nilai.*.uts.max' => 'Nilai UTS tidak boleh lebih dari 100.',
            'nilai.*.uas.integer' => 'Nilai UAS harus berupa angka.',
            'nilai.*.uas.min' => 'Nilai UAS tidak boleh kurang dari 0.',
            'nilai.*.uas.max' => 'Nilai UAS tidak boleh lebih dari 100.',
            'nilai.*.catatan.max' => 'Catatan tidak boleh lebih dari 255 karakter.',
        ]);

        $semester = $request->semester;
        $tahun_ajaran = $request->tahun_ajaran;
        $mapel_id = $request->mapel_id;
        $kelas_id = $request->kelas_id;

        if (!$user->mapels->contains('id', $mapel_id)) {
            abort(403, 'Anda tidak diizinkan.');
        }

        foreach ($request->nilai as $nisn => $data) {
            Nilai::updateOrCreate(
                [
                    'semester' => $semester,
                    'tahun_ajaran' => $tahun_ajaran,
                    'mapel_id' => $mapel_id,
                    'kelas_id' => $kelas_id,
                    'siswa_nisn' => $nisn,
                ],
                [
                    'guru_id' => $user->id,
                    'tugas' => $data['tugas'] ?? 0,
                    'kuis' => $data['kuis'] ?? 0,
                    'uts' => $data['uts'] ?? 0,
                    'uas' => $data['uas'] ?? 0,
                    'catatan' => $data['catatan'] ?? null,
                ]
            );
        }

        return redirect()->route('nilai.index')->with('success', 'Data rekapitulasi nilai berhasil disimpan secara massal.');
    }

    /**
     * Tampilkan form edit individual
     */
    public function edit($id)
    {
        $nilai = Nilai::findOrFail($id);
        $user = Auth::user();

        // RBAC Edit
        if ($user->hasRole('guru-mapel')) {
            if ($nilai->guru_id !== $user->id) {
                abort(403, 'Guru Mapel hanya dapat mengubah data nilai yang diinput olehnya sendiri.');
            }
        } elseif ($user->hasRole('wali-kelas')) {
            $kelasDiampu = $user->kelasDiampu;
            if (!$kelasDiampu || $nilai->kelas_id !== $kelasDiampu->id) {
                abort(403, 'Wali Kelas hanya dapat mengubah data nilai siswa yang ada di kelas ampuannya.');
            }
        } else {
            abort(403, 'Role Anda tidak diizinkan mengakses halaman ini.');
        }

        return view('nilai.edit', compact('nilai'));
    }

    /**
     * Update nilai individual
     */
    public function update(Request $request, $id)
    {
        $nilai = Nilai::findOrFail($id);
        $user = Auth::user();

        if ($user->hasRole('guru-mapel')) {
            if ($nilai->guru_id !== $user->id) abort(403);
        } elseif ($user->hasRole('wali-kelas')) {
            $kelasDiampu = $user->kelasDiampu;
            if (!$kelasDiampu || $nilai->kelas_id !== $kelasDiampu->id) abort(403);
        } else {
            abort(403);
        }

        $request->validate([
            'tugas' => 'required|integer|min:0|max:100',
            'kuis' => 'required|integer|min:0|max:100',
            'uts' => 'required|integer|min:0|max:100',
            'uas' => 'required|integer|min:0|max:100',
            'catatan' => 'nullable|string|max:255',
        ],
        [
            'tugas.required' => 'Nilai tugas wajib diisi.',
            'tugas.integer' => 'Nilai tugas harus berupa angka.',
            'tugas.min' => 'Nilai tugas tidak boleh kurang dari 0.',
            'tugas.max' => 'Nilai tugas tidak boleh lebih dari 100.',
            'kuis.required' => 'Nilai kuis wajib diisi.',
            'kuis.integer' => 'Nilai kuis harus berupa angka.',
            'kuis.min' => 'Nilai kuis tidak boleh kurang dari 0.',
            'kuis.max' => 'Nilai kuis tidak boleh lebih dari 100.',
            'uts.required' => 'Nilai UTS wajib diisi.',
            'uts.integer' => 'Nilai UTS harus berupa angka.',
            'uts.min' => 'Nilai UTS tidak boleh kurang dari 0.',
            'uts.max' => 'Nilai UTS tidak boleh lebih dari 100.',
            'uas.required' => 'Nilai UAS wajib diisi.',
            'uas.integer' => 'Nilai UAS harus berupa angka.',
            'uas.min' => 'Nilai UAS tidak boleh kurang dari 0.',
            'uas.max' => 'Nilai UAS tidak boleh lebih dari 100.',
            'catatan.max' => 'Catatan tidak boleh lebih dari 255 karakter.',
        ]);

        $nilai->update([
            'tugas' => $request->tugas,
            'kuis' => $request->kuis,
            'uts' => $request->uts,
            'uas' => $request->uas,
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('nilai.index')->with('success', 'Data nilai berhasil diperbarui.');
    }

    /**
     * Menghapus nilai (Guru Mapel saja)
     */
    public function destroy($id)
    {
        $nilai = Nilai::findOrFail($id);
        $user = Auth::user();

        if ($user->hasRole('guru-mapel') && $nilai->guru_id === $user->id) {
            $nilai->delete();
            return redirect()->route('nilai.index')->with('success', 'Data nilai berhasil dihapus.');
        }

        abort(403, 'Hanya Guru yang membuat rekaman nilai ini yang dapat menghapusnya.');
    }

    /**
     * Cetak Laporan
     */
    public function cetakLaporan(Request $request)
    {
        $user = Auth::user();
        $query = Nilai::with(['siswa', 'kelas', 'mapel', 'guru'])->latest('updated_at');

        if ($user->hasRole('orang-tua')) {
            $anakIds = $user->anak->pluck('nisn');
            $query->whereIn('siswa_nisn', $anakIds);
        } elseif ($user->hasRole('wali-kelas')) {
            if ($user->kelasDiampu) {
                $query->where('kelas_id', $user->kelasDiampu->id);
            }
        }

        $nilais = $query->get();
        return view('nilai.cetak', compact('nilais'));
    }
}
