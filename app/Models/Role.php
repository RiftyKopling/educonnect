<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     */
    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Relasi ke model User: Satu peran (Role) dapat dimiliki oleh banyak Pengguna (Users).
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}