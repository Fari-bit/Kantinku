<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PesananController extends Controller
{
    /* ── Keranjang ─────────────────────────────────────────────── */

    public function keranjang()
    {
        $cart  = session()->get('cart', []);
        $items = [];
        $total = 0;

        foreach ($cart as $menuId => $item) {
            $menu = Menu::with('penjual.penjualProfile')->find($menuId);
            if ($menu) {
                $subtotal        = $menu->harga * $item['jumlah'];
                $total          += $subtotal;
                $items[$menuId]  = [
                    'menu'     => $menu,
                    'jumlah'   => $item['jumlah'],
                    'catatan'  => $item['catatan'] ?? '',
                    'subtotal' => $subtotal,
                ];
            }
        }

        return view('siswa.pesanan.keranjang', compact('items', 'total'));
    }

    public function tambahKeranjang(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'jumlah'  => 'required|integer|min:1|max:20',
        ]);

        $menu = Menu::findOrFail($request->menu_id);

        if (!$menu->tersedia || $menu->stok < 1) {
            return response()->json(['error' => 'Menu tidak tersedia.'], 422);
        }

        $cart   = session()->get('cart', []);
        $menuId = $request->menu_id;

        $existingPenjualId = null;
        foreach ($cart as $id => $item) {
            $m = Menu::find($id);
            if ($m) { $existingPenjualId = $m->penjual_id; break; }
        }

        if ($existingPenjualId && $existingPenjualId !== $menu->penjual_id) {
            return response()->json([
                'error'      => 'Keranjang sudah berisi menu dari penjual lain. Kosongkan terlebih dahulu.',
                'need_clear' => true,
            ], 422);
        }

        if (isset($cart[$menuId])) {
            $cart[$menuId]['jumlah'] += $request->jumlah;
        } else {
            $cart[$menuId] = ['jumlah' => $request->jumlah, 'catatan' => ''];
        }

        session()->put('cart', $cart);
        $totalItem = array_sum(array_column($cart, 'jumlah'));

        return response()->json(['success' => true, 'message' => $menu->nama . ' ditambahkan!', 'totalItem' => $totalItem]);
    }

    public function updateKeranjang(Request $request)
    {
        $request->validate(['menu_id' => 'required', 'jumlah' => 'required|integer|min:0']);
        $cart = session()->get('cart', []);
        if ($request->jumlah == 0) {
            unset($cart[$request->menu_id]);
        } else {
            $cart[$request->menu_id]['jumlah'] = $request->jumlah;
        }
        session()->put('cart', $cart);
        return response()->json(['success' => true]);
    }

    public function hapusKeranjang(Request $request)
    {
        $cart = session()->get('cart', []);
        unset($cart[$request->menu_id]);
        session()->put('cart', $cart);
        return response()->json(['success' => true]);
    }

    public function kosongkanKeranjang()
    {
        session()->forget('cart');
        return response()->json(['success' => true]);
    }

    /* ── Checkout ──────────────────────────────────────────────── */

    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('siswa.dashboard')->with('error', 'Keranjang kosong!');
        }

        $items   = [];
        $total   = 0;
        $penjual = null;

        foreach ($cart as $menuId => $item) {
            $menu = Menu::with('penjual.penjualProfile')->find($menuId);
            if ($menu) {
                $subtotal = $menu->harga * $item['jumlah'];
                $total   += $subtotal;
                $items[]  = compact('menu', 'item', 'subtotal');
                $penjual  = $menu->penjual;
            }
        }

        return view('siswa.pesanan.checkout', compact('items', 'total', 'penjual'));
    }

    public function prosesPemesanan(Request $request)
    {
        $request->validate([
            'metode_pembayaran' => 'required|in:tunai,transfer',
            'catatan'           => 'nullable|string|max:500',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) return redirect()->route('siswa.dashboard');

        DB::beginTransaction();
        try {
            $totalHarga = 0;
            $penjualId  = null;
            $details    = [];

            foreach ($cart as $menuId => $item) {
                $menu = Menu::lockForUpdate()->find($menuId);
                if (!$menu || !$menu->tersedia || $menu->stok < $item['jumlah']) {
                    DB::rollBack();
                    return back()->with('error', 'Stok "' . ($menu->nama ?? '') . '" tidak mencukupi.');
                }
                $subtotal    = $menu->harga * $item['jumlah'];
                $totalHarga += $subtotal;
                $penjualId   = $menu->penjual_id;
                $details[]   = ['menu' => $menu, 'jumlah' => $item['jumlah'], 'catatan' => $item['catatan'] ?? '', 'subtotal' => $subtotal];
            }

            // Cek penjual terima transfer
            if ($request->metode_pembayaran === 'transfer') {
                $profile = \App\Models\PenjualProfile::where('user_id', $penjualId)->first();
                if (!$profile || !$profile->terima_transfer) {
                    DB::rollBack();
                    return back()->with('error', 'Penjual ini tidak menerima pembayaran transfer. Gunakan metode tunai.');
                }
            }

            $pesanan = Pesanan::create([
                'kode_pesanan'      => Pesanan::generateKode(),
                'siswa_id'          => auth()->id(),
                'penjual_id'        => $penjualId,
                'total_harga'       => $totalHarga,
                'status'            => 'menunggu',
                'catatan'           => $request->catatan,
                'metode_pembayaran' => $request->metode_pembayaran,
                'status_pembayaran' => 'belum_bayar',
            ]);

            foreach ($details as $d) {
                DetailPesanan::create([
                    'pesanan_id'   => $pesanan->id,
                    'menu_id'      => $d['menu']->id,
                    'nama_menu'    => $d['menu']->nama,
                    'harga_satuan' => $d['menu']->harga,
                    'jumlah'       => $d['jumlah'],
                    'subtotal'     => $d['subtotal'],
                    'catatan'      => $d['catatan'],
                ]);
                $d['menu']->decrement('stok', $d['jumlah']);
            }

            session()->forget('cart');
            DB::commit();

            return redirect()->route('siswa.pesanan.detail', $pesanan)
                ->with('success', 'Pesanan berhasil! Kode: ' . $pesanan->kode_pesanan);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    /* ── FIX #2: Upload bukti transfer ───────────────────────── */

    public function uploadBuktiTransfer(Request $request, Pesanan $pesanan)
    {
        abort_unless($pesanan->siswa_id === auth()->id(), 403);
        abort_unless($pesanan->metode_pembayaran === 'transfer', 422);

        $request->validate([
            'bukti_transfer' => 'required|image|mimes:jpg,jpeg,png|max:3072',
        ]);

        if ($pesanan->bukti_transfer) {
            Storage::disk('public')->delete($pesanan->bukti_transfer);
        }

        $path = $request->file('bukti_transfer')->store('bukti-transfer', 'public');

        $pesanan->update([
            'bukti_transfer'             => $path,
            'status_verifikasi_transfer' => 'menunggu',
        ]);

        return back()->with('success', 'Bukti transfer berhasil dikirim! Menunggu konfirmasi penjual.');
    }

    /* ── Riwayat & Detail ──────────────────────────────────────── */

    public function riwayat()
    {
        $pesanan = Pesanan::where('siswa_id', auth()->id())
            ->with(['penjual.penjualProfile', 'details', 'ulasan'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('siswa.pesanan.riwayat', compact('pesanan'));
    }

    public function detail(Pesanan $pesanan)
    {
        abort_unless($pesanan->siswa_id === auth()->id(), 403);
        $pesanan->load(['penjual.penjualProfile', 'details.menu', 'ulasan']);
        return view('siswa.pesanan.detail', compact('pesanan'));
    }

    public function batalkan(Pesanan $pesanan)
    {
        abort_unless($pesanan->siswa_id === auth()->id(), 403);
        abort_unless($pesanan->status === 'menunggu', 422);

        foreach ($pesanan->details as $detail) {
            $detail->menu?->increment('stok', $detail->jumlah);
        }
        $pesanan->update(['status' => 'dibatalkan']);
        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }

    /* ── FIX #5: Ulasan & Rating ───────────────────────────────── */

    public function buatUlasan(Request $request, Pesanan $pesanan)
    {
        abort_unless($pesanan->siswa_id === auth()->id(), 403);
        abort_unless($pesanan->status === 'selesai', 422);

        // Cek apakah sudah pernah memberi ulasan
        if ($pesanan->ulasan()->exists()) {
            return back()->with('error', 'Kamu sudah memberikan ulasan untuk pesanan ini.');
        }

        $request->validate([
            'rating'   => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:500',
        ]);

        Ulasan::create([
            'siswa_id'   => auth()->id(),
            'pesanan_id' => $pesanan->id,
            'penjual_id' => $pesanan->penjual_id,
            'rating'     => $request->rating,
            'komentar'   => $request->komentar,
        ]);

        // Update rata-rata rating penjual di tabel menus
        $avgRating = Ulasan::where('penjual_id', $pesanan->penjual_id)->avg('rating');
        \App\Models\Menu::where('penjual_id', $pesanan->penjual_id)
            ->update(['rating' => round($avgRating, 2)]);

        return back()->with('success', 'Ulasan berhasil dikirim! Terima kasih. ⭐');
    }
}
