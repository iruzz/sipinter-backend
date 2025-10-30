<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_verified' => true,
            'is_active' => true, // ← TAMBAH INI
            'email_verified_at' => now(),
        ]);

        // Siswa (5 siswa)
        $siswas = [
            ['name' => 'Ahmad Fauzi', 'email' => 'ahmad@test.com'],
            ['name' => 'Siti Nurhaliza', 'email' => 'siti@test.com'],
            ['name' => 'Budi Santoso', 'email' => 'budi@test.com'],
            ['name' => 'Rina Wijaya', 'email' => 'rina@test.com'],
            ['name' => 'Dedi Kurniawan', 'email' => 'dedi@test.com'],
        ];

        foreach ($siswas as $siswa) {
            User::create([
                'name' => $siswa['name'],
                'email' => $siswa['email'],
                'password' => Hash::make('password123'),
                'role' => 'siswa',
                'is_verified' => true,
                'email_verified_at' => now(),
            ]);
        }

        // Perusahaan (3 perusahaan)
        $perusahaans = [
            ['name' => 'PT Maju Jaya', 'email' => 'majujaya@company.com'],
            ['name' => 'CV Digital Inovasi', 'email' => 'digital@company.com'],
            ['name' => 'PT Teknologi Nusantara', 'email' => 'teknologi@company.com'],
        ];

        foreach ($perusahaans as $perusahaan) {
            User::create([
                'name' => $perusahaan['name'],
                'email' => $perusahaan['email'],
                'password' => Hash::make('password123'),
                'role' => 'perusahaan',
                'is_verified' => true,
                'email_verified_at' => now(),
            ]);
        }

        // Guru (3 guru)
        $gurus = [
            ['name' => 'Pak Budi Hartono', 'email' => 'budi.guru@test.com'],
            ['name' => 'Bu Ani Suryani', 'email' => 'ani.guru@test.com'],
            ['name' => 'Pak Agus Prasetyo', 'email' => 'agus.guru@test.com'],
        ];

        foreach ($gurus as $guru) {
            User::create([
                'name' => $guru['name'],
                'email' => $guru['email'],
                'password' => Hash::make('password123'),
                'role' => 'guru',
                'is_verified' => true,
                'email_verified_at' => now(),
            ]);
        }
    }
}