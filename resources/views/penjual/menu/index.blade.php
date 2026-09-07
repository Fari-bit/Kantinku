@extends('layouts.app')
@section('title','Daftar Menu')
@section('page-title','Daftar Menu')

@section('sidebar-links')
<span class="nav-section-label">Utama</span>
<a href="{{ route('penjual.dashboard') }}" class="sidebar-link"><i class="bi bi-grid-fill"></i>Dashboard</a>
<span class="nav-section-label">Operasional</span>
<a href="{{ route('penjual.pesanan.index') }}" class="sidebar-link"><i class="bi bi-bag-check"></i>Pesanan Masuk</a>
<a href="{{ route('penjual.pesanan.riwayat') }}" class="sidebar-link"><i class="bi bi-clock-history"></i>Riwayat Pesanan</a>
<a href="{{ route('penjual.ulasan.index') }}" class="sidebar-link {{ request()->routeIs('penjual.ulasan.*') ? 'active' : '' }}">
    <i class="bi bi-star"></i>Ulasan
</a>
<span class="nav-section-label">Menu</span>
<a href="{{ route('penjual.menu.index') }}" class="sidebar-link active"><i class="bi bi-grid-3x3-gap"></i>Daftar Menu</a>
<a href="{{ route('penjual.menu.create') }}" class="sidebar-link"><i class="bi bi-plus-circle"></i>Tambah Menu</a>
<a href="{{ route('penjual.recycle-bin.index') }}" class="sidebar-link {{ request()->routeIs('penjual.recycle-bin.*') ? 'active' : '' }}"><i class="bi bi-trash3"></i>Recycle Bin</a>

@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="fw-800 mb-0">Daftar Menu</h5>
        <div class="text-muted" style="font-size:.85rem;">{{ $menus->total() }} menu</div>
    </div>
    <a href="{{ route('penjual.menu.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Tambah Menu
    </a>
</div>

<div class="row g-3">
    @forelse($menus as $menu)
    <div class="col-sm-6 col-lg-4 col-xl-3">
        <div class="card h-100" style="border-radius:14px;overflow:hidden;">
            <div style="height:150px;background:linear-gradient(135deg,#FFF0EB,#FFD4C2);position:relative;overflow:hidden;">
                @if($menu->foto)
                    <img src="{{ asset('storage/'.$menu->foto) }}" style="width:100%;height:100%;object-fit:cover;" alt="">
                @else
                    <div style="height:100%;display:flex;align-items:center;justify-content:center;font-size:3.5rem;">
                        {{ ['🍱','🍜','🍔','🥤','🍛','🍕','🥗','🧋'][$loop->index % 8] }}
                    </div>
                @endif
                {{-- Toggle Available --}}
                <div style="position:absolute;top:.5rem;right:.5rem;">
                    <div class="form-check form-switch mb-0" title="{{ $menu->tersedia ? 'Nonaktifkan' : 'Aktifkan' }}">
                        <input class="form-check-input" type="checkbox" role="switch"
                               {{ $menu->tersedia ? 'checked' : '' }}
                               onchange="toggleMenu({{ $menu->id }}, this)"
                               style="width:2.2em;height:1.1em;cursor:pointer;">
                    </div>
                </div>
                {{-- Kategori badge --}}
                <span style="position:absolute;bottom:.5rem;left:.5rem;background:rgba(0,0,0,.55);color:#fff;font-size:.7rem;font-weight:700;padding:2px 8px;border-radius:6px;text-transform:uppercase;letter-spacing:.5px;">
                    {{ $menu->kategori }}
                </span>
            </div>
            <div class="card-body p-3">
                <div class="fw-700 mb-1" style="font-size:.925rem;">{{ $menu->nama }}</div>
                @if($menu->deskripsi)
                <div class="text-muted mb-2" style="font-size:.78rem;line-height:1.5;">{{ Str::limit($menu->deskripsi, 60) }}</div>
                @endif
                <div class="d-flex align-items-center justify-content-between">
                    <span class="fw-800" style="color:var(--primary);font-size:1rem;">{{ $menu->harga_format }}</span>
                    <span class="text-muted" style="font-size:.78rem;">Stok: <strong>{{ $menu->stok }}</strong></span>
                </div>
                <div class="mt-1 d-flex align-items-center gap-2" style="font-size:.78rem;color:#718096;">
                    <span>⭐ {{ number_format($menu->rating, 1) }}</span>
                    <span>·</span>
                    <span>{{ $menu->terjual }} terjual</span>
                </div>
            </div>
            <div class="card-footer bg-transparent p-2 d-flex gap-2">
                <a href="{{ route('penjual.menu.edit', $menu) }}" class="btn btn-sm btn-outline-primary flex-fill fw-600" style="font-size:.8rem;">
                    <i class="bi bi-pencil me-1"></i>Edit
                </a>
                <form action="{{ route('penjual.menu.destroy', $menu) }}" method="POST" id="del-menu-{{ $menu->id }}">
                    @csrf @method('DELETE')
                    <button type="button" class="btn btn-sm btn-outline-danger fw-600" style="font-size:.8rem;"
                            data-confirm="Hapus menu {{ $menu->nama }}?"
                            data-form="del-menu-{{ $menu->id }}">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card text-center py-5">
            <div style="font-size:3rem;">🍽️</div>
            <h5 class="fw-700 mt-3 mb-1">Belum ada menu</h5>
            <p class="text-muted mb-3">Mulai tambahkan menu untuk warung Anda.</p>
            <div><a href="{{ route('penjual.menu.create') }}" class="btn btn-primary">Tambah Menu Pertama</a></div>
        </div>
    </div>
    @endforelse
</div>

@if($menus->hasPages())
<div class="mt-4">{{ $menus->links() }}</div>
@endif
@endsection

@push('scripts')
<script>
function toggleMenu(menuId, checkbox) {
    fetch(`/penjual/menu/${menuId}/toggle`, {
        method: 'PATCH',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    })
    .then(r => r.json())
    .then(data => {
        // Visual feedback
        const card = checkbox.closest('.card');
        if (!data.tersedia) {
            card.style.opacity = '0.6';
        } else {
            card.style.opacity = '1';
        }
    });
}
</script>
@endpush
