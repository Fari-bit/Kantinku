@extends('layouts.app')
@section('title', isset($penjual) ? 'Edit Penjual' : 'Tambah Penjual')
@section('page-title', isset($penjual) ? 'Edit Penjual' : 'Tambah Penjual')

@section('sidebar-links')
<span class="nav-section-label">Utama</span>
<a href="{{ route('admin.dashboard') }}" class="sidebar-link"><i class="bi bi-grid-fill"></i>Dashboard</a>
<span class="nav-section-label">Manajemen</span>
<a href="{{ route('admin.penjual.index') }}" class="sidebar-link active"><i class="bi bi-shop"></i>Penjual</a>
<a href="{{ route('admin.penjual.pendaftaran') }}" class="sidebar-link"><i class="bi bi-person-check"></i>Pendaftaran Penjual</a>
<a href="{{ route('admin.siswa.index') }}" class="sidebar-link"><i class="bi bi-people"></i>Siswa</a>
<span class="nav-section-label">Laporan</span>
<a href="{{ route('admin.laporan.index') }}" class="sidebar-link"><i class="bi bi-bar-chart-fill"></i>Laporan</a>
@endsection

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.penjual.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h5 class="fw-800 mb-0">{{ isset($penjual) ? 'Edit Penjual' : 'Tambah Penjual Baru' }}</h5>
        <div class="text-muted" style="font-size:.85rem;">{{ isset($penjual) ? $penjual->name : 'Isi data penjual dengan lengkap' }}</div>
    </div>
</div>

<form action="{{ isset($penjual) ? route('admin.penjual.update', $penjual) : route('admin.penjual.store') }}"
      method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($penjual)) @method('PUT') @endif

    <div class="row g-4">
        {{-- Informasi Akun --}}
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <i class="bi bi-person-circle me-2 text-primary"></i>Informasi Akun
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-600">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $penjual->name ?? '') }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $penjual->email ?? '') }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600">No. HP</label>
                        <input type="text" name="phone" class="form-control"
                               value="{{ old('phone', $penjual->phone ?? '') }}" placeholder="08xxxxxxxxxx">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600">
                            Password {{ isset($penjual) ? '(kosongkan jika tidak diubah)' : '' }}
                            @if(!isset($penjual)) <span class="text-danger">*</span> @endif
                        </label>
                        <input type="password" name="password" class="form-control"
                               {{ !isset($penjual) ? 'required' : '' }} minlength="8" placeholder="Min. 8 karakter">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password">
                    </div>
                    @if(isset($penjual))
                    <div class="mb-3">
                        <label class="form-label fw-600">Status</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ ($penjual->status ?? '') === 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ ($penjual->status ?? '') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Informasi Warung --}}
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <i class="bi bi-shop me-2 text-primary"></i>Informasi Warung
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-600">Nama Warung <span class="text-danger">*</span></label>
                        <input type="text" name="nama_warung" class="form-control @error('nama_warung') is-invalid @enderror"
                               value="{{ old('nama_warung', $penjual->penjualProfile->nama_warung ?? '') }}" required>
                        @error('nama_warung')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600">Deskripsi Warung</label>
                        <textarea name="deskripsi" class="form-control" rows="3"
                                  placeholder="Ceritakan tentang warung ini...">{{ old('deskripsi', $penjual->penjualProfile->deskripsi ?? '') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600">Nomor Stand</label>
                        <input type="text" name="nomor_stand" class="form-control"
                               value="{{ old('nomor_stand', $penjual->penjualProfile->nomor_stand ?? '') }}" placeholder="Contoh: A1">
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-600">Jam Buka</label>
                            <input type="time" name="jam_buka" class="form-control"
                                   value="{{ old('jam_buka', $penjual->penjualProfile->jam_buka ?? '07:00') }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-600">Jam Tutup</label>
                            <input type="time" name="jam_tutup" class="form-control"
                                   value="{{ old('jam_tutup', $penjual->penjualProfile->jam_tutup ?? '14:00') }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600">Foto Warung</label>
                        @if(isset($penjual) && $penjual->penjualProfile?->foto_warung)
                        <div class="mb-2">
                            <img src="{{ $penjual->penjualProfile->foto_warung_url }}" class="rounded-3" style="height:100px;width:auto;object-fit:cover;" alt="Foto Warung">
                        </div>
                        @endif
                        <input type="file" name="foto_warung" class="form-control" accept="image/*">
                        <div class="form-text">Format JPG/PNG, max 2MB</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-lg me-1"></i>{{ isset($penjual) ? 'Simpan Perubahan' : 'Tambah Penjual' }}
                </button>
                <a href="{{ route('admin.penjual.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </div>
    </div>
</form>
@endsection
