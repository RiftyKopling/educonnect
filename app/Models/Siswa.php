<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    // Menegaskan nama tabel di database adalah 'siswa'
    protected $table = 'siswa';

    // Pengaturan Primary Key Kustom menggunakan NISN (String)
    protected $primaryKey = 'nisn';
    public $incrementing = false; 
    protected $keyType = 'string';

    // Kolom yang boleh diisi massal saat CRUD
    protected $fillable = [
        'nisn', 
        'nama_lengkap', 
        'jenis_kelamin', 
        'tempat_lahir', 
        'tanggal_lahir', 
        'alamat', 
        'kelas_id', 
        'orang_tua_id', 
        'foto_profil', 
        'status'
    ];

    /**
     * Relasi: Banyak Siswa berada di dalam Satu Kelas
     */
    public function kelas() 
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    /**
     * Relasi: Banyak Siswa terhubung ke Satu Orang Tua (User)
     */
    public function orangTua() 
    {
        return $this->belongsTo(User::class, 'orang_tua_id');
    }
}