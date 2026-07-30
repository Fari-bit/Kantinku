@extends('layouts.app')
@section('title','Dashboard')
@section('page-title','Pesan Makanan')

@section('sidebar-links')
<span class="nav-section-label">Menu</span>
<a href="{{ route('siswa.dashboard') }}" class="sidebar-link {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
    <i class="bi bi-house-fill"></i>Beranda
</a>
<a href="{{ route('siswa.keranjang') }}" class="sidebar-link">
    <i class="bi bi-bag2"></i>Keranjang
    @php $cc = array_sum(array_column(session('cart',[]),'jumlah')); @endphp
    @if($cc > 0)<span class="sidebar-badge">{{ $cc }}</span>@endif
</a>
<span class="nav-section-label">Pesanan</span>
<a href="{{ route('siswa.pesanan.riwayat') }}" class="sidebar-link {{ request()->routeIs('siswa.pesanan.*') ? 'active' : '' }}">
    <i class="bi bi-clock-history"></i>Riwayat Pesanan
</a>
@endsection

@section('content')

{{-- Pesanan Aktif Banner --}}
@if($pesanan_aktif->isNotEmpty())
<div class="mb-4">
    @foreach($pesanan_aktif as $aktif)
    <div class="d-flex align-items-center gap-3 p-3 rounded-3 mb-2" style="background:#EBF8F0;border:1px solid #9AE6B4;">
        <div style="font-size:1.5rem;">
            {{ $aktif->status === 'siap' ? '🔔' : '⏳' }}
        </div>
        <div class="flex-1">
            <div class="fw-700" style="font-size:.9rem;">
                @if($aktif->status === 'siap') Pesanan Siap Diambil!
                @elseif($aktif->status === 'diproses') Pesanan Sedang Diproses
                @else Pesanan Menunggu Konfirmasi @endif
            </div>
            <div style="font-size:.8rem;color:#276749;">
                {{ $aktif->kode_pesanan }} · {{ $aktif->penjual->penjualProfile?->nama_warung ?? $aktif->penjual->name }}
            </div>
        </div>
        <a href="{{ route('siswa.pesanan.detail', $aktif) }}" class="btn btn-sm btn-success fw-700">Cek Status</a>
    </div>
    @endforeach
</div>
@endif

{{-- Rekomendasi --}}
@if($rekomendasi->isNotEmpty())
<div class="mb-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h6 class="fw-800 mb-0">🔥 Menu Populer</h6>
    </div>
    <div class="row g-2">
        @foreach($rekomendasi as $menu)
        <div class="col-6 col-md-3 col-lg-2">
            @include('siswa._menu-card', ['menu' => $menu])
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Menu Per Penjual --}}
@forelse($penjual as $p)
<div class="mb-5">
    {{-- Penjual Header --}}
    <div class="d-flex align-items-center gap-3 mb-3 p-3 rounded-3" style="background:#fff;border:1px solid #E2E8F0;">
        <div style="width:52px;height:52px;border-radius:14px;overflow:hidden;background:#FFF0EB;display:flex;align-items:center;justify-content:center;font-size:1.75rem;flex-shrink:0;">
            @if($p->penjualProfile?->foto_warung)
                <img src="{{ asset('storage/'.$p->penjualProfile->foto_warung) }}" style="width:100%;height:100%;object-fit:cover;" alt="">
            @else 🏪 @endif
        </div>
        <div class="flex-1">
            <div class="fw-800" style="font-size:1rem;">{{ $p->penjualProfile?->nama_warung ?? $p->name }}</div>
            <div class="text-muted" style="font-size:.8rem;">
                @if($p->penjualProfile?->nomor_stand) Stand {{ $p->penjualProfile->nomor_stand }} · @endif
                {{ $p->menus->count() }} menu tersedia
                @if($p->penjualProfile) · {{ $p->penjualProfile->jam_buka }} – {{ $p->penjualProfile->jam_tutup }} @endif
            </div>
        </div>
        <span class="badge" style="background:#C6F6D5;color:#22543D;">🟢 Buka</span>
    </div>

    <div class="row g-2">
        @foreach($p->menus as $menu)
        <div class="col-6 col-md-4 col-lg-3">
            @include('siswa._menu-card', ['menu' => $menu])
        </div>
        @endforeach
    </div>
</div>
@empty
<div class="card text-center py-5">
    <div style="font-size:3rem;">🍽️</div>
    <h5 class="fw-700 mt-3 mb-1">Belum Ada Menu Tersedia</h5>
    <p class="text-muted">Tunggu penjual membuka warungnya.</p>
</div>
@endforelse

{{-- Float Cart Button (Mobile) --}}
@php $cartCount = array_sum(array_column(session('cart',[]),'jumlah')); @endphp
@if($cartCount > 0)
<div style="position:fixed;bottom:1.5rem;right:1.5rem;z-index:999;" id="floatCart">
    <a href="{{ route('siswa.keranjang') }}" class="btn btn-primary d-flex align-items-center gap-2 shadow-lg fw-700" style="border-radius:50px;padding:.75rem 1.5rem;">
        <i class="bi bi-bag2-fill"></i>
        <span>Keranjang</span>
        <span class="badge bg-white" style="color:var(--primary);" id="floatCartBadge">{{ $cartCount }}</span>
    </a>
</div>
@endif

{{-- Toast Notification --}}
<div id="addToCartToast" style="position:fixed;bottom:6rem;left:50%;transform:translateX(-50%);z-index:9999;display:none;">
    <div class="bg-dark text-white px-4 py-2 rounded-pill fw-600" style="font-size:.88rem;" id="toastMsg"></div>
</div>
@endsection

@push('scripts')
<script>
function addToCart(menuId, menuName) {
    fetch('{{ route("siswa.keranjang.tambah") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ menu_id: menuId, jumlah: 1 })
    })
    .then(r => r.json())
    .then(data => {
        if (data.need_clear) {
            Swal.fire({
                title: 'Penjual Berbeda',
                text: data.error,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#FF6B35',
                confirmButtonText: 'Kosongkan & Tambah',
                cancelButtonText: 'Batal'
            }).then(result => {
                if (result.isConfirmed) {
                    fetch('{{ route("siswa.keranjang.kosongkan") }}', {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                    }).then(() => addToCart(menuId, menuName));
                }
            });
            return;
        }
        if (data.error) {
            showToast('⚠️ ' + data.error);
            return;
        }

        // Update cart badge
        const badges = document.querySelectorAll('#cartBadge, #floatCartBadge');
        badges.forEach(b => { b.textContent = data.totalItem; b.style.display = ''; });

        // Show float cart if not visible
        const fc = document.getElementById('floatCart');
        if (!fc) location.reload(); // reload to show float cart

        showToast('🛒 ' + menuName + ' ditambahkan!');

        // Animate button
        const btn = document.getElementById('add-btn-' + menuId);
        if (btn) {
            btn.classList.add('btn-success');
            btn.classList.remove('btn-primary');
            btn.innerHTML = '<i class="bi bi-check-lg"></i>';
            setTimeout(() => {
                btn.classList.remove('btn-success');
                btn.classList.add('btn-primary');
                btn.innerHTML = '<i class="bi bi-plus-lg"></i>';
            }, 1000);
        }
    })
    .catch(() => showToast('Gagal menambahkan ke keranjang.'));
}

function showToast(msg) {
    const toast = document.getElementById('addToCartToast');
    document.getElementById('toastMsg').textContent = msg;
    toast.style.display = 'block';
    toast.style.opacity = '1';
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.style.display = 'none', 300);
    }, 2000);
}
</script>
@endpush
