<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory; // Tambahkan trait ini jika ingin menggunakan seeder/factory nanti

    protected $table = 'pengumuman';
    protected $fillable = ['user_id', 'judul', 'konten', 'target_type', 'kelas_id'];

    public function user()
    {
        // withDefault() akan mencegah error jika user_id bernilai null/terhapus
        return $this->belongsTo(User::class, 'user_id')->withDefault([
            'name' => 'Eks Komunitas Sekolah / Sistem'
        ]);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}
