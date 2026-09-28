<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Laporan extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::deleting(function (Laporan $laporan) {
            if ($laporan->bukti_like) {
                Storage::disk('public')->delete($laporan->bukti_like);
            }
            if ($laporan->bukti_komen) {
                Storage::disk('public')->delete($laporan->bukti_komen);
            }
            if ($laporan->bukti_share) {
                Storage::disk('public')->delete($laporan->bukti_share);
            }
        });
    }

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
