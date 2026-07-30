@extends('layouts.app')
@section('title','Riwayat Pesanan')
@section('page-title','Riwayat Pesanan')

@section('sidebar-links')
<span class="nav-section-label">Menu</span>
<a href="{{ route('siswa.dashboard') }}" class="sidebar-link"><i class="bi bi-house-fill"></i>Beranda</a>
<a href="{{ route('siswa.keranjang') }}" class="sidebar-link"><i class="bi bi-bag2"></i>Keranjang</a>
<span class="nav-section-label">Pesanan</span>
<a href="{{ route('siswa.pesanan.riwayat') }}" class="sidebar-link active"><i class="bi bi-clock-history"></i>Riwayat Pesanan</a>
@endsection

@section('content')
<h5 class="fw-800 mb-4">Riwayat Pesanan</h5>

@forelse($pesanan as $p)
<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                    <span class="fw-800" style="color:var(--primary);">{{ $p->kode_pesanan }}</span>
                    <span class="badge bg-{{ $p->status_badge['color'] }}">{{ $p->status_badge['label'] }}</span>
                </div>
                <div class="fw-600 mb-1" style="font-size:.9rem;">{{ $p->penjual->penjualProfile?->nama_warung ?? $p->penjual->name }}</div>
                <div class="text-muted" style="font-size:.82rem;">
                    {{ $p->details->map(fn($d) => $d->nama_menu.' x'.$d->jumlah)->implode(' · ') }}
                </div>
                <div class="text-muted mt-1" style="font-size:.8rem;">{{ $p->created_at->format('d M Y, H:i') }}</div>
            </div>
            <div class="text-end">
                <div class="fw-800" style="font-size:1rem;color:var(--primary);">{{ $p->total_format }}</div>
                <a href="{{ route('siswa.pesanan.detail', $p) }}" class="btn btn-sm btn-outline-primary mt-2 fw-600">
                    <i class="bi bi-eye me-1"></i>Detail
                </a>
            </div>
        </div>
    </div>
</div>
@empty
<div class="card text-center py-5">
    <div style="font-size:3.5rem;">📋</div>
    <h5 class="fw-700 mt-3 mb-1">Belum Ada Pesanan</h5>
    <p class="text-muted mb-3">Yuk mulai pesan makanan favoritmu!</p>
    <div><a href="{{ route('siswa.dashboard') }}" class="btn btn-primary">Pesan Sekarang</a></div>
</div>
@endforelse

@if($pesanan->hasPages())
<div class="mt-3">{{ $pesanan->links() }}</div>
@endif
@endsection
