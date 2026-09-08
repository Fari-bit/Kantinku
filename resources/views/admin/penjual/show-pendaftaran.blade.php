@extends('layouts.app')
@section('title','Detail Pendaftaran')
@section('page-title','Detail Pendaftaran Penjual')

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
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.penjual.pendaftaran') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <div>
        <h5 class="fw-800 mb-0">Detail Pendaftaran</h5>
        <div class="text-muted" style="font-size:.85rem;">{{ $pendaftaran->nama_warung }}</div>
    </div>
    <span class="badge bg-{{ $pendaftaran->status_badge['color'] }} ms-2">{{ $pendaftaran->status_badge['label'] }}</span>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-person me-2"></i>Informasi Pendaftar</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="text-muted" style="font-size:.8rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Nama Lengkap</div>
                        <div class="fw-600 mt-1">{{ $pendaftaran->nama_lengkap }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted" style="font-size:.8rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Email</div>
                        <div class="fw-600 mt-1">{{ $pendaftaran->email }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted" style="font-size:.8rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">No. HP</div>
                        <div class="fw-600 mt-1">{{ $pendaftaran->phone }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted" style="font-size:.8rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Tanggal Daftar</div>
                        <div class="fw-600 mt-1">{{ $pendaftaran->created_at->format('d M Y, H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header"><i class="bi bi-shop me-2"></i>Informasi Warung</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="text-muted" style="font-size:.8rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Nama Warung</div>
                        <div class="fw-700 mt-1" style="font-size:1.05rem;">{{ $pendaftaran->nama_warung }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted" style="font-size:.8rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Jenis Makanan</div>
                        <div class="fw-600 mt-1">{{ $pendaftaran->jenis_makanan }}</div>
                    </div>
                    <div class="col-12">
                        <div class="text-muted" style="font-size:.8rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Deskripsi</div>
                        <div class="mt-1" style="font-size:.9rem;line-height:1.7;">{{ $pendaftaran->deskripsi_warung }}</div>
                    </div>
                </div>

                @if($pendaftaran->foto_warung)
                <div class="mt-3">
                    <div class="text-muted mb-2" style="font-size:.8rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Foto Warung</div>
                    <img src="{{ asset('storage/'.$pendaftaran->foto_warung) }}" class="rounded-3" style="max-height:200px;width:auto;" alt="Foto Warung">
                </div>
                @endif

                @if($pendaftaran->foto_ktp)
                <div class="mt-3">
                    <div class="text-muted mb-2" style="font-size:.8rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Foto KTP</div>
                    <img src="{{ asset('storage/'.$pendaftaran->foto_ktp) }}" class="rounded-3" style="max-height:200px;width:auto;" alt="Foto KTP">
                </div>
                @endif
            </div>
        </div>

        @if($pendaftaran->catatan_admin)
        <div class="card">
            <div class="card-header"><i class="bi bi-chat-text me-2"></i>Catatan Admin</div>
            <div class="card-body">
                <p class="mb-0">{{ $pendaftaran->catatan_admin }}</p>
                @if($pendaftaran->reviewer)
                <div class="text-muted mt-2" style="font-size:.8rem;">
                    Direview oleh <strong>{{ $pendaftaran->reviewer->name }}</strong> pada {{ $pendaftaran->reviewed_at?->format('d M Y, H:i') }}
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    {{-- Action Panel --}}
    @if($pendaftaran->status === 'pending')
    <div class="col-lg-4">
        <div class="card border-success mb-3">
            <div class="card-header text-success fw-700"><i class="bi bi-check-circle me-2"></i>Setujui Pendaftaran</div>
            <div class="card-body">
                <p class="text-muted mb-3" style="font-size:.88rem;">Menyetujui akan membuat akun penjual baru secara otomatis dengan password sementara.</p>
                <form action="{{ route('admin.penjual.pendaftaran.approve', $pendaftaran) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-600" style="font-size:.85rem;">Catatan (opsional)</label>
                        <textarea name="catatan_admin" class="form-control" rows="3" style="font-size:.85rem;" placeholder="Pesan untuk pendaftar..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100 fw-700">
                        <i class="bi bi-check-lg me-1"></i>Setujui & Buat Akun
                    </button>
                </form>
            </div>
        </div>

        <div class="card border-danger">
            <div class="card-header text-danger fw-700"><i class="bi bi-x-circle me-2"></i>Tolak Pendaftaran</div>
            <div class="card-body">
                <form action="{{ route('admin.penjual.pendaftaran.reject', $pendaftaran) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-600" style="font-size:.85rem;">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="catatan_admin" class="form-control" rows="3" style="font-size:.85rem;" required placeholder="Tuliskan alasan penolakan..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger w-100 fw-700">
                        <i class="bi bi-x-lg me-1"></i>Tolak Pendaftaran
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
