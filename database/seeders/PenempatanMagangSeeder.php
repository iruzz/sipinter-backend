<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LamaranMagang;
use App\Models\GuruProfile;
use App\Models\PenempatanMagang;

class PenempatanMagangSeeder extends Seeder
{
    public function run(): void
    {
        $lamaranDiterima = LamaranMagang::where('status', 'diterima')->get();
        $gurus = GuruProfile::all();

        // Ahmad - Frontend Developer
        PenempatanMagang::create([
            'lamaran_id' => $lamaranDiterima[0]->id,
            'siswa_id' => $lamaranDiterima[0]->siswa_id,
            'perusahaan_id' => $lamaranDiterima[0]->lowongan->perusahaan_id,
            'guru_pembimbing_id' => $gurus[0]->id,
            'tanggal_mulai' => now()->addDays(7),
            'tanggal_selesai' => now()->addMonths(7),
            'status' => 'aktif',
        ]);

        // Siti - UI/UX Designer
        PenempatanMagang::create([
            'lamaran_id' => $lamaranDiterima[1]->id,
            'siswa_id' => $lamaranDiterima[1]->siswa_id,
            'perusahaan_id' => $lamaranDiterima[1]->lowongan->perusahaan_id,
            'guru_pembimbing_id' => $gurus[1]->id,
            'tanggal_mulai' => now()->addDays(10),
            'tanggal_selesai' => now()->addMonths(4),
            'status' => 'aktif',
        ]);

        // Budi - Backend Developer
        PenempatanMagang::create([
            'lamaran_id' => $lamaranDiterima[2]->id,
            'siswa_id' => $lamaranDiterima[2]->siswa_id,
            'perusahaan_id' => $lamaranDiterima[2]->lowongan->perusahaan_id,
            'guru_pembimbing_id' => $gurus[2]->id,
            'tanggal_mulai' => now()->addDays(14),
            'tanggal_selesai' => now()->addMonths(7),
            'status' => 'aktif',
        ]);
    }
}