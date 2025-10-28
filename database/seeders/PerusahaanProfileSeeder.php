<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\PerusahaanProfile;

class PerusahaanProfileSeeder extends Seeder
{
    public function run(): void
    {
        $perusahaanUsers = User::where('role', 'perusahaan')->get();

        $profiles = [
            [
                'nama_perusahaan' => 'PT Maju Jaya',
                'bidang_usaha' => 'IT & Software Development',
                'alamat' => 'Jl. HR Muhammad No. 100, Surabaya',
                'kota' => 'Surabaya',
                'provinsi' => 'Jawa Timur',
                'telepon' => '031-1234567',
                'website' => 'https://majujaya.com',
                'deskripsi' => 'Perusahaan IT yang bergerak di bidang software development dan konsultasi IT',
                'pic_name' => 'Budi Santoso',
                'pic_jabatan' => 'HRD Manager',
                'pic_telepon' => '081234560001',
                'pic_email' => 'hrd@majujaya.com',
            ],
            [
                'nama_perusahaan' => 'CV Digital Inovasi',
                'bidang_usaha' => 'Digital Marketing & Design',
                'alamat' => 'Jl. Raya Darmo No. 55, Surabaya',
                'kota' => 'Surabaya',
                'provinsi' => 'Jawa Timur',
                'telepon' => '031-7654321',
                'website' => 'https://digitalinovasi.co.id',
                'deskripsi' => 'Agensi digital marketing dan creative design',
                'pic_name' => 'Ani Lestari',
                'pic_jabatan' => 'HR Supervisor',
                'pic_telepon' => '081234560002',
                'pic_email' => 'hr@digitalinovasi.co.id',
            ],
            [
                'nama_perusahaan' => 'PT Teknologi Nusantara',
                'bidang_usaha' => 'Tech Startup & IoT',
                'alamat' => 'Jl. Mayjen Sungkono No. 88, Surabaya',
                'kota' => 'Surabaya',
                'provinsi' => 'Jawa Timur',
                'telepon' => '031-9876543',
                'website' => 'https://teknusantara.id',
                'deskripsi' => 'Startup teknologi yang fokus pada IoT dan Smart City',
                'pic_name' => 'Dedi Firmansyah',
                'pic_jabatan' => 'Recruitment Officer',
                'pic_telepon' => '081234560003',
                'pic_email' => 'recruitment@teknusantara.id',
            ],
        ];

        foreach ($perusahaanUsers as $index => $user) {
            PerusahaanProfile::create([
                'user_id' => $user->id,
                ...$profiles[$index],
                'status_verifikasi' => 'approved',
            ]);
        }
    }
}