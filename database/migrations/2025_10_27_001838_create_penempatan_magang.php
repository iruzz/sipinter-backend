<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penempatan_magang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lamaran_id')->constrained('lamaran_magang')->onDelete('cascade');
            $table->foreignId('siswa_id')->constrained('siswa_profiles')->onDelete('cascade');
            $table->foreignId('perusahaan_id')->constrained('perusahaan_profiles')->onDelete('cascade');
            $table->foreignId('guru_pembimbing_id')->constrained('guru_profiles')->onDelete('cascade');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->enum('status', ['aktif', 'selesai', 'dibatalkan'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penempatan_magang');
    }
};