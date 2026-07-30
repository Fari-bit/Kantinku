<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /* ── Helper: build query berdasarkan filter ── */
    private function buildQuery(Request $request)
    {
        $periode = $request->get('periode', 'hari_ini');
        $query   = Pesanan::with(['siswa', 'penjual.penjualProfile', 'details']);

        switch ($periode) {
            case 'hari_ini':
                $query->whereDate('created_at', Carbon::today());
                $label = 'Hari Ini (' . Carbon::today()->format('d M Y') . ')';
                break;
            case 'minggu_ini':
                $query->whereBetween('created_at', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek(),
                ]);
                $label = 'Minggu Ini';
                break;
            case 'bulan_ini':
                $query->whereMonth('created_at', Carbon::now()->month)
                      ->whereYear('created_at', Carbon::now()->year);
                $label = 'Bulan ' . Carbon::now()->translatedFormat('F Y');
                break;
            case 'custom':
                $from = $request->get('dari', Carbon::today()->format('Y-m-d'));
                $to   = $request->get('sampai', Carbon::today()->format('Y-m-d'));
                $query->whereBetween('created_at', [
                    Carbon::parse($from)->startOfDay(),
                    Carbon::parse($to)->endOfDay(),
                ]);
                $label = Carbon::parse($from)->format('d M Y') . ' s/d ' . Carbon::parse($to)->format('d M Y');
                break;
            default:
                $query->whereDate('created_at', Carbon::today());
                $label = 'Hari Ini';
        }

        return [$query, $label];
    }

    /* ── Halaman utama laporan ── */
    public function index(Request $request)
    {
        [$query, $label] = $this->buildQuery($request);

        $pesanan = (clone $query)->orderBy('created_at', 'desc')->paginate(10)->appends($request->query());

        $ringkasan = [
            'total_pesanan'    => (clone $query)->count(),
            'total_pendapatan' => (clone $query)->where('status', 'selesai')->sum('total_harga'),
            'pesanan_selesai'  => (clone $query)->where('status', 'selesai')->count(),
            'pesanan_batal'    => (clone $query)->where('status', 'dibatalkan')->count(),
        ];

        $chartQuery = Pesanan::selectRaw('DATE(created_at) as tanggal, COUNT(*) as total, SUM(total_harga) as pendapatan')
            ->whereMonth('created_at', Carbon::now()->month)
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        return view('admin.laporan.index', compact('pesanan', 'ringkasan', 'label', 'chartQuery'));
    }

    /* ── Export Excel ── */
    public function exportExcel(Request $request)
    {
        [$query, $label] = $this->buildQuery($request);
        $pesanan = (clone $query)->orderBy('created_at', 'desc')->get();

        $ringkasan = [
            'total_pesanan'    => (clone $query)->count(),
            'total_pendapatan' => (clone $query)->where('status', 'selesai')->sum('total_harga'),
            'pesanan_selesai'  => (clone $query)->where('status', 'selesai')->count(),
            'pesanan_batal'    => (clone $query)->where('status', 'dibatalkan')->count(),
        ];

        $filename = 'Laporan-KantinKu-' . now()->format('d-m-Y') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($pesanan, $ringkasan, $label) {
            $file = fopen('php://output', 'w');

            // BOM untuk Excel agar tidak encoding rusak
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header info
            fputcsv($file, ['LAPORAN TRANSAKSI KANTINKU']);
            fputcsv($file, ['Periode', $label]);
            fputcsv($file, ['Digenerate', now()->format('d M Y, H:i')]);
            fputcsv($file, []);

            // Ringkasan
            fputcsv($file, ['RINGKASAN']);
            fputcsv($file, ['Total Pesanan',    $ringkasan['total_pesanan']]);
            fputcsv($file, ['Pesanan Selesai',  $ringkasan['pesanan_selesai']]);
            fputcsv($file, ['Pesanan Dibatalkan', $ringkasan['pesanan_batal']]);
            fputcsv($file, ['Total Pendapatan', 'Rp ' . number_format($ringkasan['total_pendapatan'], 0, ',', '.')]);
            fputcsv($file, []);

            // Header tabel
            fputcsv($file, [
                'No', 'Kode Pesanan', 'Tanggal', 'Waktu',
                'Nama Siswa', 'Kelas',
                'Warung', 'Item Pesanan',
                'Total (Rp)', 'Metode Bayar', 'Status Bayar', 'Status Pesanan',
            ]);

            // Data
            foreach ($pesanan as $no => $p) {
                $items = $p->details->map(fn($d) => $d->nama_menu . ' x' . $d->jumlah)->implode('; ');
                fputcsv($file, [
                    $no + 1,
                    $p->kode_pesanan,
                    $p->created_at->format('d/m/Y'),
                    $p->created_at->format('H:i'),
                    $p->siswa->name,
                    $p->siswa->kelas ?? '-',
                    $p->penjual->penjualProfile?->nama_warung ?? $p->penjual->name,
                    $items,
                    number_format($p->total_harga, 0, ',', '.'),
                    $p->metode_pembayaran === 'tunai' ? 'Tunai' : 'Transfer',
                    $p->status_pembayaran === 'sudah_bayar' ? 'Lunas' : 'Belum Bayar',
                    $p->status_badge['label'],
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /* ── Export PDF (HTML print) ── */
    public function exportPdf(Request $request)
    {
        [$query, $label] = $this->buildQuery($request);
        $pesanan = (clone $query)->orderBy('created_at', 'desc')->get();

        $ringkasan = [
            'total_pesanan'    => (clone $query)->count(),
            'total_pendapatan' => (clone $query)->where('status', 'selesai')->sum('total_harga'),
            'pesanan_selesai'  => (clone $query)->where('status', 'selesai')->count(),
            'pesanan_batal'    => (clone $query)->where('status', 'dibatalkan')->count(),
        ];

        return view('admin.laporan.pdf', compact('pesanan', 'ringkasan', 'label'));
    }
}
