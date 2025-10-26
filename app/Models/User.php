<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_verified',     // ← PASTIKAN INI ADA
        'is_active',       // ← Ini juga kalau pake
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_verified' => 'boolean',  // ← PASTIKAN INI ADA
        'is_active' => 'boolean',
    ];

    // Tambahkan di class User

public function siswaProfile()
{
    return $this->hasOne(SiswaProfile::class);
}

public function perusahaanProfile()
{
    return $this->hasOne(PerusahaanProfile::class);
}

public function guruProfile()
{
    return $this->hasOne(GuruProfile::class);
}
}