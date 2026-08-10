<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TargetHarian extends Model
{
    use HasFactory;

    protected $fillable = [
        'akun_instagram_id',
        'periode_id',
        'tanggal',
        'jumlah_target',
        'deadline',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'deadline' => 'datetime',
    ];

    public function akunInstagram()
    {
        return $this->belongsTo(AkunInstagram::class);
    }

    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }

    public function laporans()
    {
        return $this->hasMany(Laporan::class);
    }
}
