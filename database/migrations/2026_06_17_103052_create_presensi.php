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
            
            // 1. Buat kolomnya dulu, WAJIB sama persis: varchar(10)
            $table->string('siswa_nisn', 10); 
            
            // 2. Kaitkan kolom tersebut sebagai Foreign Key ke tabel siswa
            $table->foreign('siswa_nisn')->references('nisn')->on('siswa')->onDelete('cascade');
            
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('mapel_id')->constrained('mata_pelajaran')->onDelete('cascade');
            $table->foreignId('guru_id')->constrained('users')->onDelete('cascade');
            
            $table->enum('status', ['H', 'S', 'I', 'A', 'D']);
            $table->string('catatan')->nullable();
            $table->timestamps();
            
            // Index untuk optimasi query statistik
            $table->index(['tanggal', 'kelas_id', 'mapel_id']);
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
