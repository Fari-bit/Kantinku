@extends('layouts.app')
@section('title','Recycle Bin')
@section('page-title','Recycle Bin')

@section('sidebar-links')
<span class="nav-section-label">Utama</span>
<a href="{{ route('penjual.dashboard') }}" class="sidebar-link"><i class="bi bi-grid-fill"></i>Dashboard</a>
<span class="nav-section-label">Operasional</span>
<a href="{{ route('penjual.pesanan.index') }}" class="sidebar-link"><i class="bi bi-bag-check"></i>Pesanan Masuk</a>
<a href="{{ route('penjual.pesanan.riwayat') }}" class="sidebar-link"><i class="bi bi-clock-history"></i>Riwayat Pesanan</a>
<a href="{{ route('penjual.ulasan.index') }}" class="sidebar-link"><i class="bi bi-star"></i>Ulasan</a>
<span class="nav-section-label">Menu</span>
<a href="{{ route('penjual.menu.index') }}" class="sidebar-link"><i class="bi bi-grid-3x3-gap"></i>Daftar Menu</a>
<a href="{{ route('penjual.menu.create') }}" class="sidebar-link"><i class="bi bi-plus-circle"></i>Tambah Menu</a>
<a href="{{ route('penjual.recycle-bin.index') }}" class="sidebar-link active"><i class="bi bi-trash3"></i>Recycle Bin</a>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
        <h5 class="fw-800 mb-0"><i class="bi bi-trash3 me-2"></i>Recycle Bin — Menu</h5>
        <div class="text-muted" style="font-size:.85rem;">
            {{ $menus->total() }} menu terhapus. Pulihkan sebelum dihapus permanen.
        </div>
    </div>
    @if($menus->total() > 0)
    <form action="{{ route('penjual.recycle-bin.empty') }}" method="POST" id="form-empty-menu-bin">
        @csrf @method('DELETE')
        <button type="button" class="btn btn-outline-danger" data-confirm="Kosongkan seluruh Recycle Bin menu? Semua menu di dalamnya akan terhapus PERMANEN dan tidak bisa dipulihkan lagi." data-form="form-empty-menu-bin">
            <i class="bi bi-x-octagon me-1"></i>Kosongkan Bin
        </button>
    </form>
    @endif
</div>

<div class="row g-3">
    @forelse($menus as $menu)
    <div class="col-sm-6 col-lg-4 col-xl-3">
        <div class="card h-100" style="border-radius:14px;overflow:hidden;opacity:.85;">
            <div style="height:150px;background:linear-gradient(135deg,#FFF0EB,#FFD4C2);position:relative;overflow:hidden;">
                @if($menu->foto)
                    <img src="{{ asset('storage/'.$menu->foto) }}" style="width:100%;height:100%;object-fit:cover;filter:grayscale(.5);" alt="">
                @else
                    <div style="height:100%;display:flex;align-items:center;justify-content:center;font-size:3.5rem;filter:grayscale(.5);">
                        {{ ['🍱','🍜','🍔','🥤','🍛','🍕','🥗','🧋'][$loop->index % 8] }}
                    </div>
                @endif
                <span style="position:absolute;top:.5rem;right:.5rem;background:rgba(220,53,69,.9);color:#fff;font-size:.68rem;font-weight:700;padding:2px 8px;border-radius:6px;">
                    <i class="bi bi-trash3"></i> Dihapus
                </span>
                <span style="position:absolute;bottom:.5rem;left:.5rem;background:rgba(0,0,0,.55);color:#fff;font-size:.7rem;font-weight:700;padding:2px 8px;border-radius:6px;text-transform:uppercase;letter-spacing:.5px;">
                    {{ $menu->kategori }}
                </span>
            </div>
            <div class="card-body p-3">
                <div class="fw-700 mb-1" style="font-size:.925rem;">{{ $menu->nama }}</div>
                <div class="d-flex align-items-center justify-content-between">
                    <span class="fw-800" style="color:var(--primary);font-size:1rem;">{{ $menu->harga_format }}</span>
                </div>
                <div class="mt-1" style="font-size:.75rem;color:#DC3545;">
                    <i class="bi bi-clock"></i> Dihapus {{ $menu->deleted_at->diffForHumans() }}
                </div>
            </div>
            <div class="card-footer bg-transparent p-2 d-flex gap-2">
                <form action="{{ route('penjual.recycle-bin.restore', $menu->id) }}" method="POST" id="restore-menu-{{ $menu->id }}" class="flex-fill">
                    @csrf @method('PATCH')
                    <button type="button" class="btn btn-sm btn-outline-success w-100 fw-600" style="font-size:.8rem;"
                            data-confirm="Pulihkan menu {{ $menu->nama }}?"
                            data-form="restore-menu-{{ $menu->id }}">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Pulihkan
                    </button>
                </form>
                <form action="{{ route('penjual.recycle-bin.force-delete', $menu->id) }}" method="POST" id="force-menu-{{ $menu->id }}">
                    @csrf @method('DELETE')
                    <button type="button" class="btn btn-sm btn-outline-danger fw-600" style="font-size:.8rem;"
                            data-confirm="Hapus permanen menu {{ $menu->nama }}? Tindakan ini TIDAK BISA dibatalkan!"
                            data-form="force-menu-{{ $menu->id }}">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card text-center py-5">
            <div style="font-size:3rem;">🗑️</div>
            <h5 class="fw-700 mt-3 mb-1">Recycle Bin kosong</h5>
            <p class="text-muted mb-0">Menu yang Anda hapus akan muncul di sini.</p>
        </div>
    </div>
    @endforelse
</div>

@if($menus->hasPages())
<div class="mt-4">{{ $menus->links() }}</div>
@endif
@endsection
