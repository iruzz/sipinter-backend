<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LamaranMagang;
use App\Models\SiswaProfile;
use App\Models\Lowongan;
use Carbon\Carbon;

class LamaranMagangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua siswa dan lowongan
        $siswa = SiswaProfile::all();
        $lowongan = Lowongan::all();

        if ($siswa->isEmpty() || $lowongan->isEmpty()) {
            $this->command->warn('Tidak ada data siswa atau lowongan. Jalankan seeder siswa dan lowongan terlebih dahulu.');
            return;
        }

        $statuses = ['pending', 'interview', 'proses', 'diterima', 'ditolak'];
        
        $catatanSiswa = [
            'Saya sangat tertarik dengan posisi ini karena sesuai dengan jurusan saya.',
            'Saya memiliki pengalaman dalam bidang ini melalui project sekolah.',
            'Saya siap belajar dan berkontribusi untuk perusahaan.',
            'Saya berharap dapat bergabung dan mengembangkan skill saya.',
            'Posisi ini sangat sesuai dengan minat dan kemampuan saya.',
        ];

        $catatanPerusahaan = [
            'Lamaran Anda sedang kami review.',
            'Selamat! Anda lolos ke tahap interview.',
            'Mohon melengkapi dokumen yang kurang.',
            'Terima kasih atas minat Anda, namun saat ini kami belum bisa menerima.',
            'Skill Anda belum sesuai dengan requirement kami.',
            'Kami sangat terkesan dengan portfolio Anda.',
        ];

        $this->command->info('Creating lamaran magang...');

        // Buat 50 lamaran
        for ($i = 0; $i < 50; $i++) {
            $randomSiswa = $siswa->random();
            $randomLowongan = $lowongan->random();
            $status = $statuses[array_rand($statuses)];
            
            // Tentukan tanggal apply (random 1-60 hari yang lalu)
            $tanggalApply = Carbon::now()->subDays(rand(1, 60))->format('Y-m-d');
            
            // Tentukan tanggal interview jika status interview atau setelahnya
            $tanggalInterview = null;
            if (in_array($status, ['interview', 'proses', 'diterima'])) {
                $tanggalInterview = Carbon::parse($tanggalApply)->addDays(rand(3, 14))->format('Y-m-d H:i:s');
            }

            // Tentukan catatan perusahaan jika status bukan pending
            $catatanPerusahaanText = null;
            if ($status !== 'pending') {
                $catatanPerusahaanText = $catatanPerusahaan[array_rand($catatanPerusahaan)];
            }

            $timestamp = time();
            $suratLamaran = "lamaran/surat_lamaran_{$randomSiswa->id}_{$timestamp}.pdf";
            $cvFile = "lamaran/cv_{$randomSiswa->id}_{$timestamp}.pdf";
            $portofolioFile = rand(0, 1) ? "lamaran/portofolio_{$randomSiswa->id}_{$timestamp}.pdf" : null;

            LamaranMagang::create([
                'siswa_id' => $randomSiswa->id,
                'lowongan_id' => $randomLowongan->id,
                'status' => $status,
                'surat_lamaran' => $suratLamaran,
                'cv_file' => $cvFile,
                'portofolio_file' => $portofolioFile,
                'catatan_siswa' => $catatanSiswa[array_rand($catatanSiswa)],
                'catatan_perusahaan' => $catatanPerusahaanText,
                'tanggal_apply' => $tanggalApply,
                'tanggal_interview' => $tanggalInterview,
                'created_at' => Carbon::parse($tanggalApply),
                'updated_at' => Carbon::now(),
            ]);

            $number = $i + 1;
            $this->command->info("Created lamaran {$number}/50");
        }

        // Buat beberapa lamaran spesifik untuk testing
        $this->command->info('Creating specific test cases...');

        // 1. Lamaran baru (pending)
        LamaranMagang::create([
            'siswa_id' => $siswa->first()->id,
            'lowongan_id' => $lowongan->first()->id,
            'status' => 'pending',
            'surat_lamaran' => 'lamaran/surat_lamaran_pending.pdf',
            'cv_file' => 'lamaran/cv_pending.pdf',
            'portofolio_file' => 'lamaran/portofolio_pending.pdf',
            'catatan_siswa' => 'Saya sangat berminat dengan posisi ini dan siap memberikan kontribusi terbaik.',
            'catatan_perusahaan' => null,
            'tanggal_apply' => Carbon::now()->format('Y-m-d'),
            'tanggal_interview' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // 2. Lamaran dengan jadwal interview
        LamaranMagang::create([
            'siswa_id' => $siswa->skip(1)->first()->id,
            'lowongan_id' => $lowongan->skip(1)->first()->id,
            'status' => 'interview',
            'surat_lamaran' => 'lamaran/surat_lamaran_interview.pdf',
            'cv_file' => 'lamaran/cv_interview.pdf',
            'portofolio_file' => null,
            'catatan_siswa' => 'Saya memiliki pengalaman coding 2 tahun.',
            'catatan_perusahaan' => 'Silakan datang untuk interview pada tanggal yang telah ditentukan. Bawa portfolio lengkap Anda.',
            'tanggal_apply' => Carbon::now()->subDays(5)->format('Y-m-d'),
            'tanggal_interview' => Carbon::now()->addDays(3)->format('Y-m-d H:i:s'),
            'created_at' => Carbon::now()->subDays(5),
            'updated_at' => Carbon::now(),
        ]);

        // 3. Lamaran diterima
        LamaranMagang::create([
            'siswa_id' => $siswa->skip(2)->first()->id,
            'lowongan_id' => $lowongan->skip(2)->first()->id,
            'status' => 'diterima',
            'surat_lamaran' => 'lamaran/surat_lamaran_diterima.pdf',
            'cv_file' => 'lamaran/cv_diterima.pdf',
            'portofolio_file' => 'lamaran/portofolio_diterima.pdf',
            'catatan_siswa' => 'Portfolio saya dapat dilihat di GitHub.',
            'catatan_perusahaan' => 'Selamat! Anda diterima untuk magang di perusahaan kami. Silakan hubungi HRD untuk proses selanjutnya.',
            'tanggal_apply' => Carbon::now()->subDays(30)->format('Y-m-d'),
            'tanggal_interview' => Carbon::now()->subDays(20)->format('Y-m-d H:i:s'),
            'created_at' => Carbon::now()->subDays(30),
            'updated_at' => Carbon::now()->subDays(15),
        ]);

        // 4. Lamaran ditolak
        LamaranMagang::create([
            'siswa_id' => $siswa->skip(3)->first()->id,
            'lowongan_id' => $lowongan->skip(3)->first()->id,
            'status' => 'ditolak',
            'surat_lamaran' => 'lamaran/surat_lamaran_ditolak.pdf',
            'cv_file' => 'lamaran/cv_ditolak.pdf',
            'portofolio_file' => null,
            'catatan_siswa' => 'Saya siap belajar hal baru.',
            'catatan_perusahaan' => 'Terima kasih atas minat Anda. Saat ini kami membutuhkan kandidat dengan pengalaman lebih spesifik di bidang mobile development.',
            'tanggal_apply' => Carbon::now()->subDays(15)->format('Y-m-d'),
            'tanggal_interview' => Carbon::now()->subDays(10)->format('Y-m-d H:i:s'),
            'created_at' => Carbon::now()->subDays(15),
            'updated_at' => Carbon::now()->subDays(7),
        ]);

        // 5. Lamaran dalam proses
        LamaranMagang::create([
            'siswa_id' => $siswa->skip(4)->first()->id,
            'lowongan_id' => $lowongan->skip(4)->first()->id,
            'status' => 'proses',
            'surat_lamaran' => 'lamaran/surat_lamaran_proses.pdf',
            'cv_file' => 'lamaran/cv_proses.pdf',
            'portofolio_file' => 'lamaran/portofolio_proses.pdf',
            'catatan_siswa' => 'Saya berpengalaman dalam UI/UX design.',
            'catatan_perusahaan' => 'Lamaran Anda sedang dalam proses review final oleh tim manajemen.',
            'tanggal_apply' => Carbon::now()->subDays(10)->format('Y-m-d'),
            'tanggal_interview' => Carbon::now()->subDays(3)->format('Y-m-d H:i:s'),
            'created_at' => Carbon::now()->subDays(10),
            'updated_at' => Carbon::now()->subDays(2),
        ]);

        $total = LamaranMagang::count();
        $pending = LamaranMagang::where('status', 'pending')->count();
        $interview = LamaranMagang::where('status', 'interview')->count();
        $proses = LamaranMagang::where('status', 'proses')->count();
        $diterima = LamaranMagang::where('status', 'diterima')->count();
        $ditolak = LamaranMagang::where('status', 'ditolak')->count();

        $this->command->info('✓ Lamaran magang seeder completed successfully!');
        $this->command->info("Total lamaran created: {$total}");
        $this->command->info("- Pending: {$pending}");
        $this->command->info("- Interview: {$interview}");
        $this->command->info("- Proses: {$proses}");
        $this->command->info("- Diterima: {$diterima}");
        $this->command->info("- Ditolak: {$ditolak}");
    }
}