<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Menu;
use App\Models\Pesanan;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil semua penjual aktif beserta menu-menu mereka
        $penjual = User::where('role', 'penjual')
            ->where('status', 'active')
            ->with(['penjualProfile', 'menus' => function ($q) {
                $q->where('tersedia', true)->where('stok', '>', 0)->orderBy('terjual', 'desc');
            }])
            ->get()
            ->filter(fn($p) => $p->menus->isNotEmpty());

        // Rekomendasi (menu terlaris)
        $rekomendasi = Menu::tersedia()
            ->with('penjual.penjualProfile')
            ->orderBy('terjual', 'desc')
            ->orderBy('rating', 'desc')
            ->limit(8)
            ->get();

        // Pesanan aktif siswa
        $pesanan_aktif = Pesanan::where('siswa_id', auth()->id())
            ->whereIn('status', ['menunggu', 'dikonfirmasi', 'diproses', 'siap'])
            ->with(['penjual', 'details'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('siswa.dashboard', compact('penjual', 'rekomendasi', 'pesanan_aktif'));
    }
}
