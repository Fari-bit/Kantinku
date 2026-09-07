<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    protected $table = 'ulasan';

    protected $fillable = [
        'siswa_id', 'pesanan_id', 'penjual_id', 'rating', 'komentar',
    ];

    public function siswa()
    {
        // withTrashed(): ulasan tetap menampilkan nama siswa meskipun
        // akunnya sudah dipindah ke Recycle Bin.
        return $this->belongsTo(User::class, 'siswa_id')->withTrashed();
    }

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    public function penjual()
    {
        return $this->belongsTo(User::class, 'penjual_id')->withTrashed();
    }

    public function getStarAttribute(): string
    {
        return str_repeat('⭐', $this->rating);
    }
}
