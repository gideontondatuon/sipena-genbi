<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AkunInstagram extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_akun',
        'username',
        'keterangan',
        'status',
    ];

    public function targetHarians()
    {
        return $this->hasMany(TargetHarian::class);
    }

    public function laporans()
    {
        return $this->hasMany(Laporan::class);
    }

    public function postinganInstagrams()
    {
        return $this->hasMany(PostinganInstagram::class);
    }
}
