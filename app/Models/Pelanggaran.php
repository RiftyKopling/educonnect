<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_pelanggaran',
        'kategori',
        'deskripsi',
    ];

    public function catatanPelanggarans()
    {
        return $this->hasMany(CatatanPelanggaran::class);
    }
}
