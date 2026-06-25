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
        Schema::create('catatan_pelanggarans', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('siswa_nisn');
            $table->foreignId('pelanggaran_id')->constrained('pelanggarans')->onDelete('cascade');
            $table->text('keterangan')->nullable();
            $table->foreignId('guru_bk_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->foreign('siswa_nisn')->references('nisn')->on('siswa')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catatan_pelanggarans');
    }
};
