<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerusahaanProfile extends Model
{
    use HasFactory;

    protected $table = 'perusahaan_profiles'; // pastikan sesuai di DB

    protected $fillable = [
        'user_id',
        'nama_perusahaan',
        'alamat',
        'deskripsi',
        'no_telp',
        'email_perusahaan',
        'logo',
        'website',
    ];

    /**
     * Relasi ke user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke lowongan
     */
    public function lowongans()
    {
        return $this->hasMany(Lowongan::class, 'perusahaan_id');
    }
}
