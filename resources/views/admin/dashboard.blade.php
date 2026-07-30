@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('sidebar-links')
<span class="nav-section-label">Utama</span>
<a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
    <i class="bi bi-grid-fill"></i>Dashboard
</a>

<span class="nav-section-label">Manajemen</span>
<a href="{{ route('admin.penjual.index') }}" class="sidebar-link {{ request()->routeIs('admin.penjual.*') ? 'active' : '' }}">
    <i class="bi bi-shop"></i>Penjual
    @if($stats['pendaftaran_pending'] > 0)
    <span class="sidebar-badge">{{ $stats['pendaftaran_pending'] }}</span>
    @endif
</a>
<a href="{{ route('admin.penjual.pendaftaran') }}" class="sidebar-link {{ request()->routeIs('admin.penjual.pendaftaran*') ? 'active' : '' }}">
    <i class="bi bi-person-check"></i>Pendaftaran Penjual
</a>
<a href="{{ route('admin.siswa.index') }}" class="sidebar-link {{ request()->routeIs('admin.siswa.*') ? 'active' : '' }}">
    <i class="bi bi-people"></i>Siswa
</a>

<span class="nav-section-label">Laporan</span>
<a href="{{ route('admin.laporan.index') }}" class="sidebar-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
    <i class="bi bi-bar-chart-fill"></i>Laporan & Statistik
</a>
@endsection

@section('content')
{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#FFF0EB;color:var(--primary);">🏪</div>
            <div>
                <div class="stat-label">Penjual Aktif</div>
                <div class="stat-value">{{ $stats['total_penjual'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#EBF8F0;color:#48BB78;">👨‍🎓</div>
            <div>
                <div class="stat-label">Total Siswa</div>
                <div class="stat-value">{{ $stats['total_siswa'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#EEF2FF;color:#667EEA;">🍽️</div>
            <div>
                <div class="stat-label">Total Menu</div>
                <div class="stat-value">{{ $stats['total_menu'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#FFFBEB;color:#F6AD55;">📋</div>
            <div>
                <div class="stat-label">Pesanan Hari Ini</div>
                <div class="stat-value">{{ $stats['total_pesanan'] }}</div>
            </div>
        </div>
    </div>
</div>

@if($stats['pendaftaran_pending'] > 0)
<div class="alert d-flex align-items-center gap-3 mb-4" style="background:#FFF7ED;border:1px solid #FDBA74;border-radius:12px;">
    <div style="font-size:1.5rem;">🔔</div>
    <div class="flex-1">
        <div class="fw-700">Ada {{ $stats['pendaftaran_pending'] }} pendaftaran penjual yang menunggu review!</div>
        <div style="font-size:.85rem;color:#78716C;">Segera tinjau dan putuskan untuk menyetujui atau menolak.</div>
    </div>
    <a href="{{ route('admin.penjual.pendaftaran') }}" class="btn btn-sm btn-warning fw-700">Tinjau Sekarang</a>
</div>
@endif

<div class="row g-4">
    {{-- Pesanan Terbaru --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span>Pesanan Terbaru</span>
                <a href="{{ route('admin.laporan.index') }}" class="btn btn-sm btn-outline-primary" style="font-size:.8rem;">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">Kode</th>
                                <th>Siswa</th>
                                <th>Penjual</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pesanan_terbaru as $p)
                            <tr>
                                <td class="ps-3"><span class="fw-700" style="font-size:.85rem;color:var(--primary);">{{ $p->kode_pesanan }}</span></td>
                                <td style="font-size:.85rem;">{{ $p->siswa->name }}</td>
                                <td style="font-size:.85rem;">{{ $p->penjual->penjualProfile?->nama_warung ?? $p->penjual->name }}</td>
                                <td><span class="fw-700" style="font-size:.85rem;">{{ $p->total_format }}</span></td>
                                <td>
                                    <span class="badge bg-{{ $p->status_badge['color'] }}">{{ $p->status_badge['label'] }}</span>
                                </td>
                                <td style="font-size:.8rem;color:#718096;">{{ $p->created_at->diffForHumans() }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted py-4">Belum ada pesanan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Right Column --}}
    <div class="col-lg-4 d-flex flex-column gap-4">
        {{-- Pendaftaran Pending --}}
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span>Pendaftaran Baru</span>
                <a href="{{ route('admin.penjual.pendaftaran') }}" class="btn btn-sm btn-outline-primary" style="font-size:.8rem;">Semua</a>
            </div>
            <div class="card-body">
                @forelse($pendaftaran_terbaru as $d)
                <div class="d-flex align-items-center gap-3 pb-3 {{ !$loop->last ? 'border-bottom mb-3' : '' }}">
                    <div style="width:38px;height:38px;background:#FFF0EB;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;">🏪</div>
                    <div class="flex-1 min-w-0">
                        <div class="fw-700" style="font-size:.875rem;">{{ $d->nama_warung }}</div>
                        <div style="font-size:.78rem;color:#718096;">{{ $d->nama_lengkap }}</div>
                    </div>
                    <a href="{{ route('admin.penjual.pendaftaran.show', $d) }}" class="btn btn-sm btn-outline-warning" style="font-size:.75rem;white-space:nowrap;">Review</a>
                </div>
                @empty
                <div class="text-center text-muted py-3" style="font-size:.88rem;">Tidak ada pendaftaran baru.</div>
                @endforelse
            </div>
        </div>

        {{-- Pendapatan --}}
        <div class="card">
            <div class="card-header">Pendapatan Hari Ini</div>
            <div class="card-body text-center py-4">
                <div style="font-size:2rem;font-weight:900;font-family:'Sora',sans-serif;color:var(--primary);">
                    Rp {{ number_format($stats['pendapatan_hari_ini'], 0, ',', '.') }}
                </div>
                <div class="text-muted mt-1" style="font-size:.85rem;">dari pesanan yang selesai</div>
            </div>
        </div>
    </div>
</div>
@endsection
