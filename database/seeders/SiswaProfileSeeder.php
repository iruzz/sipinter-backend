<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\SiswaProfile;

class SiswaProfileSeeder extends Seeder
{
    public function run(): void
    {
        $siswaUsers = User::where('role', 'siswa')->get();

        $profiles = [
            [
                'nisn' => '0012345678',
                'nis' => '2024001',
                'tanggal_lahir' => '2006-05-15',
                'jenis_kelamin' => 'L',
                'alamat' => 'Jl. Merdeka No. 123, Surabaya',
                'telepon' => '081234567890',
                'jurusan' => 'RPL',
                'kelas' => 'XII RPL 1',
                'tahun_lulus' => 2024,
            ],
            [
                'nisn' => '0012345679',
                'nis' => '2024002',
                'tanggal_lahir' => '2006-08-20',
                'jenis_kelamin' => 'P',
                'alamat' => 'Jl. Diponegoro No. 45, Surabaya',
                'telepon' => '081234567891',
                'jurusan' => 'RPL',
                'kelas' => 'XII RPL 1',
                'tahun_lulus' => 2024,
            ],
            [
                'nisn' => '0012345680',
                'nis' => '2024003',
                'tanggal_lahir' => '2006-03-10',
                'jenis_kelamin' => 'L',
                'alamat' => 'Jl. Sudirman No. 78, Surabaya',
                'telepon' => '081234567892',
                'jurusan' => 'TKJ',
                'kelas' => 'XII TKJ 2',
                'tahun_lulus' => 2024,
            ],
            [
                'nisn' => '0012345681',
                'nis' => '2024004',
                'tanggal_lahir' => '2006-11-25',
                'jenis_kelamin' => 'P',
                'alamat' => 'Jl. Ahmad Yani No. 56, Surabaya',
                'telepon' => '081234567893',
                'jurusan' => 'MM',
                'kelas' => 'XII MM 1',
                'tahun_lulus' => 2024,
            ],
            [
                'nisn' => '0012345682',
                'nis' => '2024005',
                'tanggal_lahir' => '2006-07-18',
                'jenis_kelamin' => 'L',
                'alamat' => 'Jl. Pemuda No. 90, Surabaya',
                'telepon' => '081234567894',
                'jurusan' => 'RPL',
                'kelas' => 'XII RPL 2',
                'tahun_lulus' => 2024,
            ],
        ];

        foreach ($siswaUsers as $index => $user) {
            SiswaProfile::create([
                'user_id' => $user->id,
                ...$profiles[$index],
                'status_verifikasi' => 'approved',
            ]);
        }
    }
}