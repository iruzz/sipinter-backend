<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            SiswaProfileSeeder::class,
            PerusahaanProfileSeeder::class,
            GuruProfileSeeder::class,
            LowonganMagangSeeder::class,
            LamaranMagangSeeder::class,
            PenempatanMagangSeeder::class,
            PenilaianMagangSeeder::class,
        ]);
    }
}