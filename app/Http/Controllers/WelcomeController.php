<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Menu;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        $penjual = User::where('role', 'penjual')
            ->where('status', 'active')
            ->with('penjualProfile')
            ->withCount('menus')
            ->get();

        $menu_populer = Menu::tersedia()
            ->with('penjual.penjualProfile')
            ->orderBy('terjual', 'desc')
            ->limit(6)
            ->get();

        return view('welcome', compact('penjual', 'menu_populer'));
    }
}
