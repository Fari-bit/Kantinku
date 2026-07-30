@extends('layouts.app')
@section('title','Detail Pesanan')
@section('page-title','Detail Pesanan')

@section('sidebar-links')
<span class="nav-section-label">Menu</span>
<a href="{{ route('siswa.dashboard') }}" class="sidebar-link"><i class="bi bi-house-fill"></i>Beranda</a>
<a href="{{ route('siswa.keranjang') }}" class="sidebar-link"><i class="bi bi-bag2"></i>Keranjang</a>
<span class="nav-section-label">Pesanan</span>
<a href="{{ route('siswa.pesanan.riwayat') }}" class="sidebar-link active"><i class="bi bi-clock-history"></i>Riwayat Pesanan</a>
@endsection

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('siswa.pesanan.riwayat') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <div>
        <h5 class="fw-800 mb-0">Detail Pesanan</h5>
        <div style="font-size:.85rem;color:var(--primary);font-weight:700;">{{ $pesanan->kode_pesanan }}</div>
    </div>
    <span class="badge bg-{{ $pesanan->status_badge['color'] }} ms-auto" id="statusBadge">
        {{ $pesanan->status_badge['label'] }}
    </span>
</div>

{{-- Status Tracker --}}
<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between position-relative">
            <div style="position:absolute;top:20px;left:10%;right:10%;height:2px;background:#E2E8F0;z-index:0;"></div>
            @php
            $steps    = ['menunggu','dikonfirmasi','diproses','siap','selesai'];
            $labels   = ['Menunggu','Dikonfirmasi','Diproses','Siap','Selesai'];
            $currIdx  = array_search($pesanan->status, $steps);
            if ($pesanan->status === 'dibatalkan') $currIdx = -1;
            @endphp
            @foreach($steps as $i => $step)
            <div class="text-center flex-fill" style="position:relative;z-index:1;">
                <div class="mx-auto d-flex align-items-center justify-content-center"
                     style="width:40px;height:40px;border-radius:50%;border:2px solid {{ $currIdx >= $i ? '#FF6B35' : '#E2E8F0' }};background:{{ $currIdx >= $i ? '#FF6B35' : '#fff' }};margin-bottom:.5rem;">
                    @if($currIdx >= $i)
                        <i class="bi bi-check-lg text-white fw-700"></i>
                    @else
                        <span style="color:#CBD5E0;font-size:.8rem;font-weight:700;">{{ $i+1 }}</span>
                    @endif
                </div>
                <div style="font-size:.72rem;font-weight:600;color:{{ $currIdx >= $i ? '#FF6B35' : '#A0AEC0' }};">{{ $labels[$i] }}</div>
            </div>
            @endforeach
        </div>
        @if($pesanan->status === 'dibatalkan')
        <div class="text-center mt-3"><span class="badge bg-danger px-3 py-2">Pesanan Dibatalkan</span></div>
        @elseif($pesanan->status === 'siap')
        <div class="text-center mt-3 p-3 rounded-3" style="background:#EBF8F0;border:1px solid #9AE6B4;">
            <div class="fw-700 text-success">🔔 Pesanan Anda Siap Diambil!</div>
            <div style="font-size:.85rem;color:#276749;">Silakan ambil di warung {{ $pesanan->penjual->penjualProfile?->nama_warung }}</div>
        </div>
        @endif
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">

        {{-- Item --}}
        <div class="card mb-4">
            <div class="card-header fw-700"><i class="bi bi-bag me-2"></i>Item yang Dipesan</div>
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

        {{-- FIX #2: Upload Bukti Transfer --}}
        @if($pesanan->metode_pembayaran === 'transfer')
        <div class="card mb-4">
            <div class="card-header fw-700">
                <i class="bi bi-bank me-2"></i>Pembayaran Transfer
            </div>
            <div class="card-body">
                {{-- Info Rekening Penjual --}}
                @php $profile = $pesanan->penjual->penjualProfile; @endphp
                @if($profile && $profile->terima_transfer && $profile->nomor_rekening)
                <div class="p-3 rounded-3 mb-4" style="background:#F0FDF4;border:1px solid #86EFAC;">
                    <div class="fw-700 mb-2" style="font-size:.88rem;color:#166534;">
                        <i class="bi bi-info-circle me-1"></i>Rekening Tujuan Transfer
                    </div>
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <div style="background:#fff;border-radius:10px;padding:.5rem 1rem;border:1px solid #D1FAE5;">
                            <div style="font-size:.75rem;color:#718096;">Bank</div>
                            <div class="fw-800" style="font-size:1rem;">{{ $profile->nama_bank }}</div>
                        </div>
                        <div style="background:#fff;border-radius:10px;padding:.5rem 1rem;border:1px solid #D1FAE5;">
                            <div style="font-size:.75rem;color:#718096;">Nomor Rekening</div>
                            <div class="fw-800" style="font-size:1.1rem;letter-spacing:2px;" id="nomorRek">{{ $profile->nomor_rekening }}</div>
                        </div>
                        <div style="background:#fff;border-radius:10px;padding:.5rem 1rem;border:1px solid #D1FAE5;">
                            <div style="font-size:.75rem;color:#718096;">Atas Nama</div>
                            <div class="fw-800">{{ $profile->nama_pemilik_rekening }}</div>
                        </div>
                    </div>
                    <div class="mt-3 fw-700" style="font-size:.88rem;">
                        Transfer sebesar: <span style="color:#16A34A;font-size:1rem;">{{ $pesanan->total_format }}</span>
                        (jumlah harus tepat)
                    </div>
                    <button class="btn btn-sm btn-outline-success mt-2" onclick="copyRekening()">
                        <i class="bi bi-clipboard me-1"></i>Salin Nomor Rekening
                    </button>
                    <span id="copyMsg" class="text-success fw-600 ms-2" style="font-size:.82rem;display:none;">✅ Tersalin!</span>
                </div>
                @endif

                {{-- Status Verifikasi --}}
                <div class="mb-3">
                    <div class="fw-700 mb-1" style="font-size:.9rem;">Status Pembayaran</div>
                    @if($pesanan->status_verifikasi_transfer === 'diterima')
                        <span class="badge bg-success px-3 py-2">✅ Transfer Diterima & Dikonfirmasi Penjual</span>
                    @elseif($pesanan->status_verifikasi_transfer === 'ditolak')
                        <span class="badge bg-danger px-3 py-2">❌ Transfer Ditolak</span>
                        @if($pesanan->catatan_verifikasi)
                        <div class="mt-2 p-2 rounded-3" style="background:#FFF2F2;font-size:.82rem;">
                            <strong>Catatan penjual:</strong> {{ $pesanan->catatan_verifikasi }}
                        </div>
                        @endif
                    @elseif($pesanan->status_verifikasi_transfer === 'menunggu')
                        <span class="badge bg-warning text-dark px-3 py-2">⏳ Menunggu Konfirmasi Penjual</span>
                    @else
                        <span class="badge bg-secondary px-3 py-2">Belum Upload Bukti Transfer</span>
                    @endif
                </div>

                {{-- Form Upload Bukti --}}
                @if($pesanan->status_verifikasi_transfer !== 'diterima' && !in_array($pesanan->status, ['selesai','dibatalkan']))
                <form action="{{ route('siswa.pesanan.bukti-transfer', $pesanan) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-2 fw-700" style="font-size:.9rem;">
                        {{ $pesanan->bukti_transfer ? 'Update' : 'Upload' }} Bukti Transfer
                    </div>

                    @if($pesanan->bukti_transfer)
                    <div class="mb-3">
                        <div class="text-muted mb-1" style="font-size:.82rem;">Bukti yang sudah dikirim:</div>
                        <img src="{{ $pesanan->bukti_transfer_url }}" class="rounded-3"
                             style="max-height:200px;max-width:100%;cursor:pointer;border:1px solid #E2E8F0;"
                             onclick="this.style.maxHeight = this.style.maxHeight === '200px' ? 'none' : '200px'"
                             title="Klik untuk perbesar" alt="Bukti Transfer">
                    </div>
                    @endif

                    <div class="input-group">
                        <input type="file" name="bukti_transfer" class="form-control" accept="image/*" required>
                        <button type="submit" class="btn btn-primary fw-700">
                            <i class="bi bi-upload me-1"></i>Kirim
                        </button>
                    </div>
                    <div class="form-text">Format JPG/PNG, max 3MB. Pastikan nominal terlihat jelas.</div>
                </form>
                @endif
            </div>
        </div>
        @endif

        {{-- FIX #5: Ulasan & Rating --}}
        @if($pesanan->status === 'selesai')
        <div class="card mb-4">
            <div class="card-header fw-700">
                <i class="bi bi-star me-2 text-warning"></i>Ulasan & Rating
            </div>
            <div class="card-body">
                @if($pesanan->ulasan)
                {{-- Sudah ada ulasan --}}
                <div class="text-center py-2">
                    <div style="font-size:2rem;margin-bottom:.5rem;">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi {{ $i <= $pesanan->ulasan->rating ? 'bi-star-fill text-warning' : 'bi-star text-muted' }}" style="font-size:1.5rem;"></i>
                        @endfor
                    </div>
                    <div class="fw-700 mb-1">Ulasan kamu sudah terkirim!</div>
                    @if($pesanan->ulasan->komentar)
                    <div class="p-3 rounded-3 text-start mx-auto" style="background:#F7F8FC;max-width:400px;font-size:.9rem;">
                        "{{ $pesanan->ulasan->komentar }}"
                    </div>
                    @endif
                    <div class="text-muted mt-2" style="font-size:.8rem;">
                        {{ $pesanan->ulasan->created_at->format('d M Y, H:i') }}
                    </div>
                </div>
                @else
                {{-- Form ulasan --}}
                <form action="{{ route('siswa.pesanan.ulasan', $pesanan) }}" method="POST" id="ulasanForm">
                    @csrf
                    <div class="mb-3">
                        <label class="fw-700 mb-2 d-block" style="font-size:.9rem;">Beri Rating <span class="text-danger">*</span></label>
                        <div class="d-flex gap-2" id="starContainer">
                            @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star star-btn" data-val="{{ $i }}"
                               style="font-size:2rem;cursor:pointer;color:#CBD5E0;transition:color .15s;"
                               onmouseover="hoverStar({{ $i }})"
                               onmouseout="resetStar()"
                               onclick="selectStar({{ $i }})"></i>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="ratingInput" value="">
                        <div id="ratingError" class="text-danger mt-1" style="font-size:.82rem;display:none;">
                            Pilih rating bintang terlebih dahulu.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="fw-700 mb-1 d-block" style="font-size:.9rem;">Komentar (Opsional)</label>
                        <textarea name="komentar" class="form-control" rows="3"
                                  placeholder="Bagaimana pengalaman makan kamu? Menu enak? Penyajian cepat?..."
                                  maxlength="500"></textarea>
                        <div class="form-text">Maks. 500 karakter</div>
                    </div>
                    <button type="submit" class="btn btn-warning fw-700 text-dark" id="btnUlasan">
                        <i class="bi bi-star-fill me-1"></i>Kirim Ulasan
                    </button>
                </form>
                @endif
            </div>
        </div>
        @endif

        {{-- Batalkan --}}
        @if($pesanan->status === 'menunggu')
        <form action="{{ route('siswa.pesanan.batalkan', $pesanan) }}" method="POST" id="cancelForm">
            @csrf @method('PATCH')
            <button type="button" class="btn btn-outline-danger fw-700 w-100"
                    data-confirm="Batalkan pesanan ini? Stok akan dikembalikan."
                    data-form="cancelForm">
                <i class="bi bi-x-circle me-1"></i>Batalkan Pesanan
            </button>
        </form>
        @endif
    </div>

    {{-- Info Panel --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header fw-700"><i class="bi bi-info-circle me-2"></i>Info Pesanan</div>
            <div class="card-body">
                <div class="d-flex justify-content-between py-2 border-bottom" style="font-size:.875rem;">
                    <span class="text-muted">Kode</span>
                    <span class="fw-700" style="color:var(--primary);">{{ $pesanan->kode_pesanan }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom" style="font-size:.875rem;">
                    <span class="text-muted">Warung</span>
                    <span class="fw-600">{{ $pesanan->penjual->penjualProfile?->nama_warung ?? $pesanan->penjual->name }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom" style="font-size:.875rem;">
                    <span class="text-muted">Pembayaran</span>
                    <span class="fw-600">{{ $pesanan->metode_pembayaran === 'tunai' ? '💵 Tunai' : '🏦 Transfer' }}</span>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom" style="font-size:.875rem;">
                    <span class="text-muted">Status Bayar</span>
                    <span class="badge {{ $pesanan->status_pembayaran === 'sudah_bayar' ? 'bg-success' : 'bg-warning text-dark' }}">
                        {{ $pesanan->status_pembayaran === 'sudah_bayar' ? 'Lunas' : 'Belum Bayar' }}
                    </span>
                </div>
                <div class="d-flex justify-content-between py-2" style="font-size:.875rem;">
                    <span class="text-muted">Waktu</span>
                    <span class="fw-600">{{ $pesanan->created_at->format('d M Y, H:i') }}</span>
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
@endsection

@push('scripts')
<script>
// ── FIX #1: Auto polling status pesanan ─────────────────────────
@if(!in_array($pesanan->status, ['selesai', 'dibatalkan']))
let lastStatus = '{{ $pesanan->status }}';

function pollStatus() {
    fetch('{{ route("siswa.pesanan.detail", $pesanan) }}', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.text())
    .then(html => {
        const parser   = new DOMParser();
        const doc      = parser.parseFromString(html, 'text/html');
        const newBadge = doc.getElementById('statusBadge');
        if (newBadge) {
            const newStatus = newBadge.textContent.trim();
            const oldStatus = document.getElementById('statusBadge').textContent.trim();
            if (newStatus !== oldStatus) {
                // Status berubah — reload halaman otomatis
                location.reload();
            }
        }
    })
    .catch(() => {}); // silent fail
}

// Poll setiap 10 detik
setInterval(pollStatus, 10000);
@endif

// ── Salin nomor rekening ─────────────────────────────────────────
function copyRekening() {
    const el = document.getElementById('nomorRek');
    if (!el) return;
    navigator.clipboard.writeText(el.textContent.trim()).then(() => {
        const msg = document.getElementById('copyMsg');
        msg.style.display = 'inline';
        setTimeout(() => msg.style.display = 'none', 2000);
    });
}

// ── FIX #5: Rating bintang ──────────────────────────────────────
let selectedRating = 0;

function hoverStar(val) {
    document.querySelectorAll('.star-btn').forEach((s, i) => {
        s.className = 'bi ' + (i < val ? 'bi-star-fill' : 'bi-star') + ' star-btn';
        s.style.color = i < val ? '#F6AD55' : '#CBD5E0';
    });
}

function resetStar() {
    hoverStar(selectedRating);
}

function selectStar(val) {
    selectedRating = val;
    document.getElementById('ratingInput').value = val;
    document.getElementById('ratingError').style.display = 'none';
    hoverStar(val);
}

document.getElementById('ulasanForm')?.addEventListener('submit', function(e) {
    if (!selectedRating) {
        e.preventDefault();
        document.getElementById('ratingError').style.display = 'block';
    }
});
</script>
@endpush
