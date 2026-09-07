@extends('layouts.app')
@section('title', isset($menu) ? 'Edit Menu' : 'Tambah Menu')
@section('page-title', isset($menu) ? 'Edit Menu' : 'Tambah Menu Baru')

@section('sidebar-links')
<span class="nav-section-label">Utama</span>
<a href="{{ route('penjual.dashboard') }}" class="sidebar-link"><i class="bi bi-grid-fill"></i>Dashboard</a>
<span class="nav-section-label">Operasional</span>
<a href="{{ route('penjual.pesanan.index') }}" class="sidebar-link"><i class="bi bi-bag-check"></i>Pesanan Masuk</a>
<a href="{{ route('penjual.pesanan.riwayat') }}" class="sidebar-link"><i class="bi bi-clock-history"></i>Riwayat Pesanan</a>
<a href="{{ route('penjual.ulasan.index') }}" class="sidebar-link {{ request()->routeIs('penjual.ulasan.*') ? 'active' : '' }}">
    <i class="bi bi-star"></i>Ulasan
</a>
<span class="nav-section-label">Menu</span>
<a href="{{ route('penjual.menu.index') }}" class="sidebar-link"><i class="bi bi-grid-3x3-gap"></i>Daftar Menu</a>
<a href="{{ route('penjual.menu.create') }}" class="sidebar-link active"><i class="bi bi-plus-circle"></i>Tambah Menu</a>
<a href="{{ route('penjual.recycle-bin.index') }}" class="sidebar-link {{ request()->routeIs('penjual.recycle-bin.*') ? 'active' : '' }}"><i class="bi bi-trash3"></i>Recycle Bin</a>

@endsection

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('penjual.menu.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="fw-800 mb-0">{{ isset($menu) ? 'Edit Menu: '.$menu->nama : 'Tambah Menu Baru' }}</h5>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <form action="{{ isset($menu) ? route('penjual.menu.update', $menu) : route('penjual.menu.store') }}"
              method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($menu)) @method('PUT') @endif

            <div class="card">
                <div class="card-header"><i class="bi bi-info-circle me-2 text-primary"></i>Informasi Menu</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-600">Nama Menu <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                               value="{{ old('nama', $menu->nama ?? '') }}" required placeholder="Contoh: Nasi Goreng Spesial">
                        @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3"
                                  placeholder="Deskripsikan menu ini...">{{ old('deskripsi', $menu->deskripsi ?? '') }}</textarea>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-sm-4">
                            <label class="form-label fw-600">Kategori <span class="text-danger">*</span></label>
                            <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                                <option value="">Pilih Kategori</option>
                                <option value="makanan" {{ old('kategori', $menu->kategori ?? '') === 'makanan' ? 'selected' : '' }}>🍱 Makanan</option>
                                <option value="minuman" {{ old('kategori', $menu->kategori ?? '') === 'minuman' ? 'selected' : '' }}>🥤 Minuman</option>
                                <option value="snack"   {{ old('kategori', $menu->kategori ?? '') === 'snack'   ? 'selected' : '' }}>🍿 Snack</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label fw-600">Harga (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="harga" class="form-control @error('harga') is-invalid @enderror"
                                   value="{{ old('harga', $menu->harga ?? '') }}" min="0" required placeholder="5000">
                            @error('harga')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label fw-600">Stok <span class="text-danger">*</span></label>
                            <input type="number" name="stok" class="form-control"
                                   value="{{ old('stok', $menu->stok ?? 100) }}" min="0" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600">Foto Menu</label>
                        @if(isset($menu) && $menu->foto)
                        <div class="mb-2">
                            <img src="{{ $menu->foto_url }}" class="rounded-3" style="height:120px;width:auto;object-fit:cover;" alt="Foto">
                        </div>
                        @endif
                        <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror"
                               accept="image/*" onchange="previewImg(this)">
                        <div id="imgPreview" class="mt-2 d-none">
                            <img id="previewEl" src="" class="rounded-3" style="max-height:150px;" alt="Preview">
                        </div>
                        <div class="form-text">Format JPG/PNG/WebP, max 2MB. Gambar yang jelas meningkatkan penjualan!</div>
                    </div>
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" role="switch" name="tersedia" id="tersedia"
                               {{ old('tersedia', $menu->tersedia ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label fw-600" for="tersedia">Menu tersedia untuk dipesan</label>
                    </div>
                </div>
                <div class="card-footer bg-transparent d-flex gap-3">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-lg me-1"></i>{{ isset($menu) ? 'Simpan Perubahan' : 'Tambah Menu' }}
                    </button>
                    <a href="{{ route('penjual.menu.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewImg(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('previewEl').src = e.target.result;
            document.getElementById('imgPreview').classList.remove('d-none');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
