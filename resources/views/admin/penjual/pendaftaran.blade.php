@extends('layouts.app')
@section('title','Pendaftaran Penjual')
@section('page-title','Pendaftaran Penjual')

@section('sidebar-links')
<span class="nav-section-label">Utama</span>
<a href="{{ route('admin.dashboard') }}" class="sidebar-link"><i class="bi bi-grid-fill"></i>Dashboard</a>
<span class="nav-section-label">Manajemen</span>
<a href="{{ route('admin.penjual.index') }}" class="sidebar-link"><i class="bi bi-shop"></i>Penjual</a>
<a href="{{ route('admin.penjual.pendaftaran') }}" class="sidebar-link active"><i class="bi bi-person-check"></i>Pendaftaran Penjual</a>
<a href="{{ route('admin.siswa.index') }}" class="sidebar-link"><i class="bi bi-people"></i>Siswa</a>
<a href="{{ route('admin.recycle-bin.index') }}" class="sidebar-link"><i class="bi bi-trash3"></i>Recycle Bin</a>
<span class="nav-section-label">Laporan</span>
<a href="{{ route('admin.laporan.index') }}" class="sidebar-link"><i class="bi bi-bar-chart-fill"></i>Laporan</a>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="fw-800 mb-0">Pendaftaran Penjual</h5>
        <div class="text-muted" style="font-size:.85rem;">{{ $pendaftaran->total() }} pendaftaran masuk</div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">#</th>
                        <th>Nama / Warung</th>
                        <th>Kontak</th>
                        <th>Jenis Makanan</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendaftaran as $d)
                    <tr>
                        <td class="ps-3 text-muted" style="font-size:.85rem;">{{ $pendaftaran->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="fw-700" style="font-size:.875rem;">{{ $d->nama_warung }}</div>
                            <div style="font-size:.78rem;color:#718096;">{{ $d->nama_lengkap }}</div>
                        </td>
                        <td>
                            <div style="font-size:.85rem;">{{ $d->email }}</div>
                            <div style="font-size:.78rem;color:#718096;">{{ $d->phone }}</div>
                        </td>
                        <td style="font-size:.85rem;">{{ $d->jenis_makanan }}</td>
                        <td style="font-size:.8rem;color:#718096;">{{ $d->created_at->format('d M Y') }}</td>
                        <td>
                            <span class="badge bg-{{ $d->status_badge['color'] }}">{{ $d->status_badge['label'] }}</span>
                        </td>
                        <td>
                            <a href="{{ route('admin.penjual.pendaftaran.show', $d) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye me-1"></i>Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-5">Belum ada pendaftaran penjual.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($pendaftaran->hasPages())
    <div class="card-footer bg-transparent">{{ $pendaftaran->links() }}</div>
    @endif
</div>
@endsection
