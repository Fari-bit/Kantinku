<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KantinKu — Pesan Makanan Kantin Sekolah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Sora:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #FF6B35;
            --primary-dark: #E55A25;
            --secondary: #1A202C;
            --accent: #48BB78;
        }
        * { box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; color: #2D3748; }

        /* ── Navbar ── */
        .navbar { background: rgba(255,255,255,0.95); backdrop-filter: blur(12px); border-bottom: 1px solid #E2E8F0; }
        .navbar-brand { font-family: 'Sora', sans-serif; font-weight: 900; font-size: 1.4rem; color: var(--secondary) !important; }
        .navbar-brand span { color: var(--primary); }
        .btn-primary-custom {
            background: var(--primary);
            color: #fff;
            border: none;
            padding: .5rem 1.25rem;
            border-radius: 10px;
            font-weight: 700;
            font-size: .9rem;
            transition: all .2s;
        }
        .btn-primary-custom:hover { background: var(--primary-dark); color: #fff; transform: translateY(-1px); box-shadow: 0 4px 15px rgba(255,107,53,.35); }

        /* ── Hero ── */
        .hero {
            background: linear-gradient(135deg, #1A202C 0%, #2D3748 50%, #1A202C 100%);
            min-height: 90vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            width: 700px; height: 700px;
            background: radial-gradient(circle, rgba(255,107,53,.15) 0%, transparent 70%);
            top: -200px; right: -200px;
            pointer-events: none;
        }
        .hero::after {
            content: '';
            position: absolute;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(72,187,120,.1) 0%, transparent 70%);
            bottom: -100px; left: 10%;
            pointer-events: none;
        }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            background: rgba(255,107,53,.15);
            color: #FF8C65;
            padding: .4rem 1rem;
            border-radius: 50px;
            font-size: .82rem;
            font-weight: 700;
            border: 1px solid rgba(255,107,53,.25);
            margin-bottom: 1.25rem;
        }
        .hero h1 {
            font-family: 'Sora', sans-serif;
            font-weight: 900;
            font-size: clamp(2.2rem, 5vw, 3.8rem);
            color: #fff;
            line-height: 1.12;
            margin-bottom: 1.25rem;
        }
        .hero h1 .highlight {
            color: var(--primary);
            position: relative;
        }
        .hero p {
            color: rgba(255,255,255,.65);
            font-size: 1.05rem;
            max-width: 500px;
            line-height: 1.7;
        }
        .hero-actions { display: flex; flex-wrap: wrap; gap: .75rem; margin-top: 2rem; }
        .btn-hero-primary {
            background: var(--primary);
            color: #fff;
            font-weight: 700;
            padding: .85rem 2rem;
            border-radius: 12px;
            border: none;
            font-size: 1rem;
            text-decoration: none;
            transition: all .2s;
            display: inline-flex; align-items: center; gap: .5rem;
        }
        .btn-hero-primary:hover { background: var(--primary-dark); color: #fff; transform: translateY(-2px); box-shadow: 0 8px 25px rgba(255,107,53,.4); }
        .btn-hero-outline {
            background: transparent;
            color: #fff;
            font-weight: 700;
            padding: .85rem 2rem;
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,.25);
            font-size: 1rem;
            text-decoration: none;
            transition: all .2s;
            display: inline-flex; align-items: center; gap: .5rem;
        }
        .btn-hero-outline:hover { background: rgba(255,255,255,.08); color: #fff; }
        .hero-stats { display: flex; gap: 2rem; margin-top: 2.5rem; }
        .hero-stat-item .number {
            font-family: 'Sora', sans-serif;
            font-size: 1.6rem;
            font-weight: 900;
            color: #fff;
        }
        .hero-stat-item .label { font-size: .8rem; color: rgba(255,255,255,.5); }
        .hero-visual {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .hero-visual .food-card {
            background: rgba(255,255,255,.06);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 20px;
            padding: 1.5rem;
            width: 300px;
        }
        .food-card-emoji { font-size: 4rem; text-align: center; margin-bottom: 1rem; }
        .floating-badge {
            position: absolute;
            background: #fff;
            border-radius: 12px;
            padding: .6rem .9rem;
            box-shadow: 0 8px 25px rgba(0,0,0,.15);
            display: flex; align-items: center; gap: .5rem;
            font-size: .8rem;
            font-weight: 700;
            color: var(--secondary);
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }
        .floating-badge.badge-1 { top: 10%; right: -10%; animation-delay: 0s; }
        .floating-badge.badge-2 { bottom: 20%; left: -15%; animation-delay: 1s; }
        .floating-badge.badge-3 { top: 55%; right: -12%; animation-delay: .5s; }
        .floating-badge .dot { width: 8px; height: 8px; border-radius: 50%; }

        /* ── Features ── */
        .section-tag {
            display: inline-block;
            background: var(--primary);
            color: #fff;
            font-size: .72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: .3rem .9rem;
            border-radius: 50px;
            margin-bottom: .75rem;
        }
        .section-title {
            font-family: 'Sora', sans-serif;
            font-weight: 800;
            font-size: clamp(1.6rem, 3vw, 2.4rem);
        }
        .feature-card {
            background: #fff;
            border: 1px solid #E2E8F0;
            border-radius: 16px;
            padding: 1.75rem;
            height: 100%;
            transition: all .25s;
        }
        .feature-card:hover { transform: translateY(-4px); box-shadow: 0 12px 40px rgba(0,0,0,.08); border-color: #FF6B35; }
        .feature-icon {
            width: 56px; height: 56px;
            background: var(--primary-light, #FFF0EB);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        /* ── Menu Cards ── */
        .menu-card {
            background: #fff;
            border: 1px solid #E2E8F0;
            border-radius: 16px;
            overflow: hidden;
            transition: all .25s;
            cursor: pointer;
        }
        .menu-card:hover { transform: translateY(-4px); box-shadow: 0 12px 40px rgba(0,0,0,.1); }
        .menu-card-img {
            height: 180px;
            background: linear-gradient(135deg, #FFF0EB, #FFD4C2);
            display: flex; align-items: center; justify-content: center;
            font-size: 4rem;
            position: relative;
        }
        .menu-card-img img { width: 100%; height: 100%; object-fit: cover; position: absolute; inset: 0; }
        .menu-card-body { padding: 1rem; }
        .menu-card-name { font-weight: 700; font-size: .95rem; color: var(--secondary); }
        .menu-card-price { font-weight: 800; color: var(--primary); font-size: 1rem; }
        .menu-card-seller { font-size: .78rem; color: #718096; }

        /* ── Penjual CTA ── */
        .penjual-cta {
            background: linear-gradient(135deg, #FF6B35 0%, #E55A25 100%);
            border-radius: 24px;
            padding: 3rem 2.5rem;
            color: #fff;
            position: relative;
            overflow: hidden;
        }
        .penjual-cta::before {
            content: '🍜';
            position: absolute;
            font-size: 8rem;
            right: 2rem;
            top: 50%;
            transform: translateY(-50%);
            opacity: .15;
        }
        .penjual-cta h2 {
            font-family: 'Sora', sans-serif;
            font-weight: 800;
            font-size: 1.8rem;
        }
        .btn-cta-white {
            background: #fff;
            color: var(--primary);
            font-weight: 800;
            padding: .85rem 2rem;
            border-radius: 12px;
            border: none;
            font-size: .95rem;
            text-decoration: none;
            transition: all .2s;
            display: inline-flex; align-items: center; gap: .5rem;
        }
        .btn-cta-white:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,.15); color: var(--primary); }

        /* ── Warung Cards ── */
        .warung-card {
            background: #fff;
            border: 1px solid #E2E8F0;
            border-radius: 16px;
            padding: 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all .2s;
        }
        .warung-card:hover { border-color: var(--primary); box-shadow: 0 4px 20px rgba(255,107,53,.1); }
        .warung-avatar {
            width: 56px; height: 56px;
            border-radius: 14px;
            background: linear-gradient(135deg, #FFF0EB, #FFD4C2);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.75rem;
            flex-shrink: 0;
        }
        .warung-name { font-weight: 700; font-size: .95rem; color: var(--secondary); }
        .warung-meta { font-size: .78rem; color: #718096; }
        .badge-open { background: #C6F6D5; color: #22543D; font-size: .72rem; font-weight: 700; padding: 2px 8px; border-radius: 6px; }

        /* ── Footer ── */
        footer { background: var(--secondary); color: rgba(255,255,255,.6); }
        footer .brand { font-family: 'Sora', sans-serif; font-weight: 900; font-size: 1.4rem; color: #fff; }
        footer .brand span { color: var(--primary); }
    </style>
</head>
<body>

{{-- Success Alert --}}
@if(session('success'))
<div style="position:fixed;top:80px;right:20px;z-index:9999;max-width:380px;">
    <div class="alert alert-success alert-dismissible fade show shadow" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
@endif

{{-- Navbar --}}
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('welcome') }}">Kantin<span>Ku</span></a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <i class="bi bi-list fs-4"></i>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav me-auto ms-3 gap-1">
                <li class="nav-item"><a class="nav-link fw-600" href="#fitur">Fitur</a></li>
                <li class="nav-item"><a class="nav-link fw-600" href="#menu">Menu</a></li>
                <li class="nav-item"><a class="nav-link fw-600" href="#warung">Warung</a></li>
            </ul>
            <div class="d-flex align-items-center gap-2">
                @auth
                    <a href="{{ route(auth()->user()->role . '.dashboard') }}" class="btn-primary-custom text-decoration-none">
                        <i class="bi bi-grid me-1"></i>Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-sm btn-outline-secondary fw-600 px-3">Masuk</a>
                    <a href="{{ route('register') }}" class="btn-primary-custom text-decoration-none">Daftar</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

{{-- Hero --}}
<section class="hero">
    <div class="container">
        <div class="row align-items-center gy-5">
            <div class="col-lg-6">
                <div class="hero-badge">
                    <span>🎉</span> Platform Kantin Online Terbaik
                </div>
                <h1>Pesan Makanan Kantin <span class="highlight">Lebih Mudaah</span> & Tanpa Antri</h1>
                <p>Pesan makanan & minuman dari berbagai warung kantin sekolah langsung dari HP. Bayar lebih mudah, tinggal ambil!</p>
                <div class="hero-actions">
                    @auth
                        <a href="{{ route(auth()->user()->role . '.dashboard') }}" class="btn-hero-primary">
                            <i class="bi bi-grid-fill"></i>Buka Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn-hero-primary">
                            <i class="bi bi-bag-heart-fill"></i>Pesan Sekarang
                        </a>
                        <a href="{{ route('login') }}" class="btn-hero-outline">
                            <i class="bi bi-box-arrow-in-right"></i>Masuk
                        </a>
                    @endauth
                </div>
                <div class="hero-stats">
                    <div class="hero-stat-item">
                        <div class="number">{{ $penjual->count() }}+</div>
                        <div class="label">Warung Aktif</div>
                    </div>

                    <div class="hero-stat-item">
                        <div class="number">100%</div>
                        <div class="label">Mudah Digunakan</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 offset-lg-1 d-none d-lg-block">
                <div class="hero-visual">
                    <div class="food-card">
                        <div class="food-card-emoji">🍱</div>
                        <div class="text-center text-white">
                            <div style="font-weight:700;font-size:1rem;">Nasi Goreng Spesial</div>
                            <div style="color:var(--primary);font-weight:800;margin-top:.25rem;">Rp 12.000</div>
                            <div style="color:rgba(255,255,255,.5);font-size:.8rem;margin-top:.2rem;">⭐ 4.8 · 250 terjual</div>
                        </div>
                    </div>
                    <div class="floating-badge badge-1">
                        <div class="dot" style="background:#48BB78;"></div>
                        Pesanan Siap! 🎉
                    </div>
                    <div class="floating-badge badge-2">
                        <span>🛒</span> 3 item ditambahkan
                    </div>
                    <div class="floating-badge badge-3">
                        ⚡ Cepat & Mudah
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Fitur --}}
<section class="py-5" id="fitur" style="background:#F7F8FC;">
    <div class="container py-3">
        <div class="text-center mb-5">
            <span class="section-tag">Fitur</span>
            <h2 class="section-title mt-2">Mengapa Memilih KantinKu?</h2>
            <p class="text-muted mt-2">Platform canggih untuk memudahkan seluruh ekosistem kantin sekolah</p>
        </div>
        <div class="row g-4">
            <div class="col-sm-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon" style="background:#FFF0EB;">🛒</div>
                    <h5 class="fw-700 mb-2">Pesan dengan Mudah</h5>
                    <p class="text-muted small mb-0">Tambahkan menu ke keranjang dengan sekali klik. Checkout cepat tanpa ribet.</p>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon" style="background:#EBF8F0;">📊</div>
                    <h5 class="fw-700 mb-2">Pantau Pesanan Real-time</h5>
                    <p class="text-muted small mb-0">Lihat status pesananmu dari menunggu hingga siap diambil secara langsung.</p>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon" style="background:#EEF2FF;">🏪</div>
                    <h5 class="fw-700 mb-2">Kelola Warung Digital</h5>
                    <p class="text-muted small mb-0">Penjual bisa manage menu, stok, dan pesanan dari satu dashboard lengkap.</p>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon" style="background:#FFF5F5;">👑</div>
                    <h5 class="fw-700 mb-2">Admin Kontrol Penuh</h5>
                    <p class="text-muted small mb-0">Admin bisa monitor semua aktivitas, kelola penjual, dan lihat laporan.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Menu Populer --}}
@if($menu_populer->isNotEmpty())
<section class="py-5" id="menu">
    <div class="container py-3">
        <div class="d-flex align-items-end justify-content-between mb-4">
            <div>
                <span class="section-tag">Menu</span>
                <h2 class="section-title mt-2 mb-0">Menu Paling Populer</h2>
            </div>
            <a href="{{ route('register') }}" class="text-decoration-none fw-600" style="color:var(--primary);font-size:.9rem;">
                Lihat Semua <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="row g-3">
            @foreach($menu_populer as $menu)
            <div class="col-6 col-md-4 col-lg-2">
                <div class="menu-card h-100">
                    <div class="menu-card-img">
                        @if($menu->foto)
                            <img src="{{ asset('storage/'.$menu->foto) }}" alt="{{ $menu->nama }}">
                        @else
                            {{ ['🍱','🍜','🍔','🥤','🍕','🍛','🥗','🧋'][$loop->index % 8] }}
                        @endif
                    </div>
                    <div class="menu-card-body">
                        <div class="menu-card-name mb-1">{{ $menu->nama }}</div>
                        <div class="menu-card-seller mb-1"><i class="bi bi-shop me-1"></i>{{ $menu->penjual->penjualProfile?->nama_warung ?? $menu->penjual->name }}</div>
                        <div class="menu-card-price">{{ $menu->harga_format }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Warung Aktif --}}
@if($penjual->isNotEmpty())
<section class="py-5" id="warung" style="background:#F7F8FC;">
    <div class="container py-3">
        <div class="text-center mb-5">
            <span class="section-tag">Warung</span>
            <h2 class="section-title mt-2">Warung Kantin Kami</h2>
        </div>
        <div class="row g-3">
            @foreach($penjual as $p)
            <div class="col-sm-6 col-lg-4">
                <div class="warung-card">
                    <div class="warung-avatar">
                        @if($p->penjualProfile?->foto_warung)
                            <img src="{{ asset('storage/'.$p->penjualProfile->foto_warung) }}" style="width:100%;height:100%;object-fit:cover;border-radius:14px;" alt="">
                        @else
                            🏪
                        @endif
                    </div>
                    <div class="flex-1">
                        <div class="warung-name">{{ $p->penjualProfile?->nama_warung ?? $p->name }}</div>
                        <div class="warung-meta">{{ $p->menus_count }} menu tersedia</div>
                        @if($p->penjualProfile?->nomor_stand)
                        <div class="warung-meta">Stand {{ $p->penjualProfile->nomor_stand }}</div>
                        @endif
                    </div>
                    <span class="badge-open">Buka</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Penjual CTA --}}
<section class="py-5">
    <div class="container py-3">
        <div class="penjual-cta">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <span class="section-tag" style="background:rgba(255,255,255,.25);color:#fff;">Untuk Penjual</span>
                    <h2 class="mt-2 mb-2">Ingin Buka Warung di Kantin Sekolah?</h2>
                    <p style="color:rgba(255,255,255,.8);font-size:1rem;max-width:500px;">
                        Daftarkan warungmu sekarang! Kami akan review pendaftaran Anda dan menghubungi Anda setelah disetujui.
                    </p>
                    <ul class="list-unstyled mt-3 mb-0" style="color:rgba(255,255,255,.85);">
                        <li class="mb-1"><i class="bi bi-check-circle-fill me-2" style="color:#A8FFD4;"></i>Kelola menu & pesanan digital</li>
                        <li class="mb-1"><i class="bi bi-check-circle-fill me-2" style="color:#A8FFD4;"></i>Pantau pendapatan harian</li>
                        <li><i class="bi bi-check-circle-fill me-2" style="color:#A8FFD4;"></i>Gratis pendaftaran!</li>
                    </ul>
                </div>
                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                    <a href="{{ route('pendaftaran-penjual.create') }}" class="btn-cta-white">
                        <i class="bi bi-shop-window"></i>Daftar Jadi Penjual
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Footer --}}
<footer class="py-5">
    <div class="container">
        <div class="row gy-4">
            <div class="col-md-4">
                <div class="brand mb-2">Kantin<span>Ku</span></div>
                <p style="color:rgba(255,255,255,.5);font-size:.88rem;max-width:260px;">
                    Sistem pemesanan makanan kantin sekolah yang modern, mudah, dan efisien.
                </p>
            </div>
            <div class="col-md-2">
                <div class="fw-700 text-white mb-3" style="font-size:.85rem;">Menu</div>
                <ul class="list-unstyled" style="font-size:.85rem;">
                    <li class="mb-2"><a href="#fitur" class="text-decoration-none" style="color:rgba(255,255,255,.55);">Fitur</a></li>
                    <li class="mb-2"><a href="#menu" class="text-decoration-none" style="color:rgba(255,255,255,.55);">Menu</a></li>
                    <li><a href="#warung" class="text-decoration-none" style="color:rgba(255,255,255,.55);">Warung</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <div class="fw-700 text-white mb-3" style="font-size:.85rem;">Akun</div>
                <ul class="list-unstyled" style="font-size:.85rem;">
                    <li class="mb-2"><a href="{{ route('login') }}" class="text-decoration-none" style="color:rgba(255,255,255,.55);">Masuk</a></li>
                    <li class="mb-2"><a href="{{ route('register') }}" class="text-decoration-none" style="color:rgba(255,255,255,.55);">Daftar Siswa</a></li>
                    <li><a href="{{ route('pendaftaran-penjual.create') }}" class="text-decoration-none" style="color:rgba(255,255,255,.55);">Daftar Penjual</a></li>
                </ul>
            </div>
        </div>
        <hr style="border-color:rgba(255,255,255,.1);margin:2rem 0 1.5rem;">
        <div class="text-center" style="color:rgba(255,255,255,.35);font-size:.82rem;">
            &copy; {{ date('Y') }} KantinKu. Dibuat untuk Tugas Akhir SMK. 🎓
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
