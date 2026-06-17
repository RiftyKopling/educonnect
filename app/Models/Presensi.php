<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    protected $table = 'presensi';
    
    // 1. PERBAIKAN: Ubah 'siswa_id' menjadi 'siswa_nisn' di dalam array fillable
    protected $fillable = ['tanggal', 'siswa_nisn', 'kelas_id', 'mapel_id', 'guru_id', 'status', 'catatan'];

    // 2. PERBAIKAN: Gunakan 'siswa_nisn' sebagai foreign key
    public function siswa() { 
        return $this->belongsTo(Siswa::class, 'siswa_nisn', 'nisn'); 
    }
    
    public function kelas() { 
        return $this->belongsTo(Kelas::class); 
    }
    
    // Catatan: Pastikan nama model untuk mata pelajaran di aplikasi Anda memang "Mapel"
    public function mapel() { 
        return $this->belongsTo(Mapel::class, 'mapel_id'); 
    }
    
    public function guru() { 
        return $this->belongsTo(User::class, 'guru_id'); 
    }
}