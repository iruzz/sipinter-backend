<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiswaProfile extends Model
{
    use HasFactory;

    protected $table = 'siswa_profiles';

    protected $fillable = [
        'user_id',
        'nisn',
        'nis',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'telepon',
        'jurusan',
        'kelas',
        'tahun_lulus',
        'foto_profil',
        'cv_file',
        'status_verifikasi',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tahun_lulus' => 'integer',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Lamaran Magang
    public function lamaranMagang()
    {
        return $this->hasMany(LamaranMagang::class, 'siswa_id');
    }

    // Relasi ke Penempatan Magang
    public function penempatanMagang()
    {
        return $this->hasMany(PenempatanMagang::class, 'siswa_id');
    }
}