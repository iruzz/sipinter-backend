<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\GuruProfile;

class GuruProfileSeeder extends Seeder
{
    public function run(): void
    {
        $guruUsers = User::where('role', 'guru')->get();

        $profiles = [
            [
                'nip' => '197001011998021001',
                'mata_pelajaran' => 'Pemrograman Web',
                'telepon' => '081234560011',
            ],
            [
                'nip' => '197505151999032002',
                'mata_pelajaran' => 'Basis Data',
                'telepon' => '081234560012',
            ],
            [
                'nip' => '198008082000121003',
                'mata_pelajaran' => 'Jaringan Komputer',
                'telepon' => '081234560013',
            ],
        ];

        foreach ($guruUsers as $index => $user) {
            GuruProfile::create([
                'user_id' => $user->id,
                ...$profiles[$index],
            ]);
        }
    }
}