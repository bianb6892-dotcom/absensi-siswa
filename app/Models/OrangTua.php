<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrangTua extends Model
{
    use HasFactory;

    protected $table = 'orang_tua';

    protected $fillable = [
        'user_id',
        'no_wa',
        'alamat',
        'pekerjaan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function anak()
    {
        return $this->hasMany(User::class, 'ortu_id');
    }

    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class, 'ortu_id');
    }
}
