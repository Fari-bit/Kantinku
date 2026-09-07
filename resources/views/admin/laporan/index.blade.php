@extends('layouts.app')
@section('title','Laporan')
@section('page-title','Laporan & Statistik')

@section('sidebar-links')
<span class="nav-section-label">Utama</span>
<a href="{{ route('admin.dashboard') }}" class="sidebar-link"><i class="bi bi-grid-fill"></i>Dashboard</a>
<span class="nav-section-label">Manajemen</span>
<a href="{{ route('admin.penjual.index') }}" class="sidebar-link"><i class="bi bi-shop"></i>Penjual</a>
<a href="{{ route('admin.penjual.pendaftaran') }}" class="sidebar-link"><i class="bi bi-person-check"></i>Pendaftaran Penjual</a>
<a href="{{ route('admin.siswa.index') }}" class="sidebar-link"><i class="bi bi-people"></i>Siswa</a>
<a href="{{ route('admin.recycle-bin.index') }}" class="sidebar-link"><i class="bi bi-trash3"></i>Recycle Bin</a>
<span class="nav-section-label">Laporan</span>
<a href="{{ route('admin.laporan.index') }}" class="sidebar-link active"><i class="bi bi-bar-chart-fill"></i>Laporan</a>
@endsection

@section('content')

{{-- Filter Bar --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" id="filterForm" class="row g-3 align-items-end">
            <div class="col-sm-4 col-lg-3">
                <label class="form-label fw-600" style="font-size:.85rem;">Periode</label>
                <select name="periode" class="form-select" onchange="toggleCustom(this.value)">
                    <option value="hari_ini"  {{ request('periode','hari_ini')==='hari_ini'  ? 'selected':'' }}>Hari Ini</option>
                    <option value="minggu_ini" {{ request('periode')==='minggu_ini' ? 'selected':'' }}>Minggu Ini</option>
                    <option value="bulan_ini"  {{ request('periode')==='bulan_ini'  ? 'selected':'' }}>Bulan Ini</option>
                    <option value="custom"     {{ request('periode')==='custom'     ? 'selected':'' }}>Custom</option>
                </select>
            </div>

            <div id="customDates" class="{{ request('periode')==='custom' ? '' : 'd-none' }} col-sm-6 col-lg-5">
                <div class="row g-2">
                    <div class="col-6">
                        <label class="form-label fw-600" style="font-size:.85rem;">Dari</label>
                        <input type="date" name="dari" class="form-control" value="{{ request('dari') }}">
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-600" style="font-size:.85rem;">Sampai</label>
                        <input type="date" name="sampai" class="form-control" value="{{ request('sampai') }}">
                    </div>
                </div>
            </div>

            <div class="col-auto d-flex gap-2 flex-wrap">
                <button type="submit" class="btn btn-primary fw-600">
                    <i class="bi bi-search me-1"></i>Filter
                </button>
                {{-- Export Excel --}}
                <a href="{{ route('admin.laporan.export-excel', request()->query()) }}"
                   class="btn btn-success fw-600">
                    <i class="bi bi-file-earmark-spreadsheet me-1"></i>Excel
                </a>
                {{-- Export PDF --}}
                <a href="{{ route('admin.laporan.export-pdf', request()->query()) }}"
                   target="_blank"
                   class="btn btn-danger fw-600">
                    <i class="bi bi-file-earmark-pdf me-1"></i>PDF
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Ringkasan --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#FFF0EB;color:var(--primary);">📋</div>
            <div>
                <div class="stat-label">Total Pesanan</div>
                <div class="stat-value">{{ $ringkasan['total_pesanan'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#EBF8F0;color:#48BB78;">✅</div>
            <div>
                <div class="stat-label">Pesanan Selesai</div>
                <div class="stat-value">{{ $ringkasan['pesanan_selesai'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#FFF5F5;color:#FC8181;">❌</div>
            <div>
                <div class="stat-label">Pesanan Batal</div>
                <div class="stat-value">{{ $ringkasan['pesanan_batal'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#FFFBEB;color:#F6AD55;">💰</div>
            <div>
                <div class="stat-label">Total Pendapatan</div>
                <div class="stat-value" style="font-size:1rem;">
                    Rp {{ number_format($ringkasan['total_pendapatan'],0,',','.') }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Chart --}}
<div class="card mb-4">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span>Grafik Pesanan Bulan Ini</span>
    </div>
    <div class="card-body">
        <canvas id="orderChart" height="80"></canvas>
    </div>
</div>

{{-- Tabel --}}
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
        <span>
            Riwayat Pesanan —
            <span class="text-muted fw-400" style="font-size:.9rem;">{{ $label }}</span>
        </span>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.laporan.export-excel', request()->query()) }}"
               class="btn btn-sm btn-success fw-600" style="font-size:.8rem;">
                <i class="bi bi-download me-1"></i>Export Excel
            </a>
            <a href="{{ route('admin.laporan.export-pdf', request()->query()) }}"
               target="_blank"
               class="btn btn-sm btn-danger fw-600" style="font-size:.8rem;">
                <i class="bi bi-printer me-1"></i>Export PDF
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">Kode</th>
                        <th>Tanggal</th>
                        <th>Siswa</th>
                        <th>Warung</th>
                        <th>Total</th>
                        <th>Pembayaran</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pesanan as $p)
                    <tr>
                        <td class="ps-3">
                            <span class="fw-700" style="font-size:.85rem;color:var(--primary);">
                                {{ $p->kode_pesanan }}
                            </span>
                        </td>
                        <td style="font-size:.82rem;color:#718096;">
                            {{ $p->created_at->format('d M Y') }}<br>
                            <span style="font-size:.78rem;">{{ $p->created_at->format('H:i') }}</span>
                        </td>
                        <td>
                            <div style="font-size:.875rem;font-weight:600;">{{ $p->siswa->name }}</div>
                            <div style="font-size:.78rem;color:#718096;">{{ $p->siswa->kelas ?? '-' }}</div>
                        </td>
                        <td style="font-size:.875rem;">
                            {{ $p->penjual->penjualProfile?->nama_warung ?? $p->penjual->name }}
                        </td>
                        <td>
                            <span class="fw-700" style="font-size:.875rem;">{{ $p->total_format }}</span>
                        </td>
                        <td>
                            <div>
                                <span class="badge {{ $p->metode_pembayaran === 'tunai' ? 'bg-warning text-dark' : 'bg-info' }}">
                                    {{ $p->metode_pembayaran === 'tunai' ? '💵 Tunai' : '🏦 Transfer' }}
                                </span>
                            </div>
                            <div class="mt-1">
                                <span class="badge {{ $p->status_pembayaran === 'sudah_bayar' ? 'bg-success' : 'bg-secondary' }}"
                                      style="font-size:.7rem;">
                                    {{ $p->status_pembayaran === 'sudah_bayar' ? 'Lunas' : 'Belum Bayar' }}
                                </span>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-{{ $p->status_badge['color'] }}">
                                {{ $p->status_badge['label'] }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">
                            Tidak ada pesanan pada periode ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($pesanan->hasPages())
    <div class="card-footer bg-transparent">
        {{ $pesanan->links() }}
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function toggleCustom(v) {
    document.getElementById('customDates').classList.toggle('d-none', v !== 'custom');
}

// Chart
const labels = @json($chartQuery->pluck('tanggal')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M')));
const data   = @json($chartQuery->pluck('total'));
const income = @json($chartQuery->pluck('pendapatan'));

const ctx = document.getElementById('orderChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels,
        datasets: [
            {
                label: 'Jumlah Pesanan',
                data,
                backgroundColor: 'rgba(255,107,53,.15)',
                borderColor: '#FF6B35',
                borderWidth: 2,
                borderRadius: 6,
                yAxisID: 'y',
            },
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#F0F0F0' } },
            x: { grid: { display: false } },
        }
    }
});
</script>
@endpush