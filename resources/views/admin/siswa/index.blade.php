@extends('layouts.app')
@section('title','Data Siswa')
@section('page-title','Data Siswa')

@section('sidebar-links')
<span class="nav-section-label">Utama</span>
<a href="{{ route('admin.dashboard') }}" class="sidebar-link"><i class="bi bi-grid-fill"></i>Dashboard</a>
<span class="nav-section-label">Manajemen</span>
<a href="{{ route('admin.penjual.index') }}" class="sidebar-link"><i class="bi bi-shop"></i>Penjual</a>
<a href="{{ route('admin.penjual.pendaftaran') }}" class="sidebar-link"><i class="bi bi-person-check"></i>Pendaftaran Penjual</a>
<a href="{{ route('admin.siswa.index') }}" class="sidebar-link active"><i class="bi bi-people"></i>Siswa</a>
<a href="{{ route('admin.recycle-bin.index') }}" class="sidebar-link"><i class="bi bi-trash3"></i>Recycle Bin</a>
<span class="nav-section-label">Laporan</span>
<a href="{{ route('admin.laporan.index') }}" class="sidebar-link"><i class="bi bi-bar-chart-fill"></i>Laporan</a>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="fw-800 mb-0">Data Siswa</h5>
        <div class="text-muted" style="font-size:.85rem;">{{ $siswa->total() }} siswa terdaftar</div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">#</th>
                        <th>Siswa</th>
                        <th>Kelas / NIS</th>
                        <th>No. HP</th>
                        <th>Pesanan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswa as $s)
                    <tr>
                        <td class="ps-3 text-muted" style="font-size:.85rem;">{{ $siswa->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $s->avatar_url }}" width="34" height="34" class="rounded-circle object-fit-cover">
                                <div>
                                    <div class="fw-600" style="font-size:.875rem;">{{ $s->name }}</div>
                                    <div style="font-size:.78rem;color:#718096;">{{ $s->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="font-size:.85rem;">
                            <div>{{ $s->kelas ?? '-' }}</div>
                            <div style="font-size:.78rem;color:#718096;">{{ $s->nis ?? '' }}</div>
                        </td>
                        <td style="font-size:.85rem;">{{ $s->phone ?? '-' }}</td>
                        <td><span class="badge bg-light text-dark fw-700">{{ $s->pesanan_sebagai_siswa_count }}</span></td>
                        <td>
                            <span class="badge {{ $s->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                {{ $s->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <form action="{{ route('admin.siswa.toggle', $s) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-sm {{ $s->status === 'active' ? 'btn-outline-warning' : 'btn-outline-success' }}" title="{{ $s->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        <i class="bi {{ $s->status === 'active' ? 'bi-slash-circle' : 'bi-check-circle' }}"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.siswa.destroy', $s) }}" method="POST" id="del-s-{{ $s->id }}">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                            data-confirm="Hapus akun {{ $s->name }}?"
                                            data-form="del-s-{{ $s->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-5">Belum ada siswa terdaftar.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($siswa->hasPages())
    <div class="card-footer bg-transparent">{{ $siswa->links() }}</div>
    @endif
</div>
@endsection
