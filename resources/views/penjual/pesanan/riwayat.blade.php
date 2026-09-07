@extends('layouts.app')
@section('title','Riwayat Pesanan')
@section('page-title','Riwayat Pesanan')

@section('sidebar-links')
<span class="nav-section-label">Utama</span>
<a href="{{ route('penjual.dashboard') }}" class="sidebar-link"><i class="bi bi-grid-fill"></i>Dashboard</a>
<span class="nav-section-label">Operasional</span>
<a href="{{ route('penjual.pesanan.index') }}" class="sidebar-link"><i class="bi bi-bag-check"></i>Pesanan Masuk</a>
<a href="{{ route('penjual.pesanan.riwayat') }}" class="sidebar-link active"><i class="bi bi-clock-history"></i>Riwayat Pesanan</a>
<a href="{{ route('penjual.ulasan.index') }}" class="sidebar-link {{ request()->routeIs('penjual.ulasan.*') ? 'active' : '' }}">
    <i class="bi bi-star"></i>Ulasan
</a>
<span class="nav-section-label">Menu</span>
<a href="{{ route('penjual.menu.index') }}" class="sidebar-link"><i class="bi bi-grid-3x3-gap"></i>Daftar Menu</a>
<a href="{{ route('penjual.menu.create') }}" class="sidebar-link"><i class="bi bi-plus-circle"></i>Tambah Menu</a>
<a href="{{ route('penjual.recycle-bin.index') }}" class="sidebar-link {{ request()->routeIs('penjual.recycle-bin.*') ? 'active' : '' }}"><i class="bi bi-trash3"></i>Recycle Bin</a>

@endsection

@section('content')
<h5 class="fw-800 mb-4">Riwayat Pesanan</h5>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">Kode</th>
                        <th>Pembeli</th>
                        <th>Item</th>
                        <th>Total</th>
                        <th>Pembayaran</th>
                        <th>Status</th>
                        <th>Waktu</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pesanan as $p)
                    <tr>
                        <td class="ps-3">
                            <span class="fw-700" style="color:var(--primary);font-size:.85rem;">{{ $p->kode_pesanan }}</span>
                        </td>
                        <td style="font-size:.875rem;">{{ $p->siswa->name }}</td>
                        <td style="font-size:.82rem;color:#718096;">
                            {{ $p->details->count() }} item
                        </td>
                        <td><span class="fw-700" style="font-size:.875rem;">{{ $p->total_format }}</span></td>
                        <td style="font-size:.82rem;">
                            {{ $p->metode_pembayaran === 'tunai' ? '💵 Tunai' : '💳 Transfer' }}
                        </td>
                        <td><span class="badge bg-{{ $p->status_badge['color'] }}">{{ $p->status_badge['label'] }}</span></td>
                        <td style="font-size:.8rem;color:#718096;">{{ $p->created_at->format('d M, H:i') }}</td>
                        <td>
                            <a href="{{ route('penjual.pesanan.show', $p) }}" class="btn btn-sm btn-outline-primary" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">Belum ada riwayat pesanan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($pesanan->hasPages())
    <div class="card-footer bg-transparent">{{ $pesanan->links() }}</div>
    @endif
</div>
@endsection
