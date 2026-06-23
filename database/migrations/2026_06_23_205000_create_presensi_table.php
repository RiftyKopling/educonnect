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
        Schema::create('presensi', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            
            // Foreign keys
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('mapel_id')->constrained('mata_pelajaran')->onDelete('cascade');
            $table->foreignId('guru_id')->constrained('users')->onDelete('cascade');
            
            // Foreign key to siswa (nisn is string)
            $table->string('siswa_nisn', 10);
            $table->foreign('siswa_nisn')->references('nisn')->on('siswa')->onDelete('cascade');
            
            // Enum for status: H, S, I, A, D
            $table->enum('status', ['H', 'S', 'I', 'A', 'D'])->default('H');
            
            // Catatan
            $table->string('catatan')->nullable();

            $table->timestamps();

            // Unique constraint to prevent duplicate attendance entry for same student, class, mapel, on same date
            $table->unique(['tanggal', 'kelas_id', 'mapel_id', 'siswa_nisn'], 'unique_presensi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi');
    }
};
