<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MateriAjar extends Model
{
    use HasFactory;

    protected $table = 'materi_ajars';

    protected $fillable = [
        'judul',
        'deskripsi',
        'tipe_materi',
        'file_path',
        'url_link',
        'guru_id',
        'mapel_id',
        'kelas_id',
    ];

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'mapel_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}
