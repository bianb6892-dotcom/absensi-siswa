<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Tambahkan di bagian fillable
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'kelas',
        'ortu_id',
        'nis',
    ];

    // Tambahkan relasi
    public function orangTua()
    {
        return $this->belongsTo(OrangTua::class, 'ortu_id');
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }

    public function nilais()
    {
        return $this->hasMany(Nilai::class);
    }

    // Di bagian relasi, tambahkan method untuk cek role
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isGuru()
    {
        return $this->role === 'guru';
    }

    public function isSiswa()
    {
        return $this->role === 'siswa';
    }

    public function isOrtu()
    {
        return $this->role === 'ortu';
    }
}
