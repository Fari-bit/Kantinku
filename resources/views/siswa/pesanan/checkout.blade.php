@extends('layouts.app')
@section('title','Checkout')
@section('page-title','Checkout')

@section('sidebar-links')
<span class="nav-section-label">Menu</span>
<a href="{{ route('siswa.dashboard') }}" class="sidebar-link"><i class="bi bi-house-fill"></i>Beranda</a>
<a href="{{ route('siswa.keranjang') }}" class="sidebar-link"><i class="bi bi-bag2"></i>Keranjang</a>
<span class="nav-section-label">Pesanan</span>
<a href="{{ route('siswa.pesanan.riwayat') }}" class="sidebar-link"><i class="bi bi-clock-history"></i>Riwayat Pesanan</a>
@endsection

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('siswa.keranjang') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="fw-800 mb-0">Konfirmasi Pesanan</h5>
</div>

<form action="{{ route('siswa.checkout.proses') }}" method="POST">
    @csrf
    <div class="row g-4">
        <div class="col-lg-8">
            {{-- Detail Pesanan --}}
            <div class="card mb-4">
                <div class="card-header fw-700">
                    <i class="bi bi-shop me-2"></i>{{ $penjual->penjualProfile?->nama_warung ?? $penjual->name }}
                </div>
                <div class="card-body p-0">
                    @foreach($items as $d)
                    <div class="d-flex align-items-center gap-3 p-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div style="width:48px;height:48px;border-radius:10px;overflow:hidden;flex-shrink:0;background:#FFF0EB;display:flex;align-items:center;justify-content:center;font-size:1.3rem;">
                            @if($d['menu']->foto)
                                <img src="{{ asset('storage/'.$d['menu']->foto) }}" style="width:100%;height:100%;object-fit:cover;" alt="">
                            @else 🍽️ @endif
                        </div>
                        <div class="flex-1">
                            <div class="fw-700" style="font-size:.9rem;">{{ $d['menu']->nama }}</div>
                            <div class="text-muted" style="font-size:.78rem;">{{ $d['menu']->harga_format }} × {{ $d['item']['jumlah'] }}</div>
                        </div>
                        <div class="fw-800" style="color:var(--primary);">Rp {{ number_format($d['subtotal'],0,',','.') }}</div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Metode Pembayaran --}}
            <div class="card mb-4">
                <div class="card-header fw-700"><i class="bi bi-credit-card me-2"></i>Metode Pembayaran</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="d-flex align-items-center gap-3 p-3 rounded-3 border cursor-pointer" style="cursor:pointer;" id="lbl-tunai">
                                <input type="radio" name="metode_pembayaran" value="tunai" checked onchange="selectPembayaran('tunai')" style="display:none;">
                                <div style="width:44px;height:44px;background:#FFFBEB;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.4rem;">💵</div>
                                <div>
                                    <div class="fw-700">Tunai</div>
                                    <div class="text-muted" style="font-size:.8rem;">Bayar saat mengambil</div>
                                </div>
                            </label>
                        </div>
                        <div class="col-sm-6">
                            <label class="d-flex align-items-center gap-3 p-3 rounded-3 border cursor-pointer" style="cursor:pointer;" id="lbl-transfer">
                                <input type="radio" name="metode_pembayaran" value="transfer" onchange="selectPembayaran('transfer')" style="display:none;">
                                <div style="width:44px;height:44px;background:#EBF8F0;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.4rem;">💳</div>
                                <div>
                                    <div class="fw-700">Transfer</div>
                                    <div class="text-muted" style="font-size:.8rem;">Transfer bank / e-wallet</div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Catatan --}}
            <div class="card">
                <div class="card-header fw-700"><i class="bi bi-chat-text me-2"></i>Catatan (Opsional)</div>
                <div class="card-body">
                    <textarea name="catatan" class="form-control" rows="3"
                              placeholder="Contoh: tidak pakai kecap, tambah nasi, dll..."></textarea>
                </div>
            </div>
        </div>

        {{-- Summary --}}
        <div class="col-lg-4">
            <div class="card sticky-top" style="top:80px;">
                <div class="card-header fw-700"><i class="bi bi-receipt me-2"></i>Ringkasan Pembayaran</div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2" style="font-size:.9rem;">
                        <span class="text-muted">Subtotal</span>
                        <span class="fw-600">Rp {{ number_format($total,0,',','.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2" style="font-size:.9rem;">
                        <span class="text-muted">Biaya Layanan</span>
                        <span class="fw-600 text-success">Gratis</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-800 fs-6">Total</span>
                        <span class="fw-800 fs-5" style="color:var(--primary);">Rp {{ number_format($total,0,',','.') }}</span>
                    </div>

                    <div class="mb-3 p-3 rounded-3" style="background:#F7F8FC;border:1px solid #E2E8F0;">
                        <div class="fw-700 mb-1" style="font-size:.85rem;">Memesan dari:</div>
                        <div style="font-size:.88rem;">{{ $penjual->penjualProfile?->nama_warung ?? $penjual->name }}</div>
                        @if($penjual->penjualProfile?->nomor_stand)
                        <div class="text-muted" style="font-size:.8rem;">Stand {{ $penjual->penjualProfile->nomor_stand }}</div>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-700 py-3" style="font-size:1rem;">
                        <i class="bi bi-bag-check-fill me-2"></i>Pesan Sekarang
                    </button>
                    <a href="{{ route('siswa.keranjang') }}" class="btn btn-outline-secondary w-100 mt-2">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
function selectPembayaran(type) {
    document.getElementById('lbl-tunai').style.borderColor = type === 'tunai' ? '#FF6B35' : '#E2E8F0';
    document.getElementById('lbl-tunai').style.background = type === 'tunai' ? '#FFF0EB' : '';
    document.getElementById('lbl-transfer').style.borderColor = type === 'transfer' ? '#FF6B35' : '#E2E8F0';
    document.getElementById('lbl-transfer').style.background = type === 'transfer' ? '#FFF0EB' : '';
}
selectPembayaran('tunai');
</script>
@endpush
