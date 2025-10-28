<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenilaianMagang extends Model
{
    use HasFactory;

    protected $table = 'penilaian_magang';

    protected $fillable = [
        'penempatan_id',
        'penilai_type',
        'penilai_id',
        'nilai_disiplin',
        'nilai_kerjasama',
        'nilai_inisiatif',
        'nilai_teknis',
        'nilai_komunikasi',
        'nilai_akhir',
        'komentar',
    ];

    protected $casts = [
        'nilai_disiplin' => 'integer',
        'nilai_kerjasama' => 'integer',
        'nilai_inisiatif' => 'integer',
        'nilai_teknis' => 'integer',
        'nilai_komunikasi' => 'integer',
        'nilai_akhir' => 'decimal:2',
    ];

    // Auto calculate nilai_akhir before save
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $total = $model->nilai_disiplin + 
                     $model->nilai_kerjasama + 
                     $model->nilai_inisiatif + 
                     $model->nilai_teknis + 
                     $model->nilai_komunikasi;
            $model->nilai_akhir = $total / 5;
        });
    }

    // Relasi ke Penempatan
    public function penempatan()
    {
        return $this->belongsTo(PenempatanMagang::class, 'penempatan_id');
    }

    // Relasi ke Penilai (User)
    public function penilai()
    {
        return $this->belongsTo(User::class, 'penilai_id');
    }
}