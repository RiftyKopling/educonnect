<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Presensi;
use App\Models\CatatanPelanggaran;
use App\Models\Pelanggaran;
use App\Models\Nilai;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Mapel;
use Carbon\Carbon;

class MonitoringController extends Controller
{
    /**
     * Pastikan hanya Kepala Sekolah yang bisa mengakses
     */
    private function authorize()
    {
        $user = Auth::user();
        if (!$user || !$user->hasRole('kepala-sekolah')) {
            abort(403, 'Hanya Kepala Sekolah yang dapat mengakses halaman Monitoring.');
        }
        return $user;
    }

    /**
     * Halaman Monitoring Kedisiplinan
     * - Presensi harian & tren 30 hari
     * - Rekapitulasi pelanggaran
     */
    public function kedisiplinan()
    {
        $this->authorize();

        $today = Carbon::today()->toDateString();

        // === STATISTIK PRESENSI HARI INI ===
        $presensiHariIni = Presensi::where('tanggal', $today)->get();
        $totalPresensiHariIni = $presensiHariIni->count();

        $presensiStats = [
            'hadir'  => $presensiHariIni->where('status', 'H')->count(),
            'sakit'  => $presensiHariIni->where('status', 'S')->count(),
            'izin'   => $presensiHariIni->where('status', 'I')->count(),
            'alpa'   => $presensiHariIni->where('status', 'A')->count(),
            'dispen' => $presensiHariIni->where('status', 'D')->count(),
        ];

        $persenHadir = $totalPresensiHariIni > 0
            ? round(($presensiStats['hadir'] / $totalPresensiHariIni) * 100, 1)
            : 0;

        // === TREN KEHADIRAN 30 HARI TERAKHIR ===
        $tren30Hari = Presensi::select(
                'tanggal',
                DB::raw("SUM(CASE WHEN status = 'H' THEN 1 ELSE 0 END) as hadir"),
                DB::raw("COUNT(*) as total")
            )
            ->where('tanggal', '>=', Carbon::today()->subDays(30)->toDateString())
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get()
            ->map(function ($item) {
                return [
                    'tanggal' => Carbon::parse($item->tanggal)->format('d/m'),
                    'persen' => $item->total > 0 ? round(($item->hadir / $item->total) * 100, 1) : 0,
                ];
            });

        // === TOP 5 KELAS TERAJIN (KEHADIRAN TERTINGGI BULAN INI) ===
        $bulanIni = Carbon::now()->startOfMonth()->toDateString();
        $kelasKehadiran = Presensi::select(
                'kelas_id',
                DB::raw("SUM(CASE WHEN status = 'H' THEN 1 ELSE 0 END) as hadir"),
                DB::raw("COUNT(*) as total")
            )
            ->where('tanggal', '>=', $bulanIni)
            ->groupBy('kelas_id')
            ->get()
            ->map(function ($item) {
                $kelas = Kelas::find($item->kelas_id);
                return [
                    'nama_kelas' => $kelas ? $kelas->nama_kelas : '-',
                    'persen' => $item->total > 0 ? round(($item->hadir / $item->total) * 100, 1) : 0,
                ];
            })
            ->sortByDesc('persen');

        $kelasTerajin = $kelasKehadiran->take(5)->values();
        $kelasTerburuk = $kelasKehadiran->sortBy('persen')->take(5)->values();

        // === REKAPITULASI PELANGGARAN BULAN INI ===
        $pelanggaranBulanIni = CatatanPelanggaran::where('tanggal', '>=', $bulanIni)->count();

        $pelanggaranPerKategori = CatatanPelanggaran::join('pelanggarans', 'catatan_pelanggarans.pelanggaran_id', '=', 'pelanggarans.id')
            ->where('catatan_pelanggarans.tanggal', '>=', $bulanIni)
            ->select('pelanggarans.kategori', DB::raw('COUNT(*) as total'))
            ->groupBy('pelanggarans.kategori')
            ->pluck('total', 'kategori');

        // === TOP 5 JENIS PELANGGARAN TERBANYAK ===
        $topPelanggaran = CatatanPelanggaran::join('pelanggarans', 'catatan_pelanggarans.pelanggaran_id', '=', 'pelanggarans.id')
            ->where('catatan_pelanggarans.tanggal', '>=', $bulanIni)
            ->select('pelanggarans.nama_pelanggaran', 'pelanggarans.kategori', DB::raw('COUNT(*) as total'))
            ->groupBy('pelanggarans.nama_pelanggaran', 'pelanggarans.kategori')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        // === STATISTIK UMUM ===
        $totalSiswa = Siswa::where('status', 'Aktif')->count();
        $totalKelas = Kelas::count();

        return view('monitoring.kedisiplinan', compact(
            'presensiStats',
            'totalPresensiHariIni',
            'persenHadir',
            'tren30Hari',
            'kelasTerajin',
            'kelasTerburuk',
            'pelanggaranBulanIni',
            'pelanggaranPerKategori',
            'topPelanggaran',
            'totalSiswa',
            'totalKelas'
        ));
    }

    /**
     * Halaman Monitoring Akademik
     * - Rata-rata nilai per tingkat kelas
     * - Pemetaan kinerja kelas
     */
    public function akademik(Request $request)
    {
        $this->authorize();

        // Filter tahun ajaran & semester
        $tahunAjaran = $request->get('tahun_ajaran', date('Y') . '/' . (date('Y') + 1));
        $semester = $request->get('semester', 'Ganjil');

        // === DAFTAR TAHUN AJARAN YANG TERSEDIA ===
        $daftarTahunAjaran = Nilai::select('tahun_ajaran')
            ->distinct()
            ->orderByDesc('tahun_ajaran')
            ->pluck('tahun_ajaran');

        // === RATA-RATA NILAI PER TINGKAT KELAS ===
        $rataPerTingkat = Nilai::join('kelas', 'nilai.kelas_id', '=', 'kelas.id')
            ->where('nilai.tahun_ajaran', $tahunAjaran)
            ->where('nilai.semester', $semester)
            ->select(
                'kelas.tingkat',
                DB::raw('ROUND(AVG(nilai.tugas), 1) as avg_tugas'),
                DB::raw('ROUND(AVG(nilai.kuis), 1) as avg_kuis'),
                DB::raw('ROUND(AVG(nilai.uts), 1) as avg_uts'),
                DB::raw('ROUND(AVG(nilai.uas), 1) as avg_uas'),
                DB::raw('ROUND(AVG((nilai.tugas + nilai.kuis + nilai.uts + nilai.uas) / 4), 1) as avg_total')
            )
            ->groupBy('kelas.tingkat')
            ->orderBy('kelas.tingkat')
            ->get();

        // === PEMETAAN KINERJA PER KELAS ===
        $kinerjaPerkelas = Nilai::join('kelas', 'nilai.kelas_id', '=', 'kelas.id')
            ->where('nilai.tahun_ajaran', $tahunAjaran)
            ->where('nilai.semester', $semester)
            ->select(
                'kelas.id as kelas_id',
                'kelas.nama_kelas',
                'kelas.tingkat',
                DB::raw('ROUND(AVG((nilai.tugas + nilai.kuis + nilai.uts + nilai.uas) / 4), 1) as avg_total'),
                DB::raw('COUNT(DISTINCT nilai.siswa_nisn) as jumlah_siswa')
            )
            ->groupBy('kelas.id', 'kelas.nama_kelas', 'kelas.tingkat')
            ->orderByDesc('avg_total')
            ->get();

        $kelasTop = $kinerjaPerkelas->take(5);
        $kelasBawah = $kinerjaPerkelas->sortBy('avg_total')->take(5);

        // === RATA-RATA NILAI PER MATA PELAJARAN ===
        $rataPerMapel = Nilai::join('mata_pelajaran', 'nilai.mapel_id', '=', 'mata_pelajaran.id')
            ->where('nilai.tahun_ajaran', $tahunAjaran)
            ->where('nilai.semester', $semester)
            ->select(
                'mata_pelajaran.nama_mapel',
                DB::raw('ROUND(AVG((nilai.tugas + nilai.kuis + nilai.uts + nilai.uas) / 4), 1) as avg_total')
            )
            ->groupBy('mata_pelajaran.nama_mapel')
            ->orderByDesc('avg_total')
            ->get();

        // === STATUS INPUT NILAI (KELENGKAPAN) ===
        $totalKelas = Kelas::count();
        $totalMapel = Mapel::count();
        $expectedCombinations = $totalKelas * $totalMapel;
        $filledCombinations = Nilai::where('tahun_ajaran', $tahunAjaran)
            ->where('semester', $semester)
            ->select('kelas_id', 'mapel_id')
            ->distinct()
            ->get()
            ->count();
        $persenKelengkapan = $expectedCombinations > 0
            ? round(($filledCombinations / $expectedCombinations) * 100, 1)
            : 0;

        return view('monitoring.akademik', compact(
            'tahunAjaran',
            'semester',
            'daftarTahunAjaran',
            'rataPerTingkat',
            'kinerjaPerkelas',
            'kelasTop',
            'kelasBawah',
            'rataPerMapel',
            'persenKelengkapan',
            'filledCombinations',
            'expectedCombinations'
        ));
    }

    /**
     * Cetak Laporan Kedisiplinan (PDF-friendly view)
     */
    public function cetakKedisiplinan()
    {
        $this->authorize();

        $bulanIni = Carbon::now()->startOfMonth()->toDateString();
        $namaBulan = Carbon::now()->translatedFormat('F Y');

        // Presensi bulan ini per kelas
        $presensiPerKelas = Presensi::join('kelas', 'presensi.kelas_id', '=', 'kelas.id')
            ->where('presensi.tanggal', '>=', $bulanIni)
            ->select(
                'kelas.nama_kelas',
                'kelas.tingkat',
                DB::raw("SUM(CASE WHEN presensi.status = 'H' THEN 1 ELSE 0 END) as hadir"),
                DB::raw("SUM(CASE WHEN presensi.status = 'S' THEN 1 ELSE 0 END) as sakit"),
                DB::raw("SUM(CASE WHEN presensi.status = 'I' THEN 1 ELSE 0 END) as izin"),
                DB::raw("SUM(CASE WHEN presensi.status = 'A' THEN 1 ELSE 0 END) as alpa"),
                DB::raw("SUM(CASE WHEN presensi.status = 'D' THEN 1 ELSE 0 END) as dispen"),
                DB::raw("COUNT(*) as total")
            )
            ->groupBy('kelas.nama_kelas', 'kelas.tingkat')
            ->orderBy('kelas.tingkat')
            ->orderBy('kelas.nama_kelas')
            ->get();

        // Pelanggaran bulan ini
        $pelanggaranList = CatatanPelanggaran::with(['siswa', 'pelanggaran'])
            ->where('tanggal', '>=', $bulanIni)
            ->orderByDesc('tanggal')
            ->get();

        return view('monitoring.cetak_kedisiplinan', compact(
            'presensiPerKelas',
            'pelanggaranList',
            'namaBulan'
        ));
    }

    /**
     * Cetak Laporan Akademik (PDF-friendly view)
     */
    public function cetakAkademik(Request $request)
    {
        $this->authorize();

        $tahunAjaran = $request->get('tahun_ajaran', date('Y') . '/' . (date('Y') + 1));
        $semester = $request->get('semester', 'Ganjil');

        // Nilai per kelas per mapel
        $nilaiPerKelas = Nilai::join('kelas', 'nilai.kelas_id', '=', 'kelas.id')
            ->join('mata_pelajaran', 'nilai.mapel_id', '=', 'mata_pelajaran.id')
            ->where('nilai.tahun_ajaran', $tahunAjaran)
            ->where('nilai.semester', $semester)
            ->select(
                'kelas.nama_kelas',
                'kelas.tingkat',
                'mata_pelajaran.nama_mapel',
                DB::raw('ROUND(AVG(nilai.tugas), 1) as avg_tugas'),
                DB::raw('ROUND(AVG(nilai.kuis), 1) as avg_kuis'),
                DB::raw('ROUND(AVG(nilai.uts), 1) as avg_uts'),
                DB::raw('ROUND(AVG(nilai.uas), 1) as avg_uas'),
                DB::raw('ROUND(AVG((nilai.tugas + nilai.kuis + nilai.uts + nilai.uas) / 4), 1) as avg_total')
            )
            ->groupBy('kelas.nama_kelas', 'kelas.tingkat', 'mata_pelajaran.nama_mapel')
            ->orderBy('kelas.tingkat')
            ->orderBy('kelas.nama_kelas')
            ->orderBy('mata_pelajaran.nama_mapel')
            ->get();

        return view('monitoring.cetak_akademik', compact(
            'nilaiPerKelas',
            'tahunAjaran',
            'semester'
        ));
    }
}
