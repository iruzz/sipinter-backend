<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PenempatanMagang;
use App\Models\PenilaianMagang;
use App\Models\User;

class PenilaianMagangSeeder extends Seeder
{
    public function run(): void
    {
        $penempatan = PenempatanMagang::all();

        // Penilaian Ahmad (Frontend Developer) - dari Perusahaan
        $perusahaan1 = User::where('email', 'majujaya@company.com')->first();
        PenilaianMagang::create([
            'penempatan_id' => $penempatan[0]->id,
            'penilai_type' => 'perusahaan',
            'penilai_id' => $perusahaan1->id,
            'nilai_disiplin' => 90,
            'nilai_kerjasama' => 85,
            'nilai_inisiatif' => 88,
            'nilai_teknis' => 92,
            'nilai_komunikasi' => 87,
            'komentar' => 'Ahmad menunjukkan performa yang sangat baik. Cepat belajar dan proaktif dalam menyelesaikan tugas.',
        ]);

        // Penilaian Ahmad - dari Guru
        $guru1 = User::where('email', 'budi.guru@test.com')->first();
        PenilaianMagang::create([
            'penempatan_id' => $penempatan[0]->id,
            'penilai_type' => 'guru',
            'penilai_id' => $guru1->id,
            'nilai_disiplin' => 88,
            'nilai_kerjasama' => 90,
            'nilai_inisiatif' => 85,
            'nilai_teknis' => 90,
            'nilai_komunikasi' => 88,
            'komentar' => 'Ahmad menunjukkan kemajuan yang signifikan selama magang. Disiplin dan tanggung jawab tinggi.',
        ]);

        // Penilaian Siti (UI/UX Designer) - dari Perusahaan
        $perusahaan2 = User::where('email', 'digital@company.com')->first();
        PenilaianMagang::create([
            'penempatan_id' => $penempatan[1]->id,
            'penilai_type' => 'perusahaan',
            'penilai_id' => $perusahaan2->id,
            'nilai_disiplin' => 95,
            'nilai_kerjasama' => 92,
            'nilai_inisiatif' => 90,
            'nilai_teknis' => 88,
            'nilai_komunikasi' => 93,
            'komentar' => 'Siti sangat kreatif dan detail. Hasil design-nya selalu memuaskan client.',
        ]);

        // Penilaian Siti - dari Guru
        $guru2 = User::where('email', 'ani.guru@test.com')->first();
        PenilaianMagang::create([
            'penempatan_id' => $penempatan[1]->id,
            'penilai_type' => 'guru',
            'penilai_id' => $guru2->id,
            'nilai_disiplin' => 93,
            'nilai_kerjasama' => 90,
            'nilai_inisiatif' => 92,
            'nilai_teknis' => 89,
            'nilai_komunikasi' => 91,
            'komentar' => 'Siti menunjukkan dedikasi tinggi dan selalu tepat waktu dalam mengumpulkan laporan.',
        ]);

        // Penilaian Budi (Backend Developer) - dari Perusahaan
        PenilaianMagang::create([
            'penempatan_id' => $penempatan[2]->id,
            'penilai_type' => 'perusahaan',
            'penilai_id' => $perusahaan1->id,
            'nilai_disiplin' => 85,
            'nilai_kerjasama' => 88,
            'nilai_inisiatif' => 82,
            'nilai_teknis' => 90,
            'nilai_komunikasi' => 84,
            'komentar' => 'Budi memiliki skill teknis yang baik, perlu ditingkatkan dalam hal komunikasi dengan tim.',
        ]);

        // Penilaian Budi - dari Guru
        $guru3 = User::where('email', 'agus.guru@test.com')->first();
        PenilaianMagang::create([
            'penempatan_id' => $penempatan[2]->id,
            'penilai_type' => 'guru',
            'penilai_id' => $guru3->id,
            'nilai_disiplin' => 87,
            'nilai_kerjasama' => 85,
            'nilai_inisiatif' => 84,
            'nilai_teknis' => 91,
            'nilai_komunikasi' => 83,
            'komentar' => 'Budi memiliki kemampuan teknis yang kuat. Perlu lebih aktif dalam diskusi kelompok.',
        ]);
    }
}