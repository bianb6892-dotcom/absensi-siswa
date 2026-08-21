<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;

    protected $table = 'nilai';

    protected $fillable = [
        'user_id',
        'kelas',
        'tahun_ajaran',
        'periode',
        'mata_pelajaran',
        'nilai',
    ];

    protected $casts = [
        'nilai' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getPredikatAttribute()
    {
        $n = (float) $this->nilai;

        if ($n >= 90) {
            return ['label' => 'A', 'keterangan' => 'Sangat Baik'];
        }
        if ($n >= 80) {
            return ['label' => 'B', 'keterangan' => 'Baik'];
        }
        if ($n >= 70) {
            return ['label' => 'C', 'keterangan' => 'Cukup'];
        }
        if ($n >= 60) {
            return ['label' => 'D', 'keterangan' => 'Kurang'];
        }

        return ['label' => 'E', 'keterangan' => 'Sangat Kurang'];
    }
}
