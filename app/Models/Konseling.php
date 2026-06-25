<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konseling extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'siswa_nisn',
        'guru_bk_id',
        'jenis_layanan',
        'topik',
        'deskripsi_kasus',
        'tindak_lanjut',
        'status',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_nisn', 'nisn');
    }

    public function guruBk()
    {
        return $this->belongsTo(User::class, 'guru_bk_id');
    }
}
