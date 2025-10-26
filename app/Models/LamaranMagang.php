<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LamaranMagang extends Model
{
    use HasFactory;

    protected $table = 'lamaran_magang';

    protected $fillable = [
        'siswa_id',
        'lowongan_id',
        'status',
        'surat_lamaran',
        'cv_file',
        'portofolio_file',
        'catatan_siswa',
        'catatan_perusahaan',
        'tanggal_apply',
        'tanggal_interview',
    ];

    protected $casts = [
        'tanggal_apply' => 'date',
        'tanggal_interview' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function siswa()
    {
        return $this->belongsTo(SiswaProfile::class, 'siswa_id');
    }

    public function lowongan()
    {
        return $this->belongsTo(Lowongan::class, 'lowongan_id');
    }
}