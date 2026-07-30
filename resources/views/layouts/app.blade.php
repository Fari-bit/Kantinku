<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'KantinKu') — Kantin Sekolah</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Sora:wght@400;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #FF6B35;
            --primary-dark: #E55A25;
            --primary-light: #FFF0EB;
            --secondary: #2D3748;
            --accent: #48BB78;
            --warning: #F6AD55;
            --danger: #FC8181;
            --surface: #FFFFFF;
            --bg: #F7F8FC;
            --border: #E2E8F0;
            --text: #2D3748;
            --text-muted: #718096;
            --sidebar-w: 260px;
        }

        * { box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: var(--secondary);
            position: fixed;
            top: 0; left: 0;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            transition: transform .3s ease;
        }
        .sidebar-brand {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .sidebar-brand .brand-name {
            font-family: 'Sora', sans-serif;
            font-weight: 800;
            font-size: 1.35rem;
            color: #fff;
        }
        .sidebar-brand .brand-name span { color: var(--primary); }
        .sidebar-brand .badge-role {
            font-size: 0.68rem;
            background: rgba(255,107,53,0.2);
            color: var(--primary);
            padding: 2px 8px;
            border-radius: 20px;
            font-weight: 600;
        }
        .sidebar-nav { padding: 1rem 0; flex: 1; }
        .nav-section-label {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255,255,255,0.35);
            padding: .5rem 1.25rem;
            margin-top: .5rem;
        }
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .65rem 1.25rem;
            color: rgba(255,255,255,0.65);
            text-decoration: none;
            font-size: .9rem;
            font-weight: 500;
            transition: all .2s;
            border-radius: 0;
            margin: 1px 0;
            position: relative;
        }
        .sidebar-link:hover { color: #fff; background: rgba(255,255,255,0.06); }
        .sidebar-link.active {
            color: #fff;
            background: rgba(255,107,53,0.15);
        }
        .sidebar-link.active::before {
            content: '';
            position: absolute;
            left: 0; top: 0; bottom: 0;
            width: 3px;
            background: var(--primary);
            border-radius: 0 2px 2px 0;
        }
        .sidebar-link .bi { font-size: 1rem; opacity: .8; }
        .sidebar-link.active .bi { opacity: 1; color: var(--primary); }
        .sidebar-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid rgba(255,255,255,0.08);
        }
        .sidebar-user {
            display: flex;
            align-items: center;
            gap: .75rem;
        }
        .sidebar-user img {
            width: 36px; height: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255,107,53,.4);
        }
        .sidebar-user .user-name {
            font-size: .85rem;
            font-weight: 600;
            color: #fff;
            line-height: 1.2;
        }
        .sidebar-user .user-role {
            font-size: .72rem;
            color: rgba(255,255,255,.4);
        }
        .sidebar-badge {
            margin-left: auto;
            background: var(--primary);
            color: #fff;
            font-size: .65rem;
            padding: 1px 6px;
            border-radius: 10px;
            font-weight: 700;
        }

        /* ── Main Content ── */
        .main-wrap {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        
        .topbar {
            height: 64px;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            gap: 1rem;
            position: sticky;
            top: 0;
            z-index: 900;
        }
        .topbar-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.25rem;
            color: var(--text);
            cursor: pointer;
        }
        .topbar-title {
            font-family: 'Sora', sans-serif;
            font-weight: 700;
            font-size: 1.05rem;
            color: var(--text);
            flex: 1;
        }
        .topbar-actions { display: flex; align-items: center; gap: .75rem; }
        .cart-btn {
            position: relative;
            background: var(--primary-light);
            border: none;
            width: 40px; height: 40px;
            border-radius: 10px;
            color: var(--primary);
            font-size: 1.1rem;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            text-decoration: none;
            transition: background .2s;
        }
        .cart-btn:hover { background: #fde0d5; color: var(--primary); }
        .cart-badge {
            position: absolute;
            top: -4px; right: -4px;
            background: var(--primary);
            color: #fff;
            font-size: .6rem;
            font-weight: 700;
            width: 17px; height: 17px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            border: 2px solid #fff;
        }
        .page-content { padding: 1.5rem; flex: 1; }

        /* ── Cards ── */
        .card {
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: 0 1px 4px rgba(0,0,0,.04);
        }
        .card-header { background: transparent; border-bottom: 1px solid var(--border); font-weight: 700; padding: 1rem 1.25rem; }

        /* ── Stat Cards ── */
        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .stat-icon {
            width: 52px; height: 52px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
        }
        .stat-label { font-size: .8rem; color: var(--text-muted); font-weight: 500; }
        .stat-value { font-size: 1.6rem; font-weight: 800; font-family: 'Sora', sans-serif; color: var(--text); line-height: 1.1; }
        .stat-change { font-size: .75rem; font-weight: 600; }

        /* ── Buttons ── */
        .btn-primary { background: var(--primary); border-color: var(--primary); font-weight: 600; }
        .btn-primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); }
        .btn-outline-primary { color: var(--primary); border-color: var(--primary); font-weight: 600; }
        .btn-outline-primary:hover { background: var(--primary); border-color: var(--primary); }

        /* ── Tables ── */
        .table { font-size: .875rem; }
        .table th { font-weight: 700; color: var(--text-muted); font-size: .75rem; text-transform: uppercase; letter-spacing: .5px; }
        .table td { vertical-align: middle; }

        /* ── Badges ── */
        .badge { font-weight: 600; font-size: .75rem; }

        /* ── Alert toast ── */
        .toast-container { position: fixed; bottom: 1.5rem; right: 1.5rem; z-index: 9999; }

        /* ── Sidebar overlay ── */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.5);
            z-index: 999;
        }

        /* ── Responsive ── */
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .sidebar-overlay.open { display: block; }
            .main-wrap { margin-left: 0; }
            .topbar-toggle { display: flex; }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- Sidebar Overlay --}}
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

{{-- Sidebar --}}
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-name">Kantin<span>Ku</span></div>
        <span class="badge-role mt-1 d-inline-block">
            @if(auth()->user()->isAdmin()) Admin
            @elseif(auth()->user()->isPenjual()) Penjual
            @else Siswa @endif
        </span>
    </div>

    <nav class="sidebar-nav">
        @yield('sidebar-links')
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <img src="{{ auth()->user()->avatar_url }}" alt="avatar">
            <div>
                <div class="user-name">{{ Str::limit(auth()->user()->name, 18) }}</div>
                <div class="user-role">{{ auth()->user()->email }}</div>
            </div>
        </div>
        <div class="mt-3 d-flex gap-2">
            <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-secondary flex-fill" style="font-size:.78rem;">
                <i class="bi bi-gear me-1"></i>Pengaturan
            </a>
            <form action="{{ route('logout') }}" method="POST" class="flex-fill">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger w-100" style="font-size:.78rem;">
                    <i class="bi bi-box-arrow-right me-1"></i>Keluar
                </button>
            </form>
        </div>
    </div>
</aside>

{{-- Main --}}
<div class="main-wrap">
    <header class="topbar">
        <button class="topbar-toggle" onclick="openSidebar()">
            <i class="bi bi-list"></i>
        </button>
        <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
        <div class="topbar-actions">
            @if(auth()->user()->isSiswa())
            @php $cartCount = array_sum(array_column(session('cart', []), 'jumlah')) @endphp
            <a href="{{ route('siswa.keranjang') }}" class="cart-btn">
                <i class="bi bi-bag2"></i>
                @if($cartCount > 0)
                <span class="cart-badge" id="cartBadge">{{ $cartCount }}</span>
                @endif
            </a>
            @endif
            <div class="dropdown">
                <button class="btn btn-sm btn-light rounded-circle p-0 d-flex align-items-center justify-content-center" style="width:36px;height:36px;" data-bs-toggle="dropdown">
                    <img src="{{ auth()->user()->avatar_url }}" class="rounded-circle" style="width:30px;height:30px;object-fit:cover;" alt="">
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i>Profil Saya</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Keluar</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <main class="page-content">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @yield('content')
    </main>
</div>

<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>

<script>
function openSidebar() {
    document.getElementById('sidebar').classList.add('open');
    document.getElementById('sidebarOverlay').classList.add('open');
}
function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('sidebarOverlay').classList.remove('open');
}

// Delete confirmation
document.querySelectorAll('[data-confirm]').forEach(el => {
    el.addEventListener('click', function(e) {
        e.preventDefault();
        const form = this.closest('form') || document.getElementById(this.dataset.form);
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: this.dataset.confirm || 'Data akan dihapus permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#FF6B35',
            cancelButtonColor: '#718096',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
        }).then(r => { if (r.isConfirmed) form.submit(); });
    });
});
</script>
@stack('scripts')
</body>
</html>
