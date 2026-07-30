<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenjualProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'nama_warung', 'deskripsi',
        'foto_warung', 'nomor_stand',
        'is_open', 'jam_buka', 'jam_tutup',
        'nama_bank', 'nomor_rekening',
        'nama_pemilik_rekening', 'terima_transfer',
    ];

    protected $casts = [
        'is_open'          => 'boolean',
        'terima_transfer'  => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFotoWarungUrlAttribute(): string
    {
        return $this->foto_warung
            ? asset('storage/' . $this->foto_warung)
            : asset('images/default-warung.png');
    }

    public function getInfoRekeningAttribute(): string
    {
        if (!$this->terima_transfer || !$this->nama_bank) {
            return 'Tidak menerima transfer';
        }
        return $this->nama_bank . ' · ' . $this->nomor_rekening . ' · a/n ' . $this->nama_pemilik_rekening;
    }
}
