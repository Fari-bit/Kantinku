<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Ulasan;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        $query = Pesanan::where('penjual_id', auth()->id())
            ->with(['siswa', 'details.menu'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pesanan = $query->paginate(10)->withQueryString();
        return view('penjual.pesanan.index', compact('pesanan'));
    }

    public function show(Pesanan $pesanan)
    {
        abort_unless($pesanan->penjual_id === auth()->id(), 403);
        $pesanan->load(['siswa', 'details.menu', 'ulasan.siswa']);
        return view('penjual.pesanan.show', compact('pesanan'));
    }

    /* ── FIX #1: updateStatus sekarang selalu return JSON
       sehingga frontend bisa auto-refresh tanpa manual ── */
    public function updateStatus(Request $request, Pesanan $pesanan)
    {
        abort_unless($pesanan->penjual_id === auth()->id(), 403);

        $request->validate([
            'status' => 'required|in:dikonfirmasi,diproses,siap,selesai,dibatalkan',
        ]);

        // Cek jika transfer tapi belum diverifikasi
        if (
            $pesanan->metode_pembayaran === 'transfer'
            && $pesanan->status_verifikasi_transfer !== 'diterima'
            && $request->status === 'dikonfirmasi'
        ) {
            return response()->json([
                'success' => false,
                'error'   => 'Bukti transfer belum diverifikasi. Verifikasi pembayaran terlebih dahulu.',
            ], 422);
        }

        $pesanan->update(['status' => $request->status]);

        if ($request->status === 'selesai') {
            foreach ($pesanan->details as $detail) {
                $detail->menu?->increment('terjual', $detail->jumlah);
            }
        }

        if ($request->status === 'dibatalkan') {
            foreach ($pesanan->details as $detail) {
                $detail->menu?->increment('stok', $detail->jumlah);
            }
        }

        // Selalu return JSON — FIX #1
        return response()->json([
            'success'      => true,
            'status'       => $request->status,
            'status_label' => $pesanan->fresh()->status_badge['label'],
            'status_color' => $pesanan->fresh()->status_badge['color'],
        ]);
    }

    /* ── FIX #2: Verifikasi bukti transfer oleh penjual ── */
    public function verifikasiTransfer(Request $request, Pesanan $pesanan)
    {
        abort_unless($pesanan->penjual_id === auth()->id(), 403);

        $request->validate([
            'aksi'              => 'required|in:diterima,ditolak',
            'catatan_verifikasi'=> 'nullable|string|max:500',
        ]);

        $pesanan->update([
            'status_verifikasi_transfer' => $request->aksi,
            'catatan_verifikasi'         => $request->catatan_verifikasi,
            'status_pembayaran'          => $request->aksi === 'diterima' ? 'sudah_bayar' : 'belum_bayar',
        ]);

        $pesan = $request->aksi === 'diterima'
            ? 'Bukti transfer diterima! Pembayaran dikonfirmasi.'
            : 'Bukti transfer ditolak. Siswa akan diberitahu.';

        return back()->with('success', $pesan);
    }

    /* ── Ulasan dari sudut pandang penjual ── */
    public function ulasan()
    {
        $ulasan = Ulasan::where('penjual_id', auth()->id())
            ->with(['siswa', 'pesanan'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $rataRating = Ulasan::where('penjual_id', auth()->id())->avg('rating');

        return view('penjual.ulasan.index', compact('ulasan', 'rataRating'));
    }

    public function riwayat()
    {
        $pesanan = Pesanan::where('penjual_id', auth()->id())
            ->whereIn('status', ['selesai', 'dibatalkan'])
            ->with(['siswa', 'details'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('penjual.pesanan.riwayat', compact('pesanan'));
    }
}
