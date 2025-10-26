<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LowonganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // Pastikan ada perusahaan profiles dulu
        $perusahaanIds = DB::table('perusahaan_profiles')->pluck('id')->toArray();

        if (empty($perusahaanIds)) {
            $this->command->warn('Tidak ada perusahaan profiles. Jalankan PerusahaanProfileSeeder terlebih dahulu!');
            return;
        }

        $lowongan = [
            // MAGANG - Pending
            [
                'perusahaan_id' => $perusahaanIds[array_rand($perusahaanIds)],
                'tipe_lowongan' => 'magang',
                'judul' => 'Magang Web Developer',
                'deskripsi' => "Kami membuka kesempatan magang untuk posisi Web Developer. Kamu akan belajar:\n- Mengembangkan website dengan teknologi modern\n- Bekerja dalam tim development\n- Mengelola database dan API\n- Deploy aplikasi ke production\n\nKesempatan belajar langsung dari senior developer!",
                'persyaratan' => "- Mahasiswa aktif jurusan Teknik Informatika/RPL\n- Menguasai HTML, CSS, JavaScript\n- Familiar dengan PHP atau framework Laravel\n- Memiliki portofolio (Github/Website)\n- Komunikatif dan mau belajar",
                'jumlah_posisi' => 2,
                'lokasi' => 'Jakarta Selatan',
                'durasi_magang' => 6,
                'gaji' => 1500000.00,
                'tanggal_mulai' => $now->copy()->addDays(14)->format('Y-m-d'),
                'tanggal_selesai' => $now->copy()->addDays(14)->addMonths(6)->format('Y-m-d'),
                'status' => 'draft',
                'status_approval' => 'pending',
                'catatan_admin' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'perusahaan_id' => $perusahaanIds[array_rand($perusahaanIds)],
                'tipe_lowongan' => 'magang',
                'judul' => 'Magang UI/UX Designer',
                'deskripsi' => "Bergabunglah dengan tim design kami! Kamu akan:\n- Membuat wireframe dan prototype\n- Design user interface aplikasi mobile dan web\n- Melakukan user research\n- Kolaborasi dengan product team\n\nMentoring intensif dari Lead Designer!",
                'persyaratan' => "- Mahasiswa/i D3/S1 Design, DKV, atau sejenis\n- Portfolio design (Behance/Dribbble)\n- Menguasai Figma/Adobe XD\n- Paham design thinking\n- Kreatif dan detail oriented",
                'jumlah_posisi' => 1,
                'lokasi' => 'Bandung',
                'durasi_magang' => 4,
                'gaji' => 1200000.00,
                'tanggal_mulai' => $now->copy()->addDays(20)->format('Y-m-d'),
                'tanggal_selesai' => $now->copy()->addDays(20)->addMonths(4)->format('Y-m-d'),
                'status' => 'draft',
                'status_approval' => 'pending',
                'catatan_admin' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // MAGANG - Approved & Aktif
            [
                'perusahaan_id' => $perusahaanIds[array_rand($perusahaanIds)],
                'tipe_lowongan' => 'magang',
                'judul' => 'Magang Mobile Developer (Flutter)',
                'deskripsi' => "Kesempatan emas belajar mobile development!\n- Develop aplikasi mobile dengan Flutter\n- Integrasi REST API\n- Testing dan debugging\n- Publish ke Play Store/App Store\n\nProject real yang akan masuk portfolio kamu!",
                'persyaratan' => "- Mahasiswa aktif IT/Informatika\n- Menguasai Dart & Flutter\n- Paham state management (Provider/Bloc)\n- Git version control\n- Problem solving yang baik",
                'jumlah_posisi' => 3,
                'lokasi' => 'Jakarta Pusat',
                'durasi_magang' => 5,
                'gaji' => 2000000.00,
                'tanggal_mulai' => $now->copy()->addDays(7)->format('Y-m-d'),
                'tanggal_selesai' => $now->copy()->addDays(7)->addMonths(5)->format('Y-m-d'),
                'status' => 'aktif',
                'status_approval' => 'approved',
                'catatan_admin' => 'Lowongan disetujui. Semua persyaratan sudah memenuhi.',
                'created_at' => $now->subDays(3),
                'updated_at' => $now->subDays(1),
            ],
            [
                'perusahaan_id' => $perusahaanIds[array_rand($perusahaanIds)],
                'tipe_lowongan' => 'magang',
                'judul' => 'Magang Data Analyst',
                'deskripsi' => "Belajar analisa data untuk business intelligence:\n- Data cleaning & processing\n- Visualisasi data dengan tools modern\n- Membuat dashboard reporting\n- Statistical analysis\n\nTraining lengkap dari data team!",
                'persyaratan' => "- Mahasiswa Statistik/Matematika/TI\n- Familiar Python/R\n- Basic SQL\n- Analytical thinking\n- Excel/Google Sheets advanced",
                'jumlah_posisi' => 2,
                'lokasi' => 'Surabaya',
                'durasi_magang' => 6,
                'gaji' => 1800000.00,
                'tanggal_mulai' => $now->copy()->addDays(10)->format('Y-m-d'),
                'tanggal_selesai' => $now->copy()->addDays(10)->addMonths(6)->format('Y-m-d'),
                'status' => 'aktif',
                'status_approval' => 'approved',
                'catatan_admin' => 'Disetujui. Program magang sudah sesuai kurikulum SMK.',
                'created_at' => $now->subDays(5),
                'updated_at' => $now->subDays(2),
            ],

            // MAGANG - Rejected
            [
                'perusahaan_id' => $perusahaanIds[array_rand($perusahaanIds)],
                'tipe_lowongan' => 'magang',
                'judul' => 'Magang Social Media Specialist',
                'deskripsi' => "Kelola social media brand kami!\n- Membuat konten kreatif\n- Schedule posting\n- Community management\n- Analytics reporting",
                'persyaratan' => "- Mahasiswa aktif\n- Kreatif dalam membuat konten\n- Familiar Instagram, TikTok, Twitter\n- Copywriting skills",
                'jumlah_posisi' => 1,
                'lokasi' => 'Jakarta',
                'durasi_magang' => 3,
                'gaji' => 1000000.00,
                'tanggal_mulai' => $now->copy()->addDays(5)->format('Y-m-d'),
                'tanggal_selesai' => $now->copy()->addDays(5)->addMonths(3)->format('Y-m-d'),
                'status' => 'nonaktif',
                'status_approval' => 'rejected',
                'catatan_admin' => 'Durasi magang terlalu singkat (minimum 4 bulan). Gaji di bawah standar UMK. Mohon sesuaikan.',
                'created_at' => $now->subDays(7),
                'updated_at' => $now->subDays(4),
            ],

            // KERJA - Pending
            [
                'perusahaan_id' => $perusahaanIds[array_rand($perusahaanIds)],
                'tipe_lowongan' => 'kerja',
                'judul' => 'Junior Frontend Developer',
                'deskripsi' => "Join our development team sebagai Junior Frontend Developer:\n- Develop dan maintain web applications\n- Collaborate dengan backend team\n- Code review dan optimization\n- Participate dalam sprint planning\n\nFull time position dengan benefits lengkap!",
                'persyaratan' => "- Min. D3 Teknik Informatika/sejenis\n- 1+ tahun pengalaman React/Vue\n- Paham responsive design\n- Git & CI/CD\n- Problem solving & teamwork",
                'jumlah_posisi' => 2,
                'lokasi' => 'Jakarta',
                'durasi_magang' => null,
                'gaji' => 6000000.00,
                'tanggal_mulai' => $now->copy()->addDays(30)->format('Y-m-d'),
                'tanggal_selesai' => $now->copy()->addYear()->format('Y-m-d'),
                'status' => 'draft',
                'status_approval' => 'pending',
                'catatan_admin' => null,
                'created_at' => $now->subDays(1),
                'updated_at' => $now->subDays(1),
            ],

            // KERJA - Approved & Aktif
            [
                'perusahaan_id' => $perusahaanIds[array_rand($perusahaanIds)],
                'tipe_lowongan' => 'kerja',
                'judul' => 'Backend Developer (Laravel)',
                'deskripsi' => "We are hiring Backend Developer!\n- Build RESTful API\n- Database design & optimization\n- Integration dengan third-party services\n- Code quality & testing\n\nCompetitive salary + benefits + career growth!",
                'persyaratan' => "- Min. S1 Teknik Informatika\n- 2+ tahun pengalaman Laravel\n- MySQL/PostgreSQL expert\n- RESTful API design\n- Docker & deployment",
                'jumlah_posisi' => 1,
                'lokasi' => 'Remote/Jakarta',
                'durasi_magang' => null,
                'gaji' => 8500000.00,
                'tanggal_mulai' => $now->copy()->addDays(20)->format('Y-m-d'),
                'tanggal_selesai' => $now->copy()->addYear()->format('Y-m-d'),
                'status' => 'aktif',
                'status_approval' => 'approved',
                'catatan_admin' => 'Lowongan disetujui. Benefit dan gaji sudah sesuai standar industri.',
                'created_at' => $now->subDays(10),
                'updated_at' => $now->subDays(6),
            ],
            [
                'perusahaan_id' => $perusahaanIds[array_rand($perusahaanIds)],
                'tipe_lowongan' => 'kerja',
                'judul' => 'Full Stack Developer',
                'deskripsi' => "Exciting opportunity untuk Full Stack Developer:\n- End-to-end development\n- Frontend (React) & Backend (Node.js)\n- Database management\n- DevOps & deployment\n\nWork with modern tech stack!",
                'persyaratan' => "- Min. S1 Informatika/sejenis\n- 3+ tahun pengalaman fullstack\n- React & Node.js/Express\n- MongoDB/PostgreSQL\n- AWS/GCP deployment",
                'jumlah_posisi' => 1,
                'lokasi' => 'Bandung',
                'durasi_magang' => null,
                'gaji' => 10000000.00,
                'tanggal_mulai' => $now->copy()->addDays(15)->format('Y-m-d'),
                'tanggal_selesai' => $now->copy()->addYear()->format('Y-m-d'),
                'status' => 'aktif',
                'status_approval' => 'approved',
                'catatan_admin' => 'Disetujui. Perusahaan sudah terverifikasi.',
                'created_at' => $now->subDays(8),
                'updated_at' => $now->subDays(5),
            ],

            // KERJA - Ditutup
            [
                'perusahaan_id' => $perusahaanIds[array_rand($perusahaanIds)],
                'tipe_lowongan' => 'kerja',
                'judul' => 'DevOps Engineer',
                'deskripsi' => "Looking for experienced DevOps Engineer:\n- CI/CD pipeline setup\n- Infrastructure as Code\n- Monitoring & logging\n- Cloud infrastructure management",
                'persyaratan' => "- Min. S1 TI/sejenis\n- 2+ tahun pengalaman DevOps\n- Docker & Kubernetes\n- AWS/GCP/Azure\n- Terraform/Ansible",
                'jumlah_posisi' => 1,
                'lokasi' => 'Jakarta',
                'durasi_magang' => null,
                'gaji' => 12000000.00,
                'tanggal_mulai' => $now->copy()->subDays(30)->format('Y-m-d'),
                'tanggal_selesai' => $now->copy()->addMonths(10)->format('Y-m-d'),
                'status' => 'ditutup',
                'status_approval' => 'approved',
                'catatan_admin' => 'Lowongan telah ditutup. Posisi sudah terisi.',
                'created_at' => $now->subDays(45),
                'updated_at' => $now->subDays(2),
            ],

            // MAGANG - Berbagai kondisi lainnya
            [
                'perusahaan_id' => $perusahaanIds[array_rand($perusahaanIds)],
                'tipe_lowongan' => 'magang',
                'judul' => 'Magang Digital Marketing',
                'deskripsi' => "Program magang digital marketing:\n- SEO & SEM\n- Email marketing campaigns\n- Social media advertising\n- Analytics & reporting\n\nCertificate provided!",
                'persyaratan' => "- Mahasiswa aktif Marketing/Komunikasi/sejenis\n- Basic digital marketing knowledge\n- Familiar Google Analytics\n- Creative & analytical\n- Good communication skills",
                'jumlah_posisi' => 2,
                'lokasi' => 'Yogyakarta',
                'durasi_magang' => 4,
                'gaji' => 1500000.00,
                'tanggal_mulai' => $now->copy()->addDays(12)->format('Y-m-d'),
                'tanggal_selesai' => $now->copy()->addDays(12)->addMonths(4)->format('Y-m-d'),
                'status' => 'aktif',
                'status_approval' => 'approved',
                'catatan_admin' => 'Program magang sudah sesuai standar. Disetujui.',
                'created_at' => $now->subDays(6),
                'updated_at' => $now->subDays(3),
            ],
        ];

        DB::table('lowongan')->insert($lowongan);

        $this->command->info('✓ Lowongan seeder berhasil dijalankan!');
        $this->command->info('Total: ' . count($lowongan) . ' lowongan');
    }
}