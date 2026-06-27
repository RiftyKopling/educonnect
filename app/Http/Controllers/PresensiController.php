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

        $query = Presensi::with(['siswa', 'kelas', 'mapel', 'guru'])->latest('tanggal');

        // 2. Filter berdasarkan Role
        if ($user->hasRole('guru-mapel')) {
            // Guru mapel hanya melihat riwayat inputannya sendiri
            $query->where('guru_id', $user->id);
        } elseif ($user->hasRole('wali-kelas')) {
            // Wali kelas melihat presensi siswa di kelas yang ia ampu
            $kelasDiampu = $user->kelasDiampu;
            if ($kelasDiampu) {
                $query->where('kelas_id', $kelasDiampu->id);
            } else {
                // Jika belum diassign ke kelas mana pun
                $query->where('kelas_id', 0); // Tidak tampil apa-apa
            }
        } elseif ($user->hasRole('orang-tua')) {
            // Orang tua melihat presensi anaknya
            $anakIds = $user->anak->pluck('nisn');
            $query->whereIn('siswa_nisn', $anakIds);
        }

        $presensis = $query->paginate(20);

        return view('presensi.index', compact('presensis'));
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

        // Variabel untuk menampilkan form bulk input jika mapel & kelas sudah dipilih
        $siswaList = [];
        $selectedMapel = null;
        $selectedKelas = null;
        $selectedTanggal = $request->get('tanggal', date('Y-m-d'));

        if ($request->has('mapel_id') && $request->has('kelas_id')) {
            $selectedMapel = Mapel::findOrFail($request->mapel_id);
            $selectedKelas = Kelas::findOrFail($request->kelas_id);
            
            // Validasi tambahan (Keamanan): Pastikan guru ini benar mengajar mapel tersebut
            if (!$mapels->contains('id', $selectedMapel->id)) {
                abort(403, 'Akses Ditolak: Anda tidak ditugaskan untuk mengajar Mata Pelajaran ini.');
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
            abort(403, 'Akses Ditolak.');
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
            abort(403, 'Anda tidak diizinkan.');
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
    public function edit($id)
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
            if ($presensi->guru_id !== $user->id) abort(403);
        } elseif ($user->hasRole('wali-kelas')) {
            $kelasDiampu = $user->kelasDiampu;
            if (!$kelasDiampu || $presensi->kelas_id !== $kelasDiampu->id) abort(403);
        } else {
            abort(403);
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
