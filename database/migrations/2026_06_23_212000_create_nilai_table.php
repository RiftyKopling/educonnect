<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('nilai', function (Blueprint $table) {
            $table->id();
            $table->enum('semester', ['Ganjil', 'Genap']);
            $table->string('tahun_ajaran'); // contoh: '2025/2026'
            
            // Foreign keys
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('mapel_id')->constrained('mata_pelajaran')->onDelete('cascade');
            $table->foreignId('guru_id')->constrained('users')->onDelete('cascade');
            
            // Foreign key to siswa (nisn is string)
            $table->string('siswa_nisn', 10);
            $table->foreign('siswa_nisn')->references('nisn')->on('siswa')->onDelete('cascade');
            
            // Kolom nilai numerik (0-100) mendatar
            $table->integer('tugas')->default(0);
            $table->integer('kuis')->default(0);
            $table->integer('uts')->default(0);
            $table->integer('uas')->default(0);
            
            // Catatan
            $table->string('catatan')->nullable();

            $table->timestamps();

            // Unique constraint: 1 siswa hanya punya 1 baris nilai per mapel per semester & tahun ajaran
            $table->unique(['siswa_nisn', 'mapel_id', 'kelas_id', 'semester', 'tahun_ajaran'], 'unique_nilai_horizontal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai');
    }
};
