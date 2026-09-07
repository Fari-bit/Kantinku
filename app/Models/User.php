<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password', 'role',
        'phone', 'avatar', 'kelas', 'nis', 'status',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPenjual(): bool
    {
        return $this->role === 'penjual';
    }

    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }

    public function penjualProfile()
    {
        return $this->hasOne(PenjualProfile::class);
    }

    public function menus()
    {
        return $this->hasMany(Menu::class, 'penjual_id');
    }

    public function pesananSebagaiSiswa()
    {
        return $this->hasMany(Pesanan::class, 'siswa_id');
    }

    public function pesananSebagaiPenjual()
    {
        return $this->hasMany(Pesanan::class, 'penjual_id');
    }

    public function ulasan()
    {
        return $this->hasMany(Ulasan::class, 'siswa_id');
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=FF6B35&color=fff&size=128';
    }
}
