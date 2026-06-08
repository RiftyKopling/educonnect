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
     * Atribut yang dapat diisi secara massal.
     * Pastikan role_id disertakan agar dapat disimpan.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    /**
     * Atribut yang disembunyikan untuk serialisasi array/JSON.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Tipe casting data (konversi otomatis).
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi ke model Role: Pengguna ini memiliki satu Peran (Role).
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Helper untuk mengecek apakah user memiliki role tertentu berdasarkan slug.
     * Dapat digunakan di Middleware atau Blade view.
     */
    public function hasRole(string $roleSlug): bool
    {
        return $this->role !== null && $this->role->slug === $roleSlug;
    }
}