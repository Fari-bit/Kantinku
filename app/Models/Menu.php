<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'penjual_id', 'nama', 'deskripsi', 'harga',
        'foto', 'kategori', 'stok', 'tersedia',
        'terjual', 'rating', 'jumlah_rating',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'tersedia' => 'boolean',
        'rating' => 'decimal:2',
    ];

    public function penjual()
    {
        // withTrashed(): detail/riwayat menu tetap bisa menampilkan nama
        // penjual meskipun akunnya sudah dipindah ke Recycle Bin.
        return $this->belongsTo(User::class, 'penjual_id')->withTrashed();
    }

    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class);
    }

    public function ulasan()
    {
        return $this->hasMany(Ulasan::class);
    }

    public function getFotoUrlAttribute(): string
    {
        if ($this->foto) {
            return asset('storage/' . $this->foto);
        }
        return asset('images/default-menu.jpg');
    }

    public function getHargaFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    public function scopeTersedia($query)
    {
        // whereHas('penjual', ...withoutTrashed()) memastikan menu milik
        // penjual yang akunnya sudah dipindah ke Recycle Bin tidak lagi
        // muncul untuk dipesan siswa, meskipun relasi penjual() di atas
        // pakai withTrashed() (untuk keperluan riwayat).
        return $query->where('tersedia', true)
            ->where('stok', '>', 0)
            ->whereHas('penjual', fn ($q) => $q->withoutTrashed());
    }
}
