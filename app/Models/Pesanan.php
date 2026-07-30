<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanan';

    protected $fillable = [
        'kode_pesanan', 'siswa_id', 'penjual_id',
        'total_harga', 'status', 'catatan',
        'metode_pembayaran', 'status_pembayaran',
        'bukti_transfer', 'status_verifikasi_transfer', 'catatan_verifikasi',
    ];

    protected $casts = [
        'total_harga' => 'decimal:2',
    ];

    /* ── Relationships ── */
    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    public function penjual()
    {
        return $this->belongsTo(User::class, 'penjual_id');
    }

    public function details()
    {
        return $this->hasMany(DetailPesanan::class);
    }

    public function ulasan()
    {
        return $this->hasOne(Ulasan::class);
    }

    /* ── Helpers ── */
    public static function generateKode(): string
    {
        do {
            $kode = 'KTN-' . strtoupper(substr(uniqid(), -6));
        } while (self::where('kode_pesanan', $kode)->exists());
        return $kode;
    }

    /* ── Accessors ── */
    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'menunggu'     => ['label' => 'Menunggu',     'color' => 'warning'],
            'dikonfirmasi' => ['label' => 'Dikonfirmasi', 'color' => 'info'],
            'diproses'     => ['label' => 'Diproses',     'color' => 'primary'],
            'siap'         => ['label' => 'Siap Diambil', 'color' => 'success'],
            'selesai'      => ['label' => 'Selesai',      'color' => 'secondary'],
            'dibatalkan'   => ['label' => 'Dibatalkan',   'color' => 'danger'],
            default        => ['label' => ucfirst($this->status), 'color' => 'secondary'],
        };
    }

    public function getTotalFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
    }

    public function getBuktiTransferUrlAttribute(): ?string
    {
        return $this->bukti_transfer
            ? asset('storage/' . $this->bukti_transfer)
            : null;
    }

    public function getVerifikasiTransferBadgeAttribute(): array
    {
        return match ($this->status_verifikasi_transfer) {
            'menunggu' => ['label' => 'Menunggu Verifikasi', 'color' => 'warning'],
            'diterima' => ['label' => 'Transfer Diterima',   'color' => 'success'],
            'ditolak'  => ['label' => 'Transfer Ditolak',    'color' => 'danger'],
            default    => ['label' => 'Belum Upload',        'color' => 'secondary'],
        };
    }

    public function sudahDiulasAttribute(): bool
    {
        return $this->ulasan()->exists();
    }
}
