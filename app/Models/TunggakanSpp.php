<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TunggakanSpp extends Model
{
    use HasFactory;

    protected $table = 'tunggakan_spp';

    protected $fillable = [
        'user_id',
        'kelas',
        'bulan',
        'jumlah',
        'keterangan',
    ];

    protected $casts = [
        'bulan' => 'date',
        'jumlah' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getBulanLabelAttribute(): string
    {
        return $this->bulan->translatedFormat('F Y');
    }

    public function getJumlahRupiahAttribute(): string
    {
        return 'Rp '.number_format((float) $this->jumlah, 0, ',', '.');
    }
}
