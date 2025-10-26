<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lowongan extends Model
{
    use HasFactory;

    protected $table = 'lowongan';

    protected $fillable = [
        'perusahaan_id',
        'tipe_lowongan',
        'judul',
        'deskripsi',
        'persyaratan',
        'jumlah_posisi',
        'lokasi',
        'durasi_magang',
        'gaji',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'status_approval',
        'catatan_admin',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'gaji' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationship
    public function perusahaan()
    {
        return $this->belongsTo(PerusahaanProfile::class, 'perusahaan_id');
    }

    // Accessor untuk nama perusahaan
    public function getNamaPerusahaanAttribute()
    {
        return $this->perusahaan ? $this->perusahaan->nama_perusahaan : null;
    }

    public function lamarans()
{
    return $this->hasMany(LamaranMagang::class, 'lowongan_id');
}
}