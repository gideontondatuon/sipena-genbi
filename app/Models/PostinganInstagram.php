<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostinganInstagram extends Model
{
    use HasFactory;

    protected $fillable = [
        'akun_instagram_id',
        'media_id',
        'caption',
        'thumbnail_url',
        'permalink',
        'tanggal_postingan',
        'status',
    ];

    protected $casts = [
        'tanggal_postingan' => 'date',
    ];

    public function akunInstagram()
    {
        return $this->belongsTo(AkunInstagram::class);
    }
}
