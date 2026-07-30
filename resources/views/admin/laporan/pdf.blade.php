<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan KantinKu — {{ $label }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #1A202C;
            padding: 24px;
            background: #fff;
        }

        /* ── Header ── */
        .report-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            border-bottom: 3px solid #FF6B35;
            padding-bottom: 14px;
            margin-bottom: 20px;
        }
        .brand {
            font-size: 22px;
            font-weight: 900;
            color: #1A202C;
        }
        .brand span { color: #FF6B35; }
        .report-title { font-size: 14px; font-weight: 700; color: #1A202C; margin-top: 4px; }
        .report-meta  { font-size: 11px; color: #718096; margin-top: 3px; }
        .header-right { text-align: right; }
        .periode-badge {
            display: inline-block;
            background: #FFF0EB;
            color: #FF6B35;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 20px;
            border: 1px solid #FFD4C2;
        }

        /* ── Ringkasan ── */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }
        .summary-card {
            border: 1px solid #E2E8F0;
            border-radius: 10px;
            padding: 12px;
            text-align: center;
        }
        .summary-card .s-label {
            font-size: 10px;
            color: #718096;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 6px;
        }
        .summary-card .s-value {
            font-size: 18px;
            font-weight: 900;
            color: #1A202C;
        }
        .summary-card.highlight { background: #FFF0EB; border-color: #FFD4C2; }
        .summary-card.highlight .s-value { color: #FF6B35; }

        /* ── Tabel ── */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        thead tr {
            background: #2D3748;
            color: #fff;
        }
        thead th {
            padding: 8px 10px;
            text-align: left;
            font-weight: 700;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .5px;
        }
        tbody tr { border-bottom: 1px solid #F0F0F0; }
        tbody tr:nth-child(even) { background: #FAFAFA; }
        tbody td { padding: 7px 10px; vertical-align: middle; }
        .kode { font-weight: 700; color: #FF6B35; }
        .badge {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 700;
        }
        .badge-selesai   { background: #F0FDF4; color: #166534; }
        .badge-batal     { background: #FFF2F2; color: #B91C1C; }
        .badge-menunggu  { background: #FEFCE8; color: #854D0E; }
        .badge-proses    { background: #EFF6FF; color: #1E40AF; }
        .badge-siap      { background: #F0FDF4; color: #166534; }
        .badge-default   { background: #F9FAFB; color: #374151; }

        /* ── Footer ── */
        .report-footer {
            margin-top: 20px;
            padding-top: 12px;
            border-top: 1px solid #E2E8F0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 10px;
            color: #A0AEC0;
        }

        /* ── Print ── */
        .no-print {
            margin-bottom: 16px;
            display: flex;
            gap: 10px;
        }
        .btn-print {
            background: #FF6B35;
            color: #fff;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
        }
        .btn-back {
            background: transparent;
            color: #718096;
            border: 1px solid #E2E8F0;
            padding: 8px 20px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
        }
        @media print {
            .no-print { display: none !important; }
            body { padding: 12px; }
        }
    </style>
</head>
<body>

{{-- Tombol Print --}}
<div class="no-print">
    <button class="btn-print" onclick="window.print()">🖨️ Cetak / Simpan PDF</button>
    <a href="{{ route('admin.laporan.index') }}" class="btn-back">← Kembali</a>
</div>

{{-- Header --}}
<div class="report-header">
    <div>
        <div class="brand">Kantin<span>Ku</span></div>
        <div class="report-title">Laporan Transaksi Kantin Sekolah</div>
        <div class="report-meta">Digenerate: {{ now()->format('d M Y, H:i') }} WIB</div>
    </div>
    <div class="header-right">
        <div class="periode-badge">📅 {{ $label }}</div>
        <div class="report-meta" style="margin-top:6px;">Total {{ $pesanan->count() }} transaksi</div>
    </div>
</div>

{{-- Ringkasan --}}
<div class="summary-grid">
    <div class="summary-card">
        <div class="s-label">Total Pesanan</div>
        <div class="s-value">{{ $ringkasan['total_pesanan'] }}</div>
    </div>
    <div class="summary-card">
        <div class="s-label">Pesanan Selesai</div>
        <div class="s-value" style="color:#166534;">{{ $ringkasan['pesanan_selesai'] }}</div>
    </div>
    <div class="summary-card">
        <div class="s-label">Pesanan Batal</div>
        <div class="s-value" style="color:#B91C1C;">{{ $ringkasan['pesanan_batal'] }}</div>
    </div>
    <div class="summary-card highlight">
        <div class="s-label">Total Pendapatan</div>
        <div class="s-value" style="font-size:13px;">
            Rp {{ number_format($ringkasan['total_pendapatan'],0,',','.') }}
        </div>
    </div>
</div>

{{-- Tabel Data --}}
<table>
    <thead>
        <tr>
            <th style="width:30px;">No</th>
            <th>Kode Pesanan</th>
            <th>Tanggal & Waktu</th>
            <th>Siswa</th>
            <th>Kelas</th>
            <th>Warung</th>
            <th>Item</th>
            <th>Total (Rp)</th>
            <th>Bayar</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($pesanan as $no => $p)
        <tr>
            <td style="text-align:center;color:#718096;">{{ $no + 1 }}</td>
            <td class="kode">{{ $p->kode_pesanan }}</td>
            <td>
                {{ $p->created_at->format('d/m/Y') }}<br>
                <span style="color:#718096;">{{ $p->created_at->format('H:i') }}</span>
            </td>
            <td style="font-weight:600;">{{ $p->siswa->name }}</td>
            <td style="color:#718096;">{{ $p->siswa->kelas ?? '-' }}</td>
            <td>{{ $p->penjual->penjualProfile?->nama_warung ?? $p->penjual->name }}</td>
            <td style="color:#718096;max-width:150px;">
                {{ $p->details->map(fn($d) => $d->nama_menu . ' ×' . $d->jumlah)->implode(', ') }}
            </td>
            <td style="font-weight:700;text-align:right;">
                {{ number_format($p->total_harga,0,',','.') }}
            </td>
            <td>
                @if($p->metode_pembayaran === 'tunai')
                    <span class="badge badge-default">Tunai</span>
                @else
                    <span class="badge badge-proses">Transfer</span>
                @endif
            </td>
            <td>
                @php $badge = $p->status_badge; @endphp
                <span class="badge badge-{{ $p->status }}">{{ $badge['label'] }}</span>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="10" style="text-align:center;color:#718096;padding:20px;">
                Tidak ada data pada periode ini.
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

{{-- Footer --}}
<div class="report-footer">
    <div>KantinKu — Sistem Pemesanan Makanan Kantin Sekolah</div>
    <div>{{ now()->format('d M Y, H:i') }} WIB</div>
</div>

</body>
</html>