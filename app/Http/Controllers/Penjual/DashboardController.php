<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Menu;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $penjual = auth()->user();

        $stats = [
            'pesanan_hari_ini'   => Pesanan::where('penjual_id', $penjual->id)->whereDate('created_at', Carbon::today())->count(),
            'pesanan_menunggu'   => Pesanan::where('penjual_id', $penjual->id)->where('status', 'menunggu')->count(),
            'pesanan_diproses'   => Pesanan::where('penjual_id', $penjual->id)->whereIn('status', ['dikonfirmasi', 'diproses'])->count(),
            'pendapatan_hari_ini'=> Pesanan::where('penjual_id', $penjual->id)->whereDate('created_at', Carbon::today())->where('status', 'selesai')->sum('total_harga'),
            'total_menu'         => Menu::where('penjual_id', $penjual->id)->count(),
            'menu_habis'         => Menu::where('penjual_id', $penjual->id)->where('stok', 0)->count(),
        ];

        $pesanan_aktif = Pesanan::where('penjual_id', $penjual->id)
            ->whereIn('status', ['menunggu', 'dikonfirmasi', 'diproses', 'siap'])
            ->with(['siswa', 'details.menu'])
            ->orderBy('created_at', 'desc')
            ->get();

        $menu_populer = Menu::where('penjual_id', $penjual->id)
            ->orderBy('terjual', 'desc')
            ->limit(5)
            ->get();

        return view('penjual.dashboard', compact('stats', 'pesanan_aktif', 'menu_populer'));
    }
}
