<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuruProfile extends Model
{
    use HasFactory;

    protected $table = 'guru_profiles';

    protected $fillable = [
        'user_id',
        'nip',
        'mata_pelajaran',
        'telepon',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Penempatan (sebagai pembimbing)
    public function penempatanMagang()
    {
        return $this->hasMany(PenempatanMagang::class, 'guru_pembimbing_id');
    }
}