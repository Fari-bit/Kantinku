@extends('layouts.app')

@section('title', 'Backup Database')
@section('page-title', 'Backup Database')

@section('sidebar-links')
<span class="nav-section-label">Utama</span>
<a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
    <i class="bi bi-grid-fill"></i>Dashboard
</a>

<span class="nav-section-label">Manajemen</span>
<a href="{{ route('admin.penjual.index') }}" class="sidebar-link {{ request()->routeIs('admin.penjual.*') ? 'active' : '' }}">
    <i class="bi bi-shop"></i>Penjual
</a>
<a href="{{ route('admin.penjual.pendaftaran') }}" class="sidebar-link {{ request()->routeIs('admin.penjual.pendaftaran*') ? 'active' : '' }}">
    <i class="bi bi-person-check"></i>Pendaftaran Penjual
</a>
<a href="{{ route('admin.siswa.index') }}" class="sidebar-link {{ request()->routeIs('admin.siswa.*') ? 'active' : '' }}">
    <i class="bi bi-people"></i>Siswa
</a>
<a href="{{ route('admin.recycle-bin.index') }}" class="sidebar-link {{ request()->routeIs('admin.recycle-bin.*') ? 'active' : '' }}">
    <i class="bi bi-trash3"></i>Recycle Bin
</a>

<span class="nav-section-label">Laporan</span>
<a href="{{ route('admin.laporan.index') }}" class="sidebar-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
    <i class="bi bi-bar-chart-fill"></i>Laporan & Statistik
</a>

<span class="nav-section-label">Backup</span>
<a href="{{ route('admin.backup.index') }}" class="sidebar-link {{ request()->routeIs('admin.backup.*') ? 'active' : '' }}">
    <i class="bi bi-database-fill-down"></i>Backup Database
</a>
@endsection

@section('content')
@if(session('success'))
<div class="alert mb-4" style="background:#EBF8F0;border:1px solid #9AE6B4;border-radius:12px;padding:12px 16px;color:#276749;">
    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="alert mb-4" style="background:#FED7D7;border:1px solid #FC8181;border-radius:12px;padding:12px 16px;color:#9B2C2C;">
    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
</div>
@endif

<!-- CARD 1: PENGATURAN OTOMATISASI BACKUP -->
<div class="card mb-4">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span>Pengaturan Otomatisasi Backup</span>
        @if(env('AUTO_BACKUP_ENABLED', false) == 'true')
            <span class="badge" style="background:#C6F6D5;color:#22543D;padding:6px 12px;border-radius:20px;font-size:.75rem;">
                <i class="bi bi-circle-fill me-1" style="font-size:6px;"></i> OTOMATISASI AKTIF
            </span>
        @else
            <span class="badge" style="background:#E2E8F0;color:#4A5568;padding:6px 12px;border-radius:20px;font-size:.75rem;">
                <i class="bi bi-circle-fill me-1" style="font-size:6px;"></i> NON-AKTIF
            </span>
        @endif
    </div>
    <div class="card-body">
        <div style="font-size:.88rem;color:#718096;margin-bottom:16px;">
            Atur status On/Off dan interval waktu agar sistem menjalankan pencadangan otomatis secara berkala di background.
        </div>
        <form method="POST" action="{{ route('admin.backup.update-settings') }}">
            @csrf
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label style="font-size:.8rem;color:#718096;display:block;margin-bottom:4px;">Status Otomatisasi</label>
                    <select name="enabled" class="form-control" style="font-size:.88rem;">
                        <option value="true" {{ env('AUTO_BACKUP_ENABLED', false) == 'true' ? 'selected' : '' }}>🟢 Aktif (Jalan Otomatis)</option>
                        <option value="false" {{ env('AUTO_BACKUP_ENABLED', false) == 'false' ? 'selected' : '' }}>🔴 Non-Aktif (Matikan)</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <label style="font-size:.8rem;color:#718096;display:block;margin-bottom:4px;">Interval Pengulangan</label>
                    <select name="interval" class="form-control" style="font-size:.88rem;">
                        <option value="0.1" {{ env('AUTO_BACKUP_INTERVAL', 3) == '0.1' ? 'selected' : '' }}>⚡ Setiap 30 Detik (Demo)</option>                        
                        <option value="1" {{ env('AUTO_BACKUP_INTERVAL', 3) == 1 ? 'selected' : '' }}>Setiap 1 Jam</option>
                        <option value="3" {{ env('AUTO_BACKUP_INTERVAL', 3) == 3 ? 'selected' : '' }}>Setiap 3 Jam (Default)</option>
                        <option value="6" {{ env('AUTO_BACKUP_INTERVAL', 3) == 6 ? 'selected' : '' }}>Setiap 6 Jam</option>
                        <option value="12" {{ env('AUTO_BACKUP_INTERVAL', 3) == 12 ? 'selected' : '' }}>Setiap 12 Jam</option>
                        <option value="24" {{ env('AUTO_BACKUP_INTERVAL', 3) == 24 ? 'selected' : '' }}>Setiap 24 Jam (1 Hari)</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-outline-primary w-100 fw-700" style="font-size:.88rem;">
                        <i class="bi bi-gear-fill me-1"></i> Simpan Setelan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- CARD 2: BACKUP MANUAL -->
<div class="card mb-4">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span>Buat & Kirim Backup Baru</span>
    </div>
    <div class="card-body">
        <div style="font-size:.88rem;color:#718096;margin-bottom:16px;">
            Backup akan dibuat dari database saat ini dan dikirim sebagai lampiran ke email tujuan lewat SMTP.
        </div>
        <form method="POST" action="{{ route('admin.backup.create') }}" class="d-flex align-items-end gap-3 flex-wrap">
            @csrf
            <div style="flex:1;min-width:240px;">
                <label style="font-size:.8rem;color:#718096;display:block;margin-bottom:4px;">Email Tujuan</label>
                <input type="email" name="email" class="form-control" placeholder="admin@example.com" value="{{ old('email') }}" required>
                @error('email')
                <div style="font-size:.78rem;color:#E53E3E;margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary fw-700">
                <i class="bi bi-envelope-arrow-up"></i> Buat & Kirim Backup
            </button>
        </form>
    </div>
</div>

<!-- CARD 3: RIWAYAT BACKUP -->
<div class="card">
    <div class="card-header">Riwayat Backup</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">Nama File</th>
                        <th>Ukuran</th>
                        <th>Tanggal</th>
                        <th class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($backups as $b)
                    <tr>
                        <td class="ps-3" style="font-size:.85rem;">{{ $b['name'] }}</td>
                        <td style="font-size:.85rem;">{{ $b['size'] }}</td>
                        <td style="font-size:.85rem;color:#718096;">{{ \Carbon\Carbon::createFromTimestamp($b['date'])->format('d M Y, H:i') }}</td>
                        <td class="text-end pe-3">
                            <a href="{{ route('admin.backup.download', $b['name']) }}" class="btn btn-sm btn-outline-primary" style="font-size:.78rem;">
                                <i class="bi bi-download"></i> Unduh
                            </a>
                            <form method="POST" action="{{ route('admin.backup.delete', $b['name']) }}" class="d-inline" onsubmit="return confirm('Hapus backup ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" style="font-size:.78rem;">
                                    <i class="bi bi-trash3"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">Belum ada backup.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection