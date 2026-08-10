<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'akun_instagram_id',
        'target_harian_id',
        'tanggal_postingan',
        'link_postingan',
        'judul_postingan',
        'bukti_like',
        'bukti_komen',
        'bukti_share',
        'hash_like',
        'hash_komen',
        'hash_share',
        'keterangan',
        'status',
        'catatan_admin',
    ];

    protected $casts = [
        'tanggal_postingan' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function akunInstagram()
    {
        return $this->belongsTo(AkunInstagram::class);
    }

    public function targetHarian()
    {
        return $this->belongsTo(TargetHarian::class);
    }
}
