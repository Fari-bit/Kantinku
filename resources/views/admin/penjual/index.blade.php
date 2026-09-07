@extends('layouts.app')
@section('title','Kelola Penjual')
@section('page-title','Kelola Penjual')

@section('sidebar-links')
<span class="nav-section-label">Utama</span>
<a href="{{ route('admin.dashboard') }}" class="sidebar-link"><i class="bi bi-grid-fill"></i>Dashboard</a>
<span class="nav-section-label">Manajemen</span>
<a href="{{ route('admin.penjual.index') }}" class="sidebar-link active"><i class="bi bi-shop"></i>Penjual</a>
<a href="{{ route('admin.penjual.pendaftaran') }}" class="sidebar-link"><i class="bi bi-person-check"></i>Pendaftaran Penjual</a>
<a href="{{ route('admin.siswa.index') }}" class="sidebar-link"><i class="bi bi-people"></i>Siswa</a>
<a href="{{ route('admin.recycle-bin.index') }}" class="sidebar-link"><i class="bi bi-trash3"></i>Recycle Bin</a>
<span class="nav-section-label">Laporan</span>
<a href="{{ route('admin.laporan.index') }}" class="sidebar-link"><i class="bi bi-bar-chart-fill"></i>Laporan</a>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="fw-800 mb-0">Daftar Penjual</h5>
        <div class="text-muted" style="font-size:.85rem;">{{ $penjual->total() }} penjual terdaftar</div>
    </div>
    <a href="{{ route('admin.penjual.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Tambah Penjual
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">#</th>
                        <th>Penjual</th>
                        <th>Warung</th>
                        <th>Stand</th>
                        <th>Menu</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penjual as $p)
                    <tr>
                        <td class="ps-3 text-muted" style="font-size:.85rem;">{{ $penjual->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $p->avatar_url }}" width="36" height="36" class="rounded-circle object-fit-cover">
                                <div>
                                    <div class="fw-600" style="font-size:.875rem;">{{ $p->name }}</div>
                                    <div style="font-size:.78rem;color:#718096;">{{ $p->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="font-size:.875rem;">{{ $p->penjualProfile?->nama_warung ?? '-' }}</td>
                        <td style="font-size:.875rem;">{{ $p->penjualProfile?->nomor_stand ?? '-' }}</td>
                        <td><span class="badge bg-light text-dark fw-700">{{ $p->menus_count }}</span></td>
                        <td>
                            <span class="badge {{ $p->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                {{ $p->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.penjual.edit', $p) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.penjual.destroy', $p) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-confirm="Hapus penjual {{ $p->name }}? Semua data terkait akan ikut terhapus." data-form="{{ 'del-'.$p->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-5">Belum ada penjual. <a href="{{ route('admin.penjual.create') }}">Tambah sekarang</a></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($penjual->hasPages())
    <div class="card-footer bg-transparent">
        {{ $penjual->links() }}
    </div>
    @endif
</div>
@endsection
