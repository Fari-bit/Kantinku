<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::where('penjual_id', auth()->id())
            ->orderBy('kategori')
            ->orderBy('nama')
            ->paginate(12);

        return view('penjual.menu.index', compact('menus'));
    }

    public function create()
    {
        return view('penjual.menu.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga'     => 'required|numeric|min:0',
            'kategori'  => 'required|in:makanan,minuman,snack',
            'stok'      => 'required|integer|min:0',
            'foto'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('menus', 'public');
        }

        Menu::create([
            'penjual_id' => auth()->id(),
            'nama'       => $request->nama,
            'deskripsi'  => $request->deskripsi,
            'harga'      => $request->harga,
            'kategori'   => $request->kategori,
            'stok'       => $request->stok,
            'tersedia'   => $request->boolean('tersedia', true),
            'foto'       => $fotoPath,
        ]);

        return redirect()->route('penjual.menu.index')
            ->with('success', 'Menu berhasil ditambahkan!');
    }

    public function edit(Menu $menu)
    {
        $this->authorize('update', $menu);
        return view('penjual.menu.edit', compact('menu'));
    }

    public function update(Request $request, Menu $menu)
    {
        $this->authorize('update', $menu);

        $request->validate([
            'nama'      => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga'     => 'required|numeric|min:0',
            'kategori'  => 'required|in:makanan,minuman,snack',
            'stok'      => 'required|integer|min:0',
            'foto'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only(['nama', 'deskripsi', 'harga', 'kategori', 'stok']);
        $data['tersedia'] = $request->boolean('tersedia');

        if ($request->hasFile('foto')) {
            if ($menu->foto) {
                Storage::disk('public')->delete($menu->foto);
            }
            $data['foto'] = $request->file('foto')->store('menus', 'public');
        }

        $menu->update($data);

        return redirect()->route('penjual.menu.index')
            ->with('success', 'Menu berhasil diperbarui!');
    }

    public function destroy(Menu $menu)
    {
        $this->authorize('delete', $menu);
        if ($menu->foto) {
            Storage::disk('public')->delete($menu->foto);
        }
        $menu->delete();
        return redirect()->route('penjual.menu.index')
            ->with('success', 'Menu berhasil dihapus!');
    }

    public function toggleTersedia(Menu $menu)
    {
        $this->authorize('update', $menu);
        $menu->update(['tersedia' => !$menu->tersedia]);
        return response()->json(['tersedia' => $menu->tersedia]);
    }
}
