<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Siswa;
use App\Models\Kelas; // Pastikan model Kelas di-import
use Faker\Factory as Faker;

class SiswaSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        // Tuliskan NAMA KELAS yang ingin diisi siswa. 
        // Karena 7B di-skip, kita tidak memasukkannya ke daftar ini.
        $daftar_nama_kelas = ['7A', '8A', '8B', '9A', '9B'];

        foreach ($daftar_nama_kelas as $nama_kelas) {
            
            // Laravel akan mencari kelas di database yang namanya sesuai
            $kelas = Kelas::where('nama_kelas', $nama_kelas)->first();

            // Jika kelasnya ditemukan di database, jalankan pembuatan siswa
            if ($kelas) {
                for ($i = 1; $i <= 10; $i++) {
                    Siswa::create([
                        'nisn' => $faker->unique()->numerify('00########'),
                        'nama_lengkap' => $faker->name(),
                        'jenis_kelamin' => $faker->randomElement(['L', 'P']),
                        'tempat_lahir' => $faker->city(),
                        'tanggal_lahir' => $faker->dateTimeBetween('-15 years', '-12 years')->format('Y-m-d'),
                        'alamat' => $faker->address(),
                        // Otomatis mengambil ID asli dari tabel kelas Anda (misal 7A otomatis dapat ID 7)
                        'kelas_id' => $kelas->id, 
                        'orang_tua_id' => null, 
                        'status' => 'aktif'
                    ]);
                }
            } else {
                // (Opsional) Memberi info di terminal jika ada kelas yang tidak ketemu
                $this->command->info("Kelas $nama_kelas tidak ditemukan di database, di-skip.");
            }
        }
    }
}