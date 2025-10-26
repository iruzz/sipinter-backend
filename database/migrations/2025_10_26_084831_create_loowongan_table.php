<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lowongan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perusahaan_id')->constrained('perusahaan_profiles')->onDelete('cascade');
            $table->enum('tipe_lowongan', ['magang', 'kerja'])->default('magang'); // <--- baru
            $table->string('judul');
            $table->text('deskripsi');
            $table->text('persyaratan');
            $table->integer('jumlah_posisi')->default(1);
            $table->string('lokasi');
            $table->integer('durasi_magang')->nullable(); // hanya untuk magang
            $table->decimal('gaji', 10, 2)->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->enum('status', ['draft', 'aktif', 'nonaktif', 'ditutup'])->default('draft');
            $table->enum('status_approval', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lowongan');
    }
};
