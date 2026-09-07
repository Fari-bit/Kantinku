@extends('layouts.app')
@section('title','Recycle Bin')
@section('page-title','Recycle Bin')

@section('sidebar-links')
<span class="nav-section-label">Utama</span>
<a href="{{ route('admin.dashboard') }}" class="sidebar-link"><i class="bi bi-grid-fill"></i>Dashboard</a>
<span class="nav-section-label">Manajemen</span>
<a href="{{ route('admin.penjual.index') }}" class="sidebar-link"><i class="bi bi-shop"></i>Penjual</a>
<a href="{{ route('admin.penjual.pendaftaran') }}" class="sidebar-link"><i class="bi bi-person-check"></i>Pendaftaran Penjual</a>
<a href="{{ route('admin.siswa.index') }}" class="sidebar-link"><i class="bi bi-people"></i>Siswa</a>
<a href="{{ route('admin.recycle-bin.index') }}" class="sidebar-link active"><i class="bi bi-trash3"></i>Recycle Bin</a>
<span class="nav-section-label">Laporan</span>
<a href="{{ route('admin.laporan.index') }}" class="sidebar-link"><i class="bi bi-bar-chart-fill"></i>Laporan</a>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
        <h5 class="fw-800 mb-0"><i class="bi bi-trash3 me-2"></i>Recycle Bin — Akun</h5>
        <div class="text-muted" style="font-size:.85rem;">
            Akun penjual & siswa yang dihapus akan tersimpan di sini sebelum dihapus permanen.
        </div>
    </div>
    @if($users->total() > 0)
    <form action="{{ route('admin.recycle-bin.empty') }}" method="POST" id="form-empty-bin">
        @csrf @method('DELETE')
        <button type="button" class="btn btn-outline-danger" data-confirm="Kosongkan seluruh Recycle Bin? Semua akun di dalamnya akan terhapus PERMANEN dan tidak bisa dipulihkan lagi." data-form="form-empty-bin">
            <i class="bi bi-x-octagon me-1"></i>Kosongkan Bin
        </button>
    </form>
    @endif
</div>

{{-- Filter role --}}
<ul class="nav nav-pills mb-3" style="font-size:.85rem;">
    <li class="nav-item">
        <a class="nav-link {{ !$role ? 'active' : '' }}" href="{{ route('admin.recycle-bin.index') }}">
            Semua <span class="badge bg-light text-dark ms-1">{{ $totalPenjual + $totalSiswa }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $role === 'penjual' ? 'active' : '' }}" href="{{ route('admin.recycle-bin.index', ['role' => 'penjual']) }}">
            Penjual <span class="badge bg-light text-dark ms-1">{{ $totalPenjual }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $role === 'siswa' ? 'active' : '' }}" href="{{ route('admin.recycle-bin.index', ['role' => 'siswa']) }}">
            Siswa <span class="badge bg-light text-dark ms-1">{{ $totalSiswa }}</span>
        </a>
    </li>
</ul>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">#</th>
                        <th>Akun</th>
                        <th>Role</th>
                        <th>Dihapus Pada</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                    <tr>
                        <td class="ps-3 text-muted" style="font-size:.85rem;">{{ $users->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $u->avatar_url }}" width="36" height="36" class="rounded-circle object-fit-cover">
                                <div>
                                    <div class="fw-600" style="font-size:.875rem;">{{ $u->name }}</div>
                                    <div style="font-size:.78rem;color:#718096;">{{ $u->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge {{ $u->role === 'penjual' ? 'bg-primary' : 'bg-info text-dark' }}">
                                {{ ucfirst($u->role) }}
                            </span>
                        </td>
                        <td style="font-size:.82rem;" class="text-muted">
                            {{ $u->deleted_at->translatedFormat('d M Y, H:i') }}
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <form action="{{ route('admin.recycle-bin.restore', $u->id) }}" method="POST" id="restore-{{ $u->id }}">
                                    @csrf @method('PATCH')
                                    <button type="button" class="btn btn-sm btn-outline-success" title="Pulihkan" data-confirm="Pulihkan akun {{ $u->name }}?" data-form="restore-{{ $u->id }}">
                                        <i class="bi bi-arrow-counterclockwise"></i> Pulihkan
                                    </button>
                                </form>
                                <form action="{{ route('admin.recycle-bin.force-delete', $u->id) }}" method="POST" id="force-{{ $u->id }}">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-outline-danger" title="Hapus Permanen" data-confirm="Hapus permanen akun {{ $u->name }}? Tindakan ini TIDAK BISA dibatalkan!" data-form="force-{{ $u->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-5">
                        <i class="bi bi-check2-circle" style="font-size:2rem;"></i>
                        <div class="mt-2">Recycle Bin kosong.</div>
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($users->hasPages())
    <div class="card-footer bg-transparent">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection
