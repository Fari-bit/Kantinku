@extends('layouts.app')
@section('title','Pengaturan Profil')
@section('page-title','Pengaturan Profil')

@section('sidebar-links')
@if(auth()->user()->isAdmin())
    <span class="nav-section-label">Utama</span>
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link"><i class="bi bi-grid-fill"></i>Dashboard</a>
@elseif(auth()->user()->isPenjual())
    <span class="nav-section-label">Utama</span>
    <a href="{{ route('penjual.dashboard') }}" class="sidebar-link"><i class="bi bi-grid-fill"></i>Dashboard</a>
    <span class="nav-section-label">Menu</span>
    <a href="{{ route('penjual.menu.index') }}" class="sidebar-link"><i class="bi bi-grid-3x3-gap"></i>Daftar Menu</a>
@else
    <span class="nav-section-label">Menu</span>
    <a href="{{ route('siswa.dashboard') }}" class="sidebar-link"><i class="bi bi-house-fill"></i>Beranda</a>
    <a href="{{ route('siswa.pesanan.riwayat') }}" class="sidebar-link"><i class="bi bi-clock-history"></i>Riwayat Pesanan</a>
@endif
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-9">

        {{-- Header --}}
        <div class="card mb-4" style="background:linear-gradient(135deg,#1A202C,#2D3748);border:none;">
            <div class="card-body p-4 d-flex align-items-center gap-4">
                <img src="{{ $user->avatar_url }}" class="rounded-circle"
                     style="width:80px;height:80px;object-fit:cover;border:3px solid rgba(255,107,53,.5);" alt="">
                <div>
                    <div style="font-family:'Sora',sans-serif;font-weight:800;font-size:1.3rem;color:#fff;">
                        {{ $user->name }}
                    </div>
                    <div style="color:rgba(255,255,255,.6);font-size:.9rem;">{{ $user->email }}</div>
                    <span class="badge mt-1" style="background:rgba(255,107,53,.2);color:#FF8C65;font-size:.75rem;">
                        {{ ['admin' => '👑 Admin', 'penjual' => '🏪 Penjual', 'siswa' => '🎓 Siswa'][$user->role] }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Update Profil --}}
        <div class="card mb-4">
            <div class="card-header fw-700"><i class="bi bi-person-circle me-2 text-primary"></i>Informasi Profil</div>
            <div class="card-body">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PATCH')
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="form-label fw-600">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control"
                                   value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-600">No. HP</label>
                            <input type="text" name="phone" class="form-control"
                                   value="{{ old('phone', $user->phone) }}" placeholder="08xxxxxxxxxx">
                        </div>
                        @if($user->isSiswa())
                        <div class="col-sm-6">
                            <label class="form-label fw-600">Kelas</label>
                            <input type="text" name="kelas" class="form-control"
                                   value="{{ old('kelas', $user->kelas) }}" placeholder="XII RPL 1">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-600">NIS</label>
                            <input type="text" name="nis" class="form-control"
                                   value="{{ old('nis', $user->nis) }}">
                        </div>
                        @endif
                        <div class="col-12">
                            <label class="form-label fw-600">Foto Profil</label>
                            @if($user->avatar)
                            <div class="mb-2">
                                <img src="{{ $user->avatar_url }}" class="rounded-circle"
                                     style="width:60px;height:60px;object-fit:cover;" alt="">
                            </div>
                            @endif
                            <input type="file" name="avatar" class="form-control" accept="image/*">
                        </div>

                        {{-- Informasi Warung (Penjual) --}}
                        @if($user->isPenjual())
                        <div class="col-12">
                            <hr>
                            <h6 class="fw-700 mb-3">Informasi Warung</h6>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-600">Nama Warung</label>
                            <input type="text" name="nama_warung" class="form-control"
                                   value="{{ old('nama_warung', $user->penjualProfile?->nama_warung) }}">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-600">Nomor Stand</label>
                            <input type="text" name="nomor_stand" class="form-control"
                                   value="{{ old('nomor_stand', $user->penjualProfile?->nomor_stand) }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-600">Deskripsi Warung</label>
                            <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $user->penjualProfile?->deskripsi) }}</textarea>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-600">Jam Buka</label>
                            <input type="time" name="jam_buka" class="form-control"
                                   value="{{ old('jam_buka', $user->penjualProfile?->jam_buka ?? '07:00') }}">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-600">Jam Tutup</label>
                            <input type="time" name="jam_tutup" class="form-control"
                                   value="{{ old('jam_tutup', $user->penjualProfile?->jam_tutup ?? '14:00') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-600">Foto Warung</label>
                            @if($user->penjualProfile?->foto_warung)
                            <div class="mb-2">
                                <img src="{{ $user->penjualProfile->foto_warung_url }}" class="rounded-3"
                                     style="height:80px;" alt="">
                            </div>
                            @endif
                            <input type="file" name="foto_warung" class="form-control" accept="image/*">
                        </div>

                        {{-- Rekening Bank --}}
                        <div class="col-12">
                            <hr>
                            <h6 class="fw-700 mb-3">Pembayaran Transfer Bank</h6>
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" role="switch"
                                       name="terima_transfer" id="terimaTransfer"
                                       value="1"
                                       {{ old('terima_transfer', $user->penjualProfile?->terima_transfer) ? 'checked' : '' }}
                                       onchange="toggleRekening(this.checked)">
                                <label class="form-check-label fw-600" for="terimaTransfer">
                                    Menerima Pembayaran Transfer Bank
                                </label>
                                <div class="text-muted" style="font-size:.82rem;">
                                    Aktifkan agar siswa bisa memilih metode pembayaran transfer
                                </div>
                            </div>
                        </div>
                        <div id="rekeningSection"
                             class="{{ old('terima_transfer', $user->penjualProfile?->terima_transfer) ? '' : 'd-none' }} col-12">
                            <div class="row g-3">
                                <div class="col-sm-4">
                                    <label class="form-label fw-600">Nama Bank</label>
                                    <select name="nama_bank" class="form-select">
                                        <option value="">Pilih Bank</option>
                                        @foreach(['BCA','BRI','BNI','Mandiri','BSI','CIMB Niaga','Danamon','Permata','BTN'] as $bank)
                                        <option value="{{ $bank }}"
                                            {{ old('nama_bank', $user->penjualProfile?->nama_bank) === $bank ? 'selected' : '' }}>
                                            {{ $bank }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <label class="form-label fw-600">Nomor Rekening</label>
                                    <input type="text" name="nomor_rekening" class="form-control"
                                           value="{{ old('nomor_rekening', $user->penjualProfile?->nomor_rekening) }}"
                                           placeholder="1234567890">
                                </div>
                                <div class="col-sm-4">
                                    <label class="form-label fw-600">Nama Pemilik</label>
                                    <input type="text" name="nama_pemilik_rekening" class="form-control"
                                           value="{{ old('nama_pemilik_rekening', $user->penjualProfile?->nama_pemilik_rekening) }}"
                                           placeholder="Sesuai buku tabungan">
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary fw-700">
                                <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Ubah Password --}}
        <div class="card mb-4">
            <div class="card-header fw-700"><i class="bi bi-lock me-2 text-primary"></i>Ubah Password</div>
            <div class="card-body">
                <form action="{{ route('profile.password') }}" method="POST">
                    @csrf @method('PATCH')
                    <div class="row g-3">
                        <div class="col-sm-4">
                            <label class="form-label fw-600">Password Saat Ini</label>
                            <input type="password" name="current_password"
                                   class="form-control @error('current_password') is-invalid @enderror" required>
                            @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label fw-600">Password Baru</label>
                            <input type="password" name="password" class="form-control" required minlength="8">
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label fw-600">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-outline-primary fw-700">
                                <i class="bi bi-lock-fill me-1"></i>Ubah Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Hapus Akun --}}
        @if(!auth()->user()->isAdmin())
        <div class="card border-danger">
            <div class="card-header fw-700 text-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>Zona Berbahaya
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <div class="fw-700 mb-1">Hapus Akun</div>
                        <div class="text-muted" style="font-size:.88rem;">
                            Setelah dihapus, semua data tidak dapat dikembalikan.
                        </div>
                    </div>
                    <button type="button" class="btn btn-danger fw-700"
                            data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="bi bi-trash me-1"></i>Hapus Akun
                    </button>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-800 text-danger">
                            <i class="bi bi-exclamation-triangle me-2"></i>Hapus Akun
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted">Tindakan ini tidak dapat dibatalkan. Seluruh data akan dihapus permanen.</p>
                        <form action="{{ route('profile.destroy') }}" method="POST" id="deleteAccountForm">
                            @csrf @method('DELETE')
                            <label class="form-label fw-600">Masukkan Password untuk Konfirmasi</label>
                            <input type="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   required placeholder="Password kamu">
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </form>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" form="deleteAccountForm" class="btn btn-danger fw-700">
                            <i class="bi bi-trash me-1"></i>Hapus Akun Saya
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleRekening(show) {
    document.getElementById('rekeningSection').classList.toggle('d-none', !show);
}
</script>
@endpush
