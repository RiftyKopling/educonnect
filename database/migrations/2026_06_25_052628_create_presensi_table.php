<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presensi', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('mapel_id')->constrained('mata_pelajaran')->onDelete('cascade');
            $table->foreignId('guru_id')->constrained('users')->onDelete('cascade');
            $table->string('siswa_nisn', 10);
            $table->foreign('siswa_nisn')->references('nisn')->on('siswa')->onDelete('cascade');
            $table->enum('status', ['H', 'S', 'I', 'A', 'D'])->default('H');
            $table->string('catatan')->nullable();
            $table->timestamps();
            $table->unique(['tanggal', 'kelas_id', 'mapel_id', 'siswa_nisn'], 'unique_presensi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presensi');
    }
};