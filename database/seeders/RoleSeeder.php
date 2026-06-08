<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Jalankan database seeder untuk Roles.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'Guru Mata Pelajaran', 'slug' => 'guru-mapel'],
            ['name' => 'Guru BK', 'slug' => 'guru-bk'],
            ['name' => 'Wali Kelas', 'slug' => 'wali-kelas'],
            ['name' => 'Kepala Sekolah', 'slug' => 'kepala-sekolah'],
            ['name' => 'Orang Tua', 'slug' => 'orang-tua'],
            ['name' => 'Admin Sekolah', 'slug' => 'admin-sekolah'],
        ];

        // Looping dan masukkan tiap role ke dalam database
        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}