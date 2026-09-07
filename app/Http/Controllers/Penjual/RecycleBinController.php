<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Support\Facades\Storage;

class RecycleBinController extends Controller
{
    /**
     * Menampilkan daftar menu yang sudah dihapus (soft delete) milik
     * penjual yang sedang login.
     */
    public function index()
    {
        $menus = Menu::onlyTrashed()
            ->where('penjual_id', auth()->id())
            ->orderBy('deleted_at', 'desc')
            ->paginate(12);

        return view('penjual.recycle-bin.index', compact('menus'));
    }

    /**
     * Memulihkan menu dari recycle bin.
     */
    public function restore($id)
    {
        $menu = Menu::onlyTrashed()
            ->where('penjual_id', auth()->id())
            ->findOrFail($id);

        $menu->restore();

        return back()->with('success', 'Menu "' . $menu->nama . '" berhasil dipulihkan!');
    }

    /**
     * Menghapus menu secara permanen (tidak bisa dipulihkan lagi).
     */
    public function forceDelete($id)
    {
        $menu = Menu::onlyTrashed()
            ->where('penjual_id', auth()->id())
            ->findOrFail($id);

        if ($menu->foto) {
            Storage::disk('public')->delete($menu->foto);
        }

        $nama = $menu->nama;
        $menu->forceDelete();

        return back()->with('success', 'Menu "' . $nama . '" dihapus permanen.');
    }

    /**
     * Mengosongkan seluruh recycle bin milik penjual ini sekaligus.
     */
    public function empty()
    {
        $menus = Menu::onlyTrashed()->where('penjual_id', auth()->id())->get();

        foreach ($menus as $menu) {
            if ($menu->foto) {
                Storage::disk('public')->delete($menu->foto);
            }
            $menu->forceDelete();
        }

        return back()->with('success', 'Recycle Bin berhasil dikosongkan.');
    }
}
