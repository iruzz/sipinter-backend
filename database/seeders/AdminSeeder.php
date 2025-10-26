<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@tes.com',
            'password' => Hash::make('password123'), // ganti sesuai kebutuhan
            'role' => 'admin',
            'email_verified_at' => now(),
            'is_verified' => true,
            'is_active' => true
        ]);
    }
}
