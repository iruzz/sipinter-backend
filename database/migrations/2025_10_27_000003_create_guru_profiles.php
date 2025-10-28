<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guru_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nip', 20)->unique();
            $table->string('mata_pelajaran', 100);
            $table->string('telepon', 15);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guru_profiles');
    }
};