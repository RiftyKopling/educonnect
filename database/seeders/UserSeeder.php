<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Jalankan database seeder untuk Users (Dummy data).
     */
    public function run(): void
    {
        $roles = Role::all();

        // Buatkan satu akun dummy untuk setiap peran untuk keperluan pengujian
        foreach ($roles as $role) {
            User::create([
                'name' => 'Akun ' . $role->name,
                // Email format: slug@educonnect.test (Contoh: admin-sekolah@educonnect.test)
                'email' => $role->slug . '@educonnect.test',
                'password' => Hash::make('password123'),
                'role_id' => $role->id,
            ]);
        }
    }
}