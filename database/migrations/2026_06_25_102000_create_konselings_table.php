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
        Schema::create('konselings', function (Blueprint $table) {
            $table->id();
            $table->dateTime('tanggal');
            $table->string('siswa_nisn');
            $table->foreignId('guru_bk_id')->constrained('users')->onDelete('cascade');
            $table->string('jenis_layanan'); // e.g., Konseling Pribadi, Bimbingan Kelompok
            $table->string('topik');
            $table->text('deskripsi_kasus')->nullable();
            $table->text('tindak_lanjut')->nullable();
            $table->enum('status', ['Terjadwal', 'Selesai', 'Batal']);
            $table->timestamps();

            $table->foreign('siswa_nisn')->references('nisn')->on('siswa')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konselings');
    }
};
