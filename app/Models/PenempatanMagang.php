<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenempatanMagang extends Model
{
    use HasFactory;

    protected $table = 'penempatan_magang';

    protected $fillable = [
        'lamaran_id',
        'siswa_id',
        'perusahaan_id',
        'guru_pembimbing_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    // Relasi ke Lamaran
    public function lamaran()
    {
        return $this->belongsTo(LamaranMagang::class, 'lamaran_id');
    }

    // Relasi ke Siswa
    public function siswa()
    {
        return $this->belongsTo(SiswaProfile::class, 'siswa_id');
    }

    // Relasi ke Perusahaan
    public function perusahaan()
    {
        return $this->belongsTo(PerusahaanProfile::class, 'perusahaan_id');
    }

    // Relasi ke Guru Pembimbing
    public function guruPembimbing()
    {
        return $this->belongsTo(GuruProfile::class, 'guru_pembimbing_id');
    }

    // Relasi ke Penilaian
    public function penilaianMagang()
    {
        return $this->hasMany(PenilaianMagang::class, 'penempatan_id');
    }

    // Relasi ke Laporan
    public function laporanMagang()
    {
        return $this->hasMany(LaporanMagang::class, 'penempatan_id');
    }
}