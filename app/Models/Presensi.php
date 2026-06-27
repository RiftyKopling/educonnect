<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensi';

    protected $fillable = [
        'tanggal',
        'kelas_id',
        'mapel_id',
        'guru_id',
        'siswa_nisn',
        'status',
        'catatan',
    ];

    /**
     * Relasi ke Kelas
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    /**
     * Relasi ke Mapel
     */
    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'mapel_id');
    }

    /**
     * Relasi ke User (Guru Mapel)
     */
    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    /**
     * Relasi ke Siswa (menggunakan NISN sebagai foreign key)
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_nisn', 'nisn');
    }
}
