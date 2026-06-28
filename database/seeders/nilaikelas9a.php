<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class nilaikelas9a extends Seeder
{
    public function run(): void
    {
        // Parameter Dasar
        $kelasId = 3; // ID Kelas 9A
        $tahunAjaran = '2025/2026';
        $semester = 'Ganjil';

        // Array Pemetaan: [ID Mata Pelajaran => ID Guru]
        $mapelGuru = [
            10 => 12,
            9 => 9,
            8 => 8,
            7 => 9,
            6 => 7,
            5 => 6,
            5 => 9,
            4 => 6,
            2 => 5,
            11 => 4,
            17 => 11,
            12 => 47,
            16 => 48,
            14 => 48,
            13 => 10,
            15 => 49

        ];

        // Ambil semua siswa yang berada di kelas 9A
        $siswaKelas9A = DB::table('siswa')->where('kelas_id', $kelasId)->get();

        $dataNilai = [];

        // Looping 1: Untuk setiap siswa
        foreach ($siswaKelas9A as $siswa) {
            
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
        
        $this->command->info('Berhasil menambahkan data nilai dummy untuk seluruh siswa kelas 9A!');
    }
}