<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Presensi;
use App\Models\Siswa;

class PresensiJuliSeeder extends Seeder
{
    public function run(): void
    {
        $guruMapel = [
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

        $tanggal = '2026-07-14';

        // ambil semua kelas yang ada siswanya
        $kelasIds = Siswa::distinct()
            ->pluck('kelas_id');

        foreach ($kelasIds as $kelasId) {

            $siswas = Siswa::where('kelas_id', $kelasId)->get();

            // pilih 2 mapel untuk kelas ini
            $mapelHariIni = collect(array_keys($guruMapel))
                ->shuffle()
                ->take(2);

            foreach ($mapelHariIni as $mapelId) {

                foreach ($siswas as $siswa) {

                    $acak = rand(1,100);

                    if ($acak <= 85) {
                        $status = 'H';
                        $catatan = null;
                    } elseif ($acak <= 90) {
                        $status = 'S';
                        $catatan = 'Sakit';
                    } elseif ($acak <= 95) {
                        $status = 'I';
                        $catatan = 'Izin';
                    } elseif ($acak <= 98) {
                        $status = 'A';
                        $catatan = 'Alpa';
                    } else {
                        $status = 'D';
                        $catatan = 'Dispensasi';
                    }

                    Presensi::create([
                        'tanggal'     => $tanggal,
                        'kelas_id'    => $kelasId,
                        'mapel_id'    => $mapelId,
                        'guru_id'     => $guruMapel[$mapelId],
                        'siswa_nisn'  => $siswa->nisn,
                        'status'      => $status,
                        'catatan'     => $catatan,
                    ]);
                }
            }
        }

        $this->command->info('Presensi satu hari berhasil dibuat.');
    }
}