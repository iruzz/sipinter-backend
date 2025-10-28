<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiswaProfile;
use App\Models\Lowongan;
use App\Models\LamaranMagang;

class LamaranMagangSeeder extends Seeder
{
    public function run(): void
    {
        $siswas = SiswaProfile::all();
        $lowongans = Lowongan::all();

        // Ahmad - Apply Frontend Developer (Diterima)
        LamaranMagang::create([
            'siswa_id' => $siswas[0]->id,
            'lowongan_id' => $lowongans[0]->id,
            'status' => 'diterima',
            'surat_lamaran' => 'Saya sangat tertarik dengan posisi Frontend Developer di perusahaan Bapak/Ibu.',
            'tanggal_apply' => now()->subDays(10),
        ]);

        // Siti - Apply UI/UX Designer (Diterima)
        LamaranMagang::create([
            'siswa_id' => $siswas[1]->id,
            'lowongan_id' => $lowongans[2]->id,
            'status' => 'diterima',
            'surat_lamaran' => 'Dengan portfolio design saya, saya yakin dapat berkontribusi di perusahaan.',
            'tanggal_apply' => now()->subDays(8),
        ]);

        // Budi - Apply Backend Developer (Diterima)
        LamaranMagang::create([
            'siswa_id' => $siswas[2]->id,
            'lowongan_id' => $lowongans[1]->id,
            'status' => 'diterima',
            'surat_lamaran' => 'Saya memiliki pengalaman dengan Laravel dan ingin mengembangkan skill saya.',
            'tanggal_apply' => now()->subDays(12),
        ]);

        // Rina - Apply Social Media (Pending)
        LamaranMagang::create([
            'siswa_id' => $siswas[3]->id,
            'lowongan_id' => $lowongans[3]->id,
            'status' => 'pending',
            'surat_lamaran' => 'Saya kreatif dalam membuat konten social media dan siap belajar.',
            'tanggal_apply' => now()->subDays(3),
        ]);

        // Dedi - Apply IoT Developer (Interview)
        LamaranMagang::create([
            'siswa_id' => $siswas[4]->id,
            'lowongan_id' => $lowongans[4]->id,
            'status' => 'interview',
            'surat_lamaran' => 'Saya tertarik dengan IoT dan memiliki pengalaman dengan Arduino.',
            'tanggal_apply' => now()->subDays(5),
            'tanggal_interview' => now()->addDays(2),
        ]);
    }
}