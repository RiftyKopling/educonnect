<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatatanPelanggaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'siswa_nisn',
        'pelanggaran_id',
        'keterangan',
        'guru_bk_id',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_nisn', 'nisn');
    }

    public function pelanggaran()
    {
        return $this->belongsTo(Pelanggaran::class);
    }

    public function guruBk()
    {
        return $this->belongsTo(User::class, 'guru_bk_id');
    }
}
