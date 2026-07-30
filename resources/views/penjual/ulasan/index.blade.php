@extends('layouts.app')
@section('title','Ulasan')
@section('page-title','Ulasan & Rating')

@section('sidebar-links')
<span class="nav-section-label">Utama</span>
<a href="{{ route('penjual.dashboard') }}" class="sidebar-link"><i class="bi bi-grid-fill"></i>Dashboard</a>
<span class="nav-section-label">Operasional</span>
<a href="{{ route('penjual.pesanan.index') }}" class="sidebar-link"><i class="bi bi-bag-check"></i>Pesanan Masuk</a>
<a href="{{ route('penjual.pesanan.riwayat') }}" class="sidebar-link"><i class="bi bi-clock-history"></i>Riwayat Pesanan</a>
<a href="{{ route('penjual.ulasan.index') }}" class="sidebar-link active"><i class="bi bi-star"></i>Ulasan</a>
<span class="nav-section-label">Menu</span>
<a href="{{ route('penjual.menu.index') }}" class="sidebar-link"><i class="bi bi-grid-3x3-gap"></i>Daftar Menu</a>
<a href="{{ route('penjual.menu.create') }}" class="sidebar-link"><i class="bi bi-plus-circle"></i>Tambah Menu</a>
@endsection

@section('content')

{{-- Rata-rata Rating --}}
<div class="card mb-4" style="background:linear-gradient(135deg,#FF6B35,#E55A25);border:none;">
    <div class="card-body p-4 text-white text-center">
        <div style="font-size:3.5rem;font-weight:900;font-family:'Sora',sans-serif;line-height:1;">
            {{ number_format($rataRating ?? 0, 1) }}
        </div>
        <div class="my-2">
            @for($i = 1; $i <= 5; $i++)
            <i class="bi {{ $i <= round($rataRating ?? 0) ? 'bi-star-fill' : 'bi-star' }}"
               style="font-size:1.3rem;color:rgba(255,255,255,{{ $i <= round($rataRating ?? 0) ? '1' : '.4' }});"></i>
            @endfor
        </div>
        <div style="opacity:.85;font-size:.9rem;">
            Rata-rata dari {{ $ulasan->total() }} ulasan
        </div>
    </div>
</div>

{{-- Daftar Ulasan --}}
@forelse($ulasan as $u)
<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex align-items-start gap-3">
            <img src="{{ $u->siswa->avatar_url }}" class="rounded-circle flex-shrink-0"
                 style="width:42px;height:42px;object-fit:cover;" alt="">
            <div class="flex-1">
                <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                    <span class="fw-700" style="font-size:.9rem;">{{ $u->siswa->name }}</span>
                    @if($u->siswa->kelas)
                    <span class="text-muted" style="font-size:.78rem;">{{ $u->siswa->kelas }}</span>
                    @endif
                    <span class="text-muted ms-auto" style="font-size:.78rem;">
                        {{ $u->created_at->diffForHumans() }}
                    </span>
                </div>

                {{-- Bintang --}}
                <div class="mb-2">
                    @for($i = 1; $i <= 5; $i++)
                    <i class="bi {{ $i <= $u->rating ? 'bi-star-fill text-warning' : 'bi-star text-muted' }}"
                       style="font-size:.95rem;"></i>
                    @endfor
                    <span class="ms-1 fw-700" style="font-size:.82rem;color:#F6AD55;">{{ $u->rating }}/5</span>
                </div>

                {{-- Komentar --}}
                @if($u->komentar)
                <div style="font-size:.88rem;color:#4A5568;line-height:1.6;background:#F7F8FC;padding:.65rem 1rem;border-radius:10px;border-left:3px solid #FF6B35;">
                    "{{ $u->komentar }}"
                </div>
                @else
                <div class="text-muted" style="font-size:.82rem;font-style:italic;">Tidak ada komentar</div>
                @endif

                {{-- Pesanan --}}
                @if($u->pesanan)
                <div class="mt-2">
                    <a href="{{ route('penjual.pesanan.show', $u->pesanan) }}"
                       class="text-decoration-none" style="font-size:.78rem;color:var(--primary);">
                        <i class="bi bi-receipt me-1"></i>Pesanan {{ $u->pesanan->kode_pesanan }}
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@empty
<div class="card text-center py-5">
    <div style="font-size:3rem;">⭐</div>
    <h5 class="fw-700 mt-3 mb-1">Belum ada ulasan</h5>
    <p class="text-muted">Ulasan dari siswa akan muncul di sini setelah pesanan selesai.</p>
</div>
@endforelse

@if($ulasan->hasPages())
<div class="mt-3">{{ $ulasan->links() }}</div>
@endif
@endsection
