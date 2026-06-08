<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed aplikasi dengan memanggil seeder spesifik.
     */
    public function run(): void
    {
        // Urutan pemanggilan sangat penting, RoleSeeder harus dijalankan 
        // lebih dulu agar 'role_id' tersedia untuk 'UserSeeder'.
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
        ]);
    }
}