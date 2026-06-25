<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id', // Memastikan role_id bisa diisi saat CRUD nanti
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi ke tabel Role (Satu User memiliki Satu Role)
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Fungsi Helper untuk mengecek Role pengguna berdasarkan slug
     */
    public function hasRole(string $roleSlug): bool
    {
        return $this->role?->slug === $roleSlug;
    }

        // Relasi ke Siswa (Jika user adalah Orang Tua)
    public function anak()
    {
        return $this->hasMany(Siswa::class, 'orang_tua_id');
    }

    // Relasi ke Kelas (Jika user adalah Wali Kelas)
    public function kelasDiampu()
    {
        return $this->hasOne(Kelas::class, 'wali_kelas_id');
    }

    // Relasi ke Mata Pelajaran (Jika user adalah Guru)
    public function mapels()
    {
        return $this->belongsToMany(Mapel::class, 'mapel_user');
    }

    // Relasi ke Catatan Pelanggaran (Jika user adalah Guru BK)
    public function catatanPelanggaransDiinput()
    {
        return $this->hasMany(CatatanPelanggaran::class, 'guru_bk_id');
    }

    // Relasi ke Konseling (Jika user adalah Guru BK)
    public function konselingsDiampu()
    {
        return $this->hasMany(Konseling::class, 'guru_bk_id');
    }

    // Relasi ke Kelas (Jika user adalah Guru Mapel, banyak ke banyak)
    public function kelasDiajar()
    {
        return $this->belongsToMany(Kelas::class, 'kelas_user');
    }
}
