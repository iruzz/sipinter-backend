<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PerusahaanProfile;
use App\Models\Lowongan;

class LowonganMagangSeeder extends Seeder
{
    public function run(): void
    {
        $perusahaans = PerusahaanProfile::all();

        $lowongans = [
            // PT Maju Jaya
            [
                'perusahaan_id' => $perusahaans[0]->id,
                'judul' => 'Frontend Developer Intern',
                'deskripsi' => 'Kami mencari frontend developer intern yang passionate dalam web development. Akan bekerja dengan teknologi React.js dan Tailwind CSS.',
                'persyaratan' => "- Mahasiswa aktif atau fresh graduate\n- Menguasai HTML, CSS, JavaScript\n- Familiar dengan React.js\n- Paham responsive design\n- Bisa bekerja dalam tim",
                'jumlah_posisi' => 3,
                'lokasi' => 'Surabaya',
                'durasi_magang' => 6,
                'gaji' => 2000000,
                'tanggal_mulai' => now()->addDays(7),
                'tanggal_selesai' => now()->addMonths(7),
                'status' => 'aktif',
                'status_approval' => 'approved',
            ],
            [
                'perusahaan_id' => $perusahaans[0]->id,
                'judul' => 'Backend Developer Intern',
                'deskripsi' => 'Bergabunglah dengan tim backend kami dan belajar pengembangan API dengan Laravel.',
                'persyaratan' => "- Menguasai PHP\n- Familiar dengan Laravel atau framework PHP lainnya\n- Paham database MySQL\n- Memahami RESTful API",
                'jumlah_posisi' => 2,
                'lokasi' => 'Surabaya',
                'durasi_magang' => 6,
                'gaji' => 2000000,
                'tanggal_mulai' => now()->addDays(14),
                'tanggal_selesai' => now()->addMonths(7),
                'status' => 'aktif',
                'status_approval' => 'approved',
            ],
            // CV Digital Inovasi
            [
                'perusahaan_id' => $perusahaans[1]->id,
                'judul' => 'UI/UX Designer Intern',
                'deskripsi' => 'Kesempatan untuk belajar UI/UX design di proyek-proyek digital marketing yang menarik.',
                'persyaratan' => "- Menguasai Figma atau Adobe XD\n- Paham prinsip design\n- Kreatif dan detail oriented\n- Portfolio design (wajib)",
                'jumlah_posisi' => 2,
                'lokasi' => 'Surabaya',
                'durasi_magang' => 3,
                'gaji' => 1500000,
                'tanggal_mulai' => now()->addDays(10),
                'tanggal_selesai' => now()->addMonths(4),
                'status' => 'aktif',
                'status_approval' => 'approved',
            ],
            [
                'perusahaan_id' => $perusahaans[1]->id,
                'judul' => 'Social Media Specialist Intern',
                'deskripsi' => 'Bantu kami mengelola social media client dan belajar strategi digital marketing.',
                'persyaratan' => "- Aktif di social media\n- Kreatif dalam membuat konten\n- Paham trend social media\n- Bisa editing foto/video",
                'jumlah_posisi' => 2,
                'lokasi' => 'Surabaya',
                'durasi_magang' => 4,
                'gaji' => 1200000,
                'tanggal_mulai' => now()->addDays(5),
                'tanggal_selesai' => now()->addMonths(5),
                'status' => 'aktif',
                'status_approval' => 'approved',
            ],
            // PT Teknologi Nusantara
            [
                'perusahaan_id' => $perusahaans[2]->id,
                'judul' => 'IoT Developer Intern',
                'deskripsi' => 'Belajar Internet of Things dan Smart City solutions bersama tim engineer kami.',
                'persyaratan' => "- Menguasai Arduino/Raspberry Pi\n- Paham sensor dan microcontroller\n- Bisa programming (C/C++/Python)\n- Tertarik dengan IoT",
                'jumlah_posisi' => 2,
                'lokasi' => 'Surabaya',
                'durasi_magang' => 6,
                'gaji' => 2500000,
                'tanggal_mulai' => now()->addDays(20),
                'tanggal_selesai' => now()->addMonths(7),
                'status' => 'aktif',
                'status_approval' => 'approved',
            ],
        ];

        foreach ($lowongans as $lowongan) {
            Lowongan::create($lowongan);
        }
    }
}