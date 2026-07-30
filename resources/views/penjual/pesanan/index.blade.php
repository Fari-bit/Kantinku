@extends('layouts.app')
@section('title','Pesanan Masuk')
@section('page-title','Pesanan Masuk')

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
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <h5 class="fw-800 mb-0">Pesanan Masuk</h5>
    <div class="d-flex gap-2 flex-wrap">
        @foreach(['' => 'Semua', 'menunggu' => 'Menunggu', 'dikonfirmasi' => 'Dikonfirmasi', 'diproses' => 'Diproses', 'siap' => 'Siap'] as $val => $label)
        <a href="{{ route('penjual.pesanan.index', $val ? ['status' => $val] : []) }}"
           class="btn btn-sm {{ request('status') === $val ? 'btn-primary' : 'btn-outline-secondary' }} fw-600"
           style="font-size:.8rem;">{{ $label }}</a>
        @endforeach
    </div>
</div>

<div class="d-flex flex-column gap-3">
    @forelse($pesanan as $p)
    <div class="card" id="card-pesanan-{{ $p->id }}">
        <div class="card-body">
            <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
                <div class="flex-1">
                    <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
                        <span class="fw-800" style="color:var(--primary);">{{ $p->kode_pesanan }}</span>
                        <span class="badge bg-{{ $p->status_badge['color'] }}" id="badge-{{ $p->id }}">
                            {{ $p->status_badge['label'] }}
                        </span>
                        <span class="badge {{ $p->metode_pembayaran === 'tunai' ? 'bg-warning text-dark' : 'bg-info text-white' }}">
                            {{ $p->metode_pembayaran === 'tunai' ? '💵 Tunai' : '🏦 Transfer' }}
                        </span>
                        {{-- Badge bukti transfer --}}
                        @if($p->metode_pembayaran === 'transfer')
                            @if($p->status_verifikasi_transfer === 'menunggu')
                            <span class="badge bg-danger" style="font-size:.7rem;">⚠️ Verifikasi Transfer</span>
                            @elseif($p->status_verifikasi_transfer === 'diterima')
                            <span class="badge bg-success" style="font-size:.7rem;">✅ Transfer OK</span>
                            @endif
                        @endif
                    </div>
                    <div class="fw-600 mb-1" style="font-size:.9rem;">{{ $p->siswa->name }}
                        @if($p->siswa->kelas)
                        <span class="text-muted fw-400" style="font-size:.82rem;"> · {{ $p->siswa->kelas }}</span>
                        @endif
                    </div>
                    <div class="text-muted mb-1" style="font-size:.85rem;">
                        {{ $p->details->map(fn($d) => $d->nama_menu . ' ×' . $d->jumlah)->implode(', ') }}
                    </div>
                    @if($p->catatan)
                    <div style="font-size:.82rem;background:#FFF7ED;border:1px solid #FED7AA;border-radius:8px;padding:.3rem .75rem;display:inline-block;">
                        📝 {{ $p->catatan }}
                    </div>
                    @endif
                </div>
                <div class="text-end flex-shrink-0">
                    <div class="fw-800" style="font-size:1.05rem;color:var(--primary);">{{ $p->total_format }}</div>
                    <div class="text-muted" style="font-size:.8rem;">{{ $p->created_at->diffForHumans() }}</div>
                </div>
            </div>

            {{-- Tombol Aksi — FIX #1 pakai JS --}}
            <div class="mt-3 pt-3 border-top d-flex gap-2 flex-wrap" id="actions-{{ $p->id }}">
                @if($p->status === 'menunggu')
                    <button class="btn btn-sm btn-primary fw-600"
                            onclick="updateStatus({{ $p->id }}, 'dikonfirmasi')">
                        <i class="bi bi-check-lg me-1"></i>Konfirmasi
                    </button>
                    <button class="btn btn-sm btn-outline-danger fw-600"
                            onclick="updateStatus({{ $p->id }}, 'dibatalkan')">Batalkan</button>
                @elseif($p->status === 'dikonfirmasi')
                    <button class="btn btn-sm btn-primary fw-600"
                            onclick="updateStatus({{ $p->id }}, 'diproses')">
                        <i class="bi bi-fire me-1"></i>Mulai Proses
                    </button>
                @elseif($p->status === 'diproses')
                    <button class="btn btn-sm btn-success fw-600"
                            onclick="updateStatus({{ $p->id }}, 'siap')">
                        <i class="bi bi-bell me-1"></i>Siap Diambil
                    </button>
                @elseif($p->status === 'siap')
                    <button class="btn btn-sm btn-secondary fw-600"
                            onclick="updateStatus({{ $p->id }}, 'selesai')">
                        <i class="bi bi-check2-all me-1"></i>Selesai
                    </button>
                @endif
                <a href="{{ route('penjual.pesanan.show', $p) }}"
                   class="btn btn-sm btn-outline-secondary fw-600">
                    <i class="bi bi-eye me-1"></i>Detail
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="card text-center py-5">
        <div style="font-size:3rem;">📭</div>
        <h5 class="fw-700 mt-3 mb-1">Tidak ada pesanan</h5>
        <p class="text-muted">Pesanan baru akan muncul di sini.</p>
    </div>
    @endforelse
</div>

@if($pesanan->hasPages())
<div class="mt-4">{{ $pesanan->links() }}</div>
@endif
@endsection

@push('scripts')
<script>
const statusConfig = {
    dikonfirmasi: { label: 'Dikonfirmasi', color: 'info',      next: 'diproses',  nextLabel: 'Mulai Proses',  nextIcon: 'bi-fire' },
    diproses:     { label: 'Diproses',     color: 'primary',   next: 'siap',      nextLabel: 'Siap Diambil',  nextIcon: 'bi-bell' },
    siap:         { label: 'Siap Diambil', color: 'success',   next: 'selesai',   nextLabel: 'Selesai',       nextIcon: 'bi-check2-all' },
    selesai:      { label: 'Selesai',      color: 'secondary', next: null },
    dibatalkan:   { label: 'Dibatalkan',   color: 'danger',    next: null },
};

function updateStatus(pesananId, status) {
    fetch(`/penjual/pesanan/${pesananId}/status`, {
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
            const cfg = statusConfig[status];

            // ── Update badge langsung ──────────────────────────
            const badge = document.getElementById(`badge-${pesananId}`);
            if (badge) {
                badge.textContent = cfg.label;
                badge.className   = `badge bg-${cfg.color}`;
            }

            // ── Update tombol aksi langsung ────────────────────
            const actions = document.getElementById(`actions-${pesananId}`);
            if (actions && cfg.next) {
                const nextCfg = statusConfig[cfg.next];
                actions.innerHTML = `
                    <button class="btn btn-sm btn-primary fw-600"
                            onclick="updateStatus(${pesananId}, '${cfg.next}')">
                        <i class="bi ${nextCfg ? 'bi-' + (cfg.nextIcon?.replace('bi-','') ?? 'check') : 'bi-check'} me-1"></i>
                        ${cfg.nextLabel}
                    </button>
                    <a href="/penjual/pesanan/${pesananId}" class="btn btn-sm btn-outline-secondary fw-600">
                        <i class="bi bi-eye me-1"></i>Detail
                    </a>`;
            } else if (actions && !cfg.next) {
                // Status final — hanya tombol detail
                actions.innerHTML = `
                    <a href="/penjual/pesanan/${pesananId}" class="btn btn-sm btn-outline-secondary fw-600">
                        <i class="bi bi-eye me-1"></i>Detail
                    </a>`;
            }

            // Toast sukses kecil
            Swal.fire({
                icon: 'success',
                title: 'Status Diperbarui!',
                text: cfg.label,
                toast: true,
                position: 'bottom-end',
                timer: 2000,
                showConfirmButton: false,
                timerProgressBar: true,
            });
        } else {
            Swal.fire('Gagal', data.error || 'Terjadi kesalahan.', 'error');
        }
    })
    .catch(() => Swal.fire('Error', 'Tidak dapat terhubung ke server.', 'error'));
}
</script>
@endpush
