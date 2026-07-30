@extends('layouts.app')
@section('title','Keranjang')
@section('page-title','Keranjang Belanja')

@section('sidebar-links')
<span class="nav-section-label">Menu</span>
<a href="{{ route('siswa.dashboard') }}" class="sidebar-link"><i class="bi bi-house-fill"></i>Beranda</a>
<a href="{{ route('siswa.keranjang') }}" class="sidebar-link active"><i class="bi bi-bag2"></i>Keranjang</a>
<span class="nav-section-label">Pesanan</span>
<a href="{{ route('siswa.pesanan.riwayat') }}" class="sidebar-link"><i class="bi bi-clock-history"></i>Riwayat Pesanan</a>
@endsection

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('siswa.dashboard') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="fw-800 mb-0">Keranjang Belanja</h5>
</div>

@if(empty($items))
<div class="card text-center py-5">
    <div style="font-size:4rem;">🛒</div>
    <h5 class="fw-700 mt-3 mb-1">Keranjang Masih Kosong</h5>
    <p class="text-muted mb-3">Yuk pilih menu favoritmu!</p>
    <div><a href="{{ route('siswa.dashboard') }}" class="btn btn-primary">Lihat Menu</a></div>
</div>
@else
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Item ({{ count($items) }})</span>
                <button class="btn btn-sm btn-outline-danger fw-600" style="font-size:.8rem;" onclick="clearCart()">
                    <i class="bi bi-trash me-1"></i>Kosongkan
                </button>
            </div>
            <div class="card-body p-0">
                @foreach($items as $menuId => $item)
                <div class="p-3 {{ !$loop->last ? 'border-bottom' : '' }}" id="cart-item-{{ $menuId }}">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:56px;height:56px;border-radius:12px;overflow:hidden;flex-shrink:0;background:#FFF0EB;display:flex;align-items:center;justify-content:center;font-size:1.5rem;">
                            @if($item['menu']->foto)
                                <img src="{{ asset('storage/'.$item['menu']->foto) }}" style="width:100%;height:100%;object-fit:cover;" alt="">
                            @else 🍽️ @endif
                        </div>
                        <div class="flex-1">
                            <div class="fw-700" style="font-size:.9rem;">{{ $item['menu']->nama }}</div>
                            <div class="text-muted" style="font-size:.78rem;">{{ $item['menu']->penjual->penjualProfile?->nama_warung ?? $item['menu']->penjual->name }}</div>
                            <div class="fw-800 mt-1" style="color:var(--primary);">{{ $item['menu']->harga_format }}</div>
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-shrink-0">
                            <button class="btn btn-sm btn-outline-secondary" style="width:30px;height:30px;padding:0;" onclick="changeQty({{ $menuId }}, -1)">
                                <i class="bi bi-dash"></i>
                            </button>
                            <span class="fw-700" id="qty-{{ $menuId }}" style="min-width:20px;text-align:center;">{{ $item['jumlah'] }}</span>
                            <button class="btn btn-sm btn-outline-secondary" style="width:30px;height:30px;padding:0;" onclick="changeQty({{ $menuId }}, 1)">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                        <div class="text-end flex-shrink-0">
                            <div class="fw-800" id="subtotal-{{ $menuId }}" style="font-size:.95rem;">
                                Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                            </div>
                            <button class="btn btn-sm btn-link text-danger p-0 mt-1" style="font-size:.78rem;" onclick="removeItem({{ $menuId }})">
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card sticky-top" style="top:80px;">
            <div class="card-header fw-700"><i class="bi bi-receipt me-2"></i>Ringkasan</div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2" style="font-size:.9rem;">
                    <span class="text-muted">Subtotal</span>
                    <span class="fw-600" id="grandTotal">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3" style="font-size:.9rem;">
                    <span class="text-muted">Biaya Layanan</span>
                    <span class="fw-600 text-success">Gratis</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-4">
                    <span class="fw-800">Total</span>
                    <span class="fw-800" style="color:var(--primary);font-size:1.1rem;" id="totalFinal">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <a href="{{ route('siswa.checkout') }}" class="btn btn-primary w-100 fw-700 py-3" style="font-size:1rem;">
                    <i class="bi bi-bag-check-fill me-2"></i>Lanjut ke Checkout
                </a>
                <a href="{{ route('siswa.dashboard') }}" class="btn btn-outline-secondary w-100 mt-2 fw-600">
                    <i class="bi bi-arrow-left me-1"></i>Tambah Menu Lagi
                </a>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
const menuPrices = @json(collect($items ?? [])->map(fn($i) => $i['menu']->harga)->toArray());

function changeQty(menuId, delta) {
    const qtyEl = document.getElementById('qty-' + menuId);
    let qty = parseInt(qtyEl.textContent) + delta;
    if (qty < 1) { removeItem(menuId); return; }
    if (qty > 20) return;

    qtyEl.textContent = qty;
    updateSubtotal(menuId, qty);
    syncCart(menuId, qty);
}

function updateSubtotal(menuId, qty) {
    const price = menuPrices[menuId];
    if (!price) return;
    const sub = price * qty;
    document.getElementById('subtotal-' + menuId).textContent = 'Rp ' + sub.toLocaleString('id-ID');
    recalcTotal();
}

function recalcTotal() {
    let total = 0;
    document.querySelectorAll('[id^="subtotal-"]').forEach(el => {
        const val = parseInt(el.textContent.replace(/[^\d]/g, ''));
        total += val;
    });
    const formatted = 'Rp ' + total.toLocaleString('id-ID');
    document.getElementById('grandTotal').textContent = formatted;
    document.getElementById('totalFinal').textContent = formatted;
}

function syncCart(menuId, qty) {
    fetch('{{ route("siswa.keranjang.update") }}', {
        method: 'PATCH',
        headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ menu_id: menuId, jumlah: qty })
    });
}

function removeItem(menuId) {
    fetch('{{ route("siswa.keranjang.hapus") }}', {
        method: 'DELETE',
        headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ menu_id: menuId })
    }).then(() => {
        document.getElementById('cart-item-' + menuId)?.remove();
        recalcTotal();
    });
}

function clearCart() {
    Swal.fire({
        title: 'Kosongkan Keranjang?',
        text: 'Semua item akan dihapus.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#FF6B35',
        confirmButtonText: 'Ya, Kosongkan',
        cancelButtonText: 'Batal'
    }).then(r => {
        if (r.isConfirmed) {
            fetch('{{ route("siswa.keranjang.kosongkan") }}', {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            }).then(() => location.reload());
        }
    });
}
</script>
@endpush
