<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerusahaanProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_perusahaan',
        'bidang_usaha',
        'alamat',
        'kota',
        'provinsi',
        'telepon',
        'website',
        'deskripsi',
        'logo',
        'pic_name',
        'pic_jabatan',
        'pic_telepon',
        'pic_email',
        'status_verifikasi',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}