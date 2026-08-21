<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasi';

    protected $fillable = [
        'ortu_id',
        'siswa_id',
        'judul',
        'pesan',
        'status',
        'jenis',
        'dikirim_at',
    ];

    protected $casts = [
        'dikirim_at' => 'datetime',
    ];

    public function orangTua()
    {
        return $this->belongsTo(OrangTua::class, 'ortu_id');
    }

    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }
}
