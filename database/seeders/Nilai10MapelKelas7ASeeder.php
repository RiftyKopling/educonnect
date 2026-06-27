<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Nilai10MapelKelas7ASeeder extends Seeder
{
    public function run(): void
    {
        // Parameter Dasar
        $kelasId = 1; // ID Kelas 7A
        $tahunAjaran = '2025/2026';
        $semester = 'Ganjil';

        // Array Pemetaan: [ID Mata Pelajaran => ID Guru]
        $mapelGuru = [
            11 => 4,  // MTK -> Dewi Kurniawati
            1  => 5,  // IPA -> Eko Prasetyo
            3  => 6,  // IPS -> Fitri Handayani
            6  => 7,  // B. Indonesia -> Gunawan Saputra
            8  => 8,  // B. Inggris -> Hana Pertiwi
            7  => 9,  // PPKN -> Irwan Maulana
            13 => 10, // PAI -> Joko Widodo
            17 => 11, // Seni Budaya -> Kartini Susanti
            10 => 12, // PJOK -> Lukman Hakim
            14 => 4,  // Informatika -> Ditugaskan ke ID 4 (Dewi Kurniawati)
        ];

        // Ambil semua siswa yang berada di kelas 7A
        $siswaKelas7A = DB::table('siswa')->where('kelas_id', $kelasId)->get();

        $dataNilai = [];

        // Looping 1: Untuk setiap siswa
        foreach ($siswaKelas7A as $siswa) {
            
            // Looping 2: Untuk setiap mata pelajaran
            foreach ($mapelGuru as $mapelId => $guruId) {
                $dataNilai[] = [
                    'semester'     => $semester,
                    'tahun_ajaran' => $tahunAjaran,
                    'kelas_id'     => $kelasId,
                    'mapel_id'     => $mapelId,
                    'guru_id'      => $guruId,
                    'siswa_nisn'   => $siswa->nisn,
                    
                    // Nilai acak (random) antara 75 sampai 100
                    'tugas'        => rand(75, 100),
                    'kuis'         => rand(75, 100),
                    'uts'          => rand(75, 100),
                    'uas'          => rand(75, 100),
                    
                    'catatan'      => 'Telah menyelesaikan target pembelajaran dengan baik.',
                    'created_at'   => Carbon::now(),
                    'updated_at'   => Carbon::now(),
                ];
            }
        }

        // Insert massal ke database (10 siswa x 10 mapel = 100 baris data)
        DB::table('nilai')->insert($dataNilai);
        
        $this->command->info('Berhasil menambahkan 100 data nilai (10 Mapel) untuk seluruh siswa kelas 7A!');
    }
}