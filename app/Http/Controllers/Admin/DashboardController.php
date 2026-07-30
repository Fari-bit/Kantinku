<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\PendaftaranPenjual;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_penjual'  => User::where('role', 'penjual')->where('status', 'active')->count(),
            'total_siswa'    => User::where('role', 'siswa')->where('status', 'active')->count(),
            'total_menu'     => Menu::count(),
            'total_pesanan'  => Pesanan::whereDate('created_at', Carbon::today())->count(),
            'pendaftaran_pending' => PendaftaranPenjual::where('status', 'pending')->count(),
            'pendapatan_hari_ini' => Pesanan::whereDate('created_at', Carbon::today())->where('status', 'selesai')->sum('total_harga'),
        ];

        $pesanan_terbaru = Pesanan::with(['siswa', 'penjual', 'details'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $penjual_aktif = User::where('role', 'penjual')
            ->where('status', 'active')
            ->with('penjualProfile')
            ->withCount('menus')
            ->get();

        $pendaftaran_terbaru = PendaftaranPenjual::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'pesanan_terbaru', 'penjual_aktif', 'pendaftaran_terbaru'));
    }
}
