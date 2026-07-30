@extends('layouts.app')
@section('title','Detail Pesanan')
@section('page-title','Detail Pesanan')

@section('sidebar-links')
<span class="nav-section-label">Utama</span>
<a href="{{ route('penjual.dashboard') }}" class="sidebar-link"><i class="bi bi-grid-fill"></i>Dashboard</a>
<span class="nav-section-label">Operasional</span>
<a href="{{ route('penjual.pesanan.index') }}" class="sidebar-link active"><i class="bi bi-bag-check"></i>Pesanan Masuk</a>
<a href="{{ route('penjual.pesanan.riwayat') }}" class="sidebar-link"><i class="bi bi-clock-history"></i>Riwayat Pesanan</a>
<a href="{{ route('penjual.ulasan.index') }}" class="sidebar-link"><i class="bi bi-star"></i>Ulasan</a>
<span class="nav-section-label">Menu</span>
<a href="{{ route('penjual.menu.index') }}" class="sidebar-link"><i class="bi bi-grid-3x3-gap"></i>Daftar Menu</a>
<a href="{{ route('penjual.menu.create') }}" class="sidebar-link"><i class="bi bi-plus-circle"></i>Tambah Menu</a>
@endsection

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('penjual.pesanan.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <div>
        <h5 class="fw-800 mb-0">Detail Pesanan</h5>
        <div style="font-size:.85rem;color:var(--primary);font-weight:700;">{{ $pesanan->kode_pesanan }}</div>
    </div>
    <span class="badge bg-{{ $pesanan->status_badge['color'] }} ms-auto" id="statusBadge">
        {{ $pesanan->status_badge['label'] }}
    </span>
</div>

<div class="row g-4">
    <div class="col-lg-8">

        {{-- Items --}}
        <div class="card mb-4">
            <div class="card-header fw-700"><i class="bi bi-bag me-2"></i>Item Pesanan</div>
            <div class="card-body p-0">
                @foreach($pesanan->details as $d)
                <div class="d-flex align-items-center gap-3 p-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div style="width:50px;height:50px;border-radius:12px;overflow:hidden;flex-shrink:0;background:#FFF0EB;display:flex;align-items:center;justify-content:center;font-size:1.3rem;">
                        @if($d->menu?->foto) <img src="{{ asset('storage/'.$d->menu->foto) }}" style="width:100%;height:100%;object-fit:cover;"> @else 🍽️ @endif
                    </div>
                    <div class="flex-1">
                        <div class="fw-700" style="font-size:.9rem;">{{ $d->nama_menu }}</div>
                        <div class="text-muted" style="font-size:.8rem;">Rp {{ number_format($d->harga_satuan,0,',','.') }} × {{ $d->jumlah }}</div>
                        @if($d->catatan)<div style="font-size:.78rem;color:#718096;">📝 {{ $d->catatan }}</div>@endif
                    </div>
                    <div class="fw-800" style="color:var(--primary);">Rp {{ number_format($d->subtotal,0,',','.') }}</div>
                </div>
                @endforeach
            </div>
            <div class="card-footer bg-transparent">
                <div class="d-flex justify-content-between fw-800" style="font-size:1rem;">
                    <span>Total</span>
                    <span style="color:var(--primary);">{{ $pesanan->total_format }}</span>
                </div>
            </div>
        </div>

        {{-- FIX #2: Bukti Transfer & Verifikasi --}}
        @if($pesanan->metode_pembayaran === 'transfer')
        <div class="card mb-4">
            <div class="card-header fw-700">
                <i class="bi bi-bank me-2"></i>Verifikasi Pembayaran Transfer
            </div>
            <div class="card-body">
                {{-- Info rekening sendiri --}}
                @php $profile = auth()->user()->penjualProfile; @endphp
                @if($profile?->terima_transfer)
                <div class="p-3 rounded-3 mb-4" style="background:#F7F8FC;border:1px solid #E2E8F0;">
                    <div class="fw-700 mb-1" style="font-size:.85rem;">Rekening Kamu</div>
                    <div style="font-size:.9rem;">
                        <strong>{{ $profile->nama_bank }}</strong> —
                        <span style="letter-spacing:1px;">{{ $profile->nomor_rekening }}</span>
                        a/n {{ $profile->nama_pemilik_rekening }}
                    </div>
                </div>
                @endif

                {{-- Status --}}
                <div class="mb-3">
                    <div class="fw-700 mb-2" style="font-size:.9rem;">Status Verifikasi</div>
                    @if($pesanan->status_verifikasi_transfer === 'diterima')
                        <span class="badge bg-success px-3 py-2">✅ Transfer Diterima</span>
                    @elseif($pesanan->status_verifikasi_transfer === 'ditolak')
                        <span class="badge bg-danger px-3 py-2">❌ Transfer Ditolak</span>
                    @elseif($pesanan->status_verifikasi_transfer === 'menunggu')
                        <span class="badge bg-warning text-dark px-3 py-2">⏳ Menunggu Verifikasi Kamu</span>
                    @else
                        <span class="badge bg-secondary px-3 py-2">Siswa belum upload bukti</span>
                    @endif
                </div>

                {{-- Tampilkan bukti transfer --}}
                @if($pesanan->bukti_transfer)
                <div class="mb-4">
                    <div class="fw-700 mb-2" style="font-size:.9rem;">Bukti Transfer dari Siswa</div>
                    <img src="{{ $pesanan->bukti_transfer_url }}"
                         class="rounded-3 img-fluid"
                         style="max-height:300px;cursor:pointer;border:1px solid #E2E8F0;"
                         onclick="openImage(this.src)"
                         title="Klik untuk perbesar"
                         alt="Bukti Transfer">
                    <div class="mt-2">
                        <a href="{{ $pesanan->bukti_transfer_url }}" target="_blank"
                           class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-zoom-in me-1"></i>Lihat Full Size
                        </a>
                    </div>
                </div>

                {{-- Form Verifikasi --}}
                @if($pesanan->status_verifikasi_transfer === 'menunggu')
                <form action="{{ route('penjual.pesanan.verifikasi-transfer', $pesanan) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="fw-700 mb-1 d-block" style="font-size:.88rem;">
                            Catatan (wajib jika ditolak)
                        </label>
                        <textarea name="catatan_verifikasi" class="form-control" rows="2"
                                  placeholder="Contoh: Nominal tidak sesuai, bukti tidak jelas..."></textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" name="aksi" value="diterima"
                                class="btn btn-success fw-700 flex-fill"
                                onclick="return confirm('Konfirmasi transfer diterima?')">
                            <i class="bi bi-check-circle me-1"></i>Terima Transfer
                        </button>
                        <button type="submit" name="aksi" value="ditolak"
                                class="btn btn-danger fw-700 flex-fill"
                                onclick="return confirm('Tolak transfer ini? Siswa perlu upload ulang.')">
                            <i class="bi bi-x-circle me-1"></i>Tolak Transfer
                        </button>
                    </div>
                </form>
                @endif
                @else
                <div class="text-center py-3 text-muted" style="font-size:.88rem;">
                    <i class="bi bi-image" style="font-size:2rem;display:block;margin-bottom:.5rem;"></i>
                    Siswa belum mengirimkan bukti transfer
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- Update Status — FIX #1: tombol pakai JS dengan auto-update badge --}}
        @if(!in_array($pesanan->status, ['selesai','dibatalkan']))
        <div class="card">
            <div class="card-header fw-700"><i class="bi bi-arrow-repeat me-2"></i>Update Status Pesanan</div>
            <div class="card-body">
                @if($pesanan->metode_pembayaran === 'transfer' && $pesanan->status_verifikasi_transfer !== 'diterima' && $pesanan->status === 'menunggu')
                <div class="alert alert-warning py-2 mb-3" style="font-size:.85rem;border-radius:10px;">
                    ⚠️ Transfer belum diverifikasi. Verifikasi pembayaran dulu sebelum konfirmasi pesanan.
                </div>
                @endif
                <div class="d-flex gap-2 flex-wrap">
                    @if($pesanan->status === 'menunggu')
                        <button class="btn btn-primary fw-700" onclick="updateStatus('dikonfirmasi')">
                            <i class="bi bi-check-lg me-1"></i>Konfirmasi Pesanan
                        </button>
                        <button class="btn btn-outline-danger fw-700" onclick="updateStatus('dibatalkan')">Batalkan</button>
                    @elseif($pesanan->status === 'dikonfirmasi')
                        <button class="btn btn-primary fw-700" onclick="updateStatus('diproses')">
                            <i class="bi bi-fire me-1"></i>Mulai Memasak
                        </button>
                    @elseif($pesanan->status === 'diproses')
                        <button class="btn btn-success fw-700" onclick="updateStatus('siap')">
                            <i class="bi bi-bell me-1"></i>Siap Diambil
                        </button>
                    @elseif($pesanan->status === 'siap')
                        <button class="btn btn-secondary fw-700" onclick="updateStatus('selesai')">
                            <i class="bi bi-check2-all me-1"></i>Selesai
                        </button>
                    @endif
                </div>
            </div>
        </div>
        @endif

        {{-- Ulasan --}}
        @if($pesanan->ulasan)
        <div class="card mt-4">
            <div class="card-header fw-700"><i class="bi bi-star-fill text-warning me-2"></i>Ulasan Siswa</div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <img src="{{ $pesanan->ulasan->siswa->avatar_url }}" class="rounded-circle"
                         style="width:38px;height:38px;object-fit:cover;" alt="">
                    <div class="flex-1">
                        <div class="fw-700" style="font-size:.875rem;">{{ $pesanan->ulasan->siswa->name }}</div>
                        <div>
                            @for($i = 1; $i <= 5; $i++)
                            <i class="bi {{ $i <= $pesanan->ulasan->rating ? 'bi-star-fill text-warning' : 'bi-star text-muted' }}"
                               style="font-size:.9rem;"></i>
                            @endfor
                            <span class="text-muted ms-1" style="font-size:.8rem;">
                                {{ $pesanan->ulasan->created_at->format('d M Y') }}
                            </span>
                        </div>
                        @if($pesanan->ulasan->komentar)
                        <div class="mt-1" style="font-size:.875rem;">{{ $pesanan->ulasan->komentar }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Info Pembeli --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header fw-700"><i class="bi bi-person me-2"></i>Informasi Pembeli</div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom">
                    <img src="{{ $pesanan->siswa->avatar_url }}" class="rounded-circle"
                         style="width:44px;height:44px;object-fit:cover;">
                    <div>
                        <div class="fw-700" style="font-size:.9rem;">{{ $pesanan->siswa->name }}</div>
                        <div class="text-muted" style="font-size:.8rem;">{{ $pesanan->siswa->kelas ?? 'Siswa' }}</div>
                    </div>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom" style="font-size:.875rem;">
                    <span class="text-muted">Kode</span>
                    <span class="fw-700" style="color:var(--primary);">{{ $pesanan->kode_pesanan }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom" style="font-size:.875rem;">
                    <span class="text-muted">Pembayaran</span>
                    <span class="fw-600">{{ $pesanan->metode_pembayaran === 'tunai' ? '💵 Tunai' : '🏦 Transfer' }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom" style="font-size:.875rem;">
                    <span class="text-muted">Status Bayar</span>
                    <span class="badge {{ $pesanan->status_pembayaran === 'sudah_bayar' ? 'bg-success' : 'bg-warning text-dark' }}">
                        {{ $pesanan->status_pembayaran === 'sudah_bayar' ? '✅ Lunas' : '⏳ Belum' }}
                    </span>
                </div>
                <div class="d-flex justify-content-between py-2" style="font-size:.875rem;">
                    <span class="text-muted">Waktu</span>
                    <span class="fw-600">{{ $pesanan->created_at->format('d M, H:i') }}</span>
                </div>
                @if($pesanan->catatan)
                <div class="mt-3 p-2 rounded-3" style="background:#FFF7ED;border:1px solid #FED7AA;font-size:.82rem;">
                    📝 {{ $pesanan->catatan }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Image Modal --}}
<div id="imgModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.85);z-index:9999;align-items:center;justify-content:center;cursor:pointer;" onclick="this.style.display='none'">
    <img id="imgModalSrc" src="" style="max-width:90vw;max-height:90vh;border-radius:12px;object-fit:contain;" alt="Bukti Transfer">
</div>
@endsection

@push('scripts')
<script>
// ── FIX #1: updateStatus → auto update badge tanpa manual refresh ──
function updateStatus(status) {
    const labels = {
        dikonfirmasi: 'Dikonfirmasi',
        diproses:     'Diproses',
        siap:         'Siap Diambil',
        selesai:      'Selesai',
        dibatalkan:   'Dibatalkan',
    };
    const colors = {
        dikonfirmasi: 'info',
        diproses:     'primary',
        siap:         'success',
        selesai:      'secondary',
        dibatalkan:   'danger',
    };

    fetch(`/penjual/pesanan/{{ $pesanan->id }}/status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ status }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            // Update badge langsung tanpa reload
            const badge = document.getElementById('statusBadge');
            badge.textContent = labels[status] || status;
            badge.className   = `badge bg-${colors[status] || 'secondary'} ms-auto`;

            Swal.fire({
                icon: 'success',
                title: 'Status Diperbarui!',
                text: `Pesanan sekarang: ${labels[status]}`,
                timer: 1800,
                showConfirmButton: false,
            }).then(() => {
                // Reload halaman agar tombol aksi ikut berubah
                location.reload();
            });
        } else {
            Swal.fire('Gagal', data.error || 'Terjadi kesalahan.', 'error');
        }
    })
    .catch(() => Swal.fire('Error', 'Tidak dapat terhubung ke server.', 'error'));
}

// Lihat gambar fullscreen
function openImage(src) {
    document.getElementById('imgModalSrc').src = src;
    document.getElementById('imgModal').style.display = 'flex';
}
</script>
@endpush
