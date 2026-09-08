@extends('layouts.app')
@section('title','Dashboard Penjual')
@section('page-title','Dashboard')

@section('sidebar-links')
<span class="nav-section-label">Utama</span>
<a href="{{ route('penjual.dashboard') }}" class="sidebar-link {{ request()->routeIs('penjual.dashboard') ? 'active' : '' }}">
    <i class="bi bi-grid-fill"></i>Dashboard
</a>
<span class="nav-section-label">Operasional</span>
<a href="{{ route('penjual.pesanan.index') }}" class="sidebar-link {{ request()->routeIs('penjual.pesanan.*') ? 'active' : '' }}">
    <i class="bi bi-bag-check"></i>Pesanan Masuk
    @if($stats['pesanan_menunggu'] > 0)
    <span class="sidebar-badge">{{ $stats['pesanan_menunggu'] }}</span>
    @endif
</a>
<a href="{{ route('penjual.pesanan.riwayat') }}" class="sidebar-link">
    <i class="bi bi-clock-history"></i>Riwayat Pesanan
</a>
<a href="{{ route('penjual.ulasan.index') }}" class="sidebar-link {{ request()->routeIs('penjual.ulasan.*') ? 'active' : '' }}">
    <i class="bi bi-star"></i>Ulasan
</a>
<span class="nav-section-label">Menu</span>
<a href="{{ route('penjual.menu.index') }}" class="sidebar-link {{ request()->routeIs('penjual.menu.*') ? 'active' : '' }}">
    <i class="bi bi-grid-3x3-gap"></i>Daftar Menu
</a>
<a href="{{ route('penjual.menu.create') }}" class="sidebar-link">
    <i class="bi bi-plus-circle"></i>Tambah Menu
</a>
<a href="{{ route('penjual.recycle-bin.index') }}" class="sidebar-link {{ request()->routeIs('penjual.recycle-bin.*') ? 'active' : '' }}">
    <i class="bi bi-trash3"></i>Recycle Bin
</a>

@endsection

@section('content')
{{-- Welcome Banner --}}
@php $profile = auth()->user()->penjualProfile; @endphp
<div class="p-4 mb-4 rounded-3 d-flex align-items-center gap-3" style="background:linear-gradient(135deg,#FF6B35,#E55A25);color:#fff;">
    <div style="font-size:2.5rem;">🏪</div>
    <div>
        <div style="font-family:'Sora',sans-serif;font-weight:800;font-size:1.2rem;">{{ $profile?->nama_warung ?? auth()->user()->name }}</div>
        <div style="opacity:.8;font-size:.88rem;">
            {{ $profile?->nomor_stand ? 'Stand '.$profile->nomor_stand.' · ' : '' }}
            Jam {{ $profile?->jam_buka ?? '07:00' }} – {{ $profile?->jam_tutup ?? '14:00' }}
        </div>
    </div>
    <div class="ms-auto">
        <span class="badge" style="background:rgba(255,255,255,.25);font-size:.8rem;padding:.4rem .9rem;">
            {{ $profile?->is_open ? '🟢 Sedang Buka' : '🔴 Tutup' }}
        </span>
    </div>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#FFF0EB;color:var(--primary);">📋</div>
            <div>
                <div class="stat-label">Pesanan Hari Ini</div>
                <div class="stat-value">{{ $stats['pesanan_hari_ini'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#FFFBEB;color:#F6AD55;">⏳</div>
            <div>
                <div class="stat-label">Menunggu</div>
                <div class="stat-value">{{ $stats['pesanan_menunggu'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#EBF8F0;color:#48BB78;">💰</div>
            <div>
                <div class="stat-label">Pendapatan Hari Ini</div>
                <div class="stat-value" style="font-size:1.1rem;">Rp {{ number_format($stats['pendapatan_hari_ini'],0,',','.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#FFF5F5;color:#FC8181;">⚠️</div>
            <div>
                <div class="stat-label">Menu Habis</div>
                <div class="stat-value">{{ $stats['menu_habis'] }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Pesanan Aktif --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span>Pesanan Aktif</span>
                <a href="{{ route('penjual.pesanan.index') }}" class="btn btn-sm btn-outline-primary" style="font-size:.8rem;">Kelola Semua</a>
            </div>
            <div class="card-body p-0">
                @forelse($pesanan_aktif as $pesanan)
                <div class="p-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div class="d-flex align-items-start justify-content-between gap-3">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="fw-700" style="color:var(--primary);font-size:.9rem;">{{ $pesanan->kode_pesanan }}</span>
                                <span class="badge bg-{{ $pesanan->status_badge['color'] }}">{{ $pesanan->status_badge['label'] }}</span>
                            </div>
                            <div class="fw-600" style="font-size:.875rem;">{{ $pesanan->siswa->name }}</div>
                            <div style="font-size:.8rem;color:#718096;">
                                {{ $pesanan->details->map(fn($d) => $d->nama_menu.' x'.$d->jumlah)->implode(', ') }}
                            </div>
                        </div>
                        <div class="text-end flex-shrink-0">
                            <div class="fw-800" style="color:var(--primary);">{{ $pesanan->total_format }}</div>
                            <div style="font-size:.78rem;color:#718096;">{{ $pesanan->created_at->diffForHumans() }}</div>
                        </div>
                    </div>

                    {{-- Status Action Buttons --}}
                    <div class="mt-2 d-flex gap-2 flex-wrap">
                        @if($pesanan->status === 'menunggu')
                        <button class="btn btn-sm btn-primary fw-600" onclick="updateStatus({{ $pesanan->id }},'dikonfirmasi')">
                            <i class="bi bi-check-lg me-1"></i>Konfirmasi
                        </button>
                        <button class="btn btn-sm btn-outline-danger fw-600" onclick="updateStatus({{ $pesanan->id }},'dibatalkan')">Batalkan</button>
                        @elseif($pesanan->status === 'dikonfirmasi')
                        <button class="btn btn-sm btn-primary fw-600" onclick="updateStatus({{ $pesanan->id }},'diproses')">
                            <i class="bi bi-fire me-1"></i>Mulai Proses
                        </button>
                        @elseif($pesanan->status === 'diproses')
                        <button class="btn btn-sm btn-success fw-600" onclick="updateStatus({{ $pesanan->id }},'siap')">
                            <i class="bi bi-bell me-1"></i>Siap Diambil
                        </button>
                        @elseif($pesanan->status === 'siap')
                        <button class="btn btn-sm btn-secondary fw-600" onclick="updateStatus({{ $pesanan->id }},'selesai')">
                            <i class="bi bi-check2-all me-1"></i>Selesai
                        </button>
                        @endif
                        <a href="{{ route('penjual.pesanan.show', $pesanan) }}" class="btn btn-sm btn-outline-secondary fw-600">
                            <i class="bi bi-eye me-1"></i>Detail
                        </a>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-5">
                    <div style="font-size:2.5rem;">🎉</div>
                    <div class="fw-600 mt-2">Tidak ada pesanan aktif saat ini.</div>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Menu Populer --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span>Menu Terlaris</span>
                <a href="{{ route('penjual.menu.index') }}" class="btn btn-sm btn-outline-primary" style="font-size:.8rem;">Semua</a>
            </div>
            <div class="card-body">
                @forelse($menu_populer as $menu)
                <div class="d-flex align-items-center gap-3 pb-3 {{ !$loop->last ? 'border-bottom mb-3' : '' }}">
                    <div style="width:42px;height:42px;border-radius:10px;overflow:hidden;flex-shrink:0;background:#FFF0EB;display:flex;align-items:center;justify-content:center;font-size:1.2rem;">
                        @if($menu->foto)
                            <img src="{{ asset('storage/'.$menu->foto) }}" style="width:100%;height:100%;object-fit:cover;" alt="">
                        @else 🍽️ @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="fw-700 text-truncate" style="font-size:.875rem;">{{ $menu->nama }}</div>
                        <div style="font-size:.78rem;color:#718096;">{{ $menu->terjual }} terjual · Stok: {{ $menu->stok }}</div>
                    </div>
                    <div class="fw-800" style="color:var(--primary);font-size:.875rem;white-space:nowrap;">{{ $menu->harga_format }}</div>
                </div>
                @empty
                <div class="text-center text-muted py-3" style="font-size:.88rem;">Belum ada menu.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function updateStatus(pesananId, status) {
    fetch(`/penjual/pesanan/${pesananId}/status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ status })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            Swal.fire({ icon:'success', title:'Status Diperbarui!', timer:1500, showConfirmButton:false })
                .then(() => location.reload());
        }
    });
}
</script>
@endpush
