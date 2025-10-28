<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lamaran_magang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa_profiles')->onDelete('cascade');
            $table->foreignId('lowongan_id')->constrained('lowongan')->onDelete('cascade');
            $table->enum('status', ['pending', 'diterima', 'ditolak', 'interview', 'proses'])->default('pending');
            $table->string('surat_lamaran')->nullable(); // path file
            $table->string('cv_file')->nullable(); // path file
            $table->string('portofolio_file')->nullable(); // path file
            $table->text('catatan_siswa')->nullable();
            $table->text('catatan_perusahaan')->nullable();
            $table->date('tanggal_apply');
            $table->dateTime('tanggal_interview')->nullable();
            $table->timestamps();

            // Index untuk performa query
            $table->index('siswa_id');
            $table->index('lowongan_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lamaran_magang');
    }
};