<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama_kelas' => '7A', 'tingkat' => 7, 'tahun_ajaran' => '2025/2026'],
            ['nama_kelas' => '7B', 'tingkat' => 7, 'tahun_ajaran' => '2025/2026'],
            ['nama_kelas' => '8A', 'tingkat' => 8, 'tahun_ajaran' => '2025/2026'],
            ['nama_kelas' => '8B', 'tingkat' => 8, 'tahun_ajaran' => '2025/2026'],
            ['nama_kelas' => '9A', 'tingkat' => 9, 'tahun_ajaran' => '2025/2026'],
            ['nama_kelas' => '9B', 'tingkat' => 9, 'tahun_ajaran' => '2025/2026'],
        ];

        foreach ($data as $item) {
            Kelas::create($item);
        }
    }
}