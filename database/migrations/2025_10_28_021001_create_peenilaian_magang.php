<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penilaian_magang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penempatan_id')->constrained('penempatan_magang')->onDelete('cascade');
            $table->enum('penilai_type', ['perusahaan', 'guru']);
            $table->foreignId('penilai_id')->constrained('users')->onDelete('cascade');
            $table->integer('nilai_disiplin')->default(0)->comment('1-100');
            $table->integer('nilai_kerjasama')->default(0)->comment('1-100');
            $table->integer('nilai_inisiatif')->default(0)->comment('1-100');
            $table->integer('nilai_teknis')->default(0)->comment('1-100');
            $table->integer('nilai_komunikasi')->default(0)->comment('1-100');
            $table->decimal('nilai_akhir', 5, 2)->default(0)->comment('average');
            $table->text('komentar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian_magang');
    }
};