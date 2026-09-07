<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PendaftaranPenjual extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran_penjual';

    protected $fillable = [
        'nama_lengkap', 'email', 'password', 'phone',
        'nama_warung', 'deskripsi_warung', 'jenis_makanan',
        'terima_transfer', 'nama_bank', 'nomor_rekening', 'nama_pemilik_rekening',
        'foto_ktp', 'foto_warung',
        'status', 'catatan_admin', 'reviewed_by', 'reviewed_at',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'reviewed_at'     => 'datetime',
        'terima_transfer' => 'boolean',
    ];

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by')->withTrashed();
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'pending'  => ['label' => 'Menunggu Review', 'color' => 'warning'],
            'approved' => ['label' => 'Disetujui',       'color' => 'success'],
            'rejected' => ['label' => 'Ditolak',         'color' => 'danger'],
            default    => ['label' => ucfirst($this->status), 'color' => 'secondary'],
        };
    }
}
