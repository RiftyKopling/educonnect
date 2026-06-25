<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pelanggaran;
use App\Models\CatatanPelanggaran;
use App\Models\Konseling;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Role;
use Carbon\Carbon;

class KonselingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Buat Master Pelanggaran
        $pelanggarans = [
            ['nama_pelanggaran' => 'Terlambat Masuk Sekolah', 'kategori' => 'Ringan', 'deskripsi' => 'Siswa datang melewati jam 07:00 tanpa alasan yang sah.'],
            ['nama_pelanggaran' => 'Atribut Seragam Tidak Lengkap', 'kategori' => 'Ringan', 'deskripsi' => 'Tidak memakai dasi, topi, atau sabuk sesuai ketentuan hari tersebut.'],
            ['nama_pelanggaran' => 'Membolos Jam Pelajaran', 'kategori' => 'Sedang', 'deskripsi' => 'Meninggalkan kelas atau area sekolah pada saat jam belajar aktif tanpa izin guru.'],
            ['nama_pelanggaran' => 'Bermain HP Saat Pelajaran', 'kategori' => 'Ringan', 'deskripsi' => 'Menggunakan gawai di dalam kelas saat guru sedang menerangkan tanpa instruksi.'],
            ['nama_pelanggaran' => 'Berkelahi di Area Sekolah', 'kategori' => 'Berat', 'deskripsi' => 'Terlibat kontak fisik agresif dengan siswa lain di lingkungan sekolah.'],
            ['nama_pelanggaran' => 'Membawa Rokok / Vaping', 'kategori' => 'Berat', 'deskripsi' => 'Membawa, menggunakan, atau mengedarkan rokok tembakau atau rokok elektrik.'],
        ];

        foreach ($pelanggarans as $p) {
            Pelanggaran::firstOrCreate(
                ['nama_pelanggaran' => $p['nama_pelanggaran']],
                $p
            );
        }

        // 2. Ambil referensi User dan Siswa
        $guruBkRole = Role::where('slug', 'guru-bk')->first();
        if (!$guruBkRole) {
            $this->command->info('Role guru-bk tidak ditemukan. Harap jalankan RoleSeeder terlebih dahulu.');
            return;
        }

        $guruBk = User::where('role_id', $guruBkRole->id)->first();
        if (!$guruBk) {
            $this->command->info('User dengan role Guru BK tidak ditemukan.');
            return;
        }

        // Ambil beberapa siswa acak
        $siswas = Siswa::inRandomOrder()->take(3)->get();
        if ($siswas->count() === 0) {
            $this->command->info('Tabel Siswa kosong. Harap isi data siswa terlebih dahulu.');
            return;
        }

        // Data Pelanggaran yang baru dibuat
        $semuaPelanggaran = Pelanggaran::all();

        // 3. Buat Catatan Pelanggaran
        foreach ($siswas as $index => $siswa) {
            $pelanggaran = $semuaPelanggaran->random();
            CatatanPelanggaran::create([
                'tanggal' => Carbon::now()->subDays(rand(1, 14))->format('Y-m-d'),
                'siswa_nisn' => $siswa->nisn,
                'pelanggaran_id' => $pelanggaran->id,
                'guru_bk_id' => $guruBk->id,
                'keterangan' => 'Siswa telah diberikan peringatan lisan dan diminta untuk tidak mengulangi lagi.',
            ]);
        }

        // 4. Buat Sesi Konseling
        // - Konseling Pribadi (Selesai)
        Konseling::create([
            'tanggal' => Carbon::now()->subDays(2)->setHour(10)->setMinute(30),
            'siswa_nisn' => $siswas[0]->nisn,
            'guru_bk_id' => $guruBk->id,
            'jenis_layanan' => 'Konseling Pribadi',
            'topik' => 'Masalah Motivasi Belajar dan Keluarga',
            'deskripsi_kasus' => 'Siswa menceritakan bahwa orang tuanya sering bertengkar di rumah, yang membuatnya sulit fokus belajar dan sering murung di kelas.',
            'tindak_lanjut' => '1. Memberikan dukungan emosional. 2. Akan diagendakan pemanggilan orang tua jika kondisi memburuk. 3. Observasi selama 1 minggu ke depan.',
            'status' => 'Selesai'
        ]);

        // - Bimbingan Karir (Terjadwal di masa depan)
        if (isset($siswas[1])) {
            Konseling::create([
                'tanggal' => Carbon::now()->addDays(3)->setHour(9)->setMinute(0),
                'siswa_nisn' => $siswas[1]->nisn,
                'guru_bk_id' => $guruBk->id,
                'jenis_layanan' => 'Bimbingan Karir',
                'topik' => 'Konsultasi Pemilihan Jurusan SMA/SMK',
                'deskripsi_kasus' => null, // Belum terjadi
                'tindak_lanjut' => null, // Belum terjadi
                'status' => 'Terjadwal'
            ]);
        }

        // - Konseling Kelompok (Terjadwal)
        if (isset($siswas[2])) {
            Konseling::create([
                'tanggal' => Carbon::now()->addDays(1)->setHour(13)->setMinute(0),
                'siswa_nisn' => $siswas[2]->nisn,
                'guru_bk_id' => $guruBk->id,
                'jenis_layanan' => 'Konseling Kelompok',
                'topik' => 'Dinamika Teman Sebaya di Kelas',
                'deskripsi_kasus' => null,
                'tindak_lanjut' => null,
                'status' => 'Terjadwal'
            ]);
        }

        $this->command->info('Data Dummy Konseling & Pelanggaran berhasil di-seed!');
    }
}
