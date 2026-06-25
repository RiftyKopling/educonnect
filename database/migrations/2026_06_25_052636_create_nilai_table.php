<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nilai', function (Blueprint $table) {
            $table->id();
            $table->enum('semester', ['Ganjil', 'Genap']);
            $table->string('tahun_ajaran');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('mapel_id')->constrained('mata_pelajaran')->onDelete('cascade');
            $table->foreignId('guru_id')->constrained('users')->onDelete('cascade');
            $table->string('siswa_nisn', 10);
            $table->foreign('siswa_nisn')->references('nisn')->on('siswa')->onDelete('cascade');
            $table->integer('tugas')->default(0);
            $table->integer('kuis')->default(0);
            $table->integer('uts')->default(0);
            $table->integer('uas')->default(0);
            $table->string('catatan')->nullable();
            $table->timestamps();
            $table->unique(['siswa_nisn', 'mapel_id', 'kelas_id', 'semester', 'tahun_ajaran'], 'unique_nilai_horizontal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilai');
    }
};