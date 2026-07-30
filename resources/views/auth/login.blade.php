<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — KantinKu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root { --primary: #FF6B35; --primary-dark: #E55A25; }

        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* ── Full page background ── */
        .page-wrapper {
            min-height: 100vh;
            width: 100%;
            background: linear-gradient(135deg, #0F1923 0%, #1A202C 50%, #1E2D3D 100%);
            display: grid;
            grid-template-columns: 1fr 460px;
            position: relative;
            overflow: hidden;
        }

        /* Glow effects */
        .page-wrapper::before {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(255,107,53,.15) 0%, transparent 70%);
            top: -200px;
            left: -100px;
            pointer-events: none;
        }
        .page-wrapper::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(72,187,120,.08) 0%, transparent 70%);
            bottom: -100px;
            right: 100px;
            pointer-events: none;
        }

        /* ── Floating emojis ── */
        .emoji-float {
            position: absolute;
            font-size: 2.2rem;
            opacity: 0.06;
            pointer-events: none;
            animation: floatUp 7s ease-in-out infinite;
            user-select: none;
        }
        @keyframes floatUp {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33%       { transform: translateY(-20px) rotate(5deg); }
            66%       { transform: translateY(-8px) rotate(-3deg); }
        }

        /* ── LEFT PANEL ── */
        .left-panel {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 4rem 4rem 4rem 5rem;
        }

        .brand-logo {
            font-family: 'Sora', sans-serif;
            font-weight: 900;
            font-size: 1.9rem;
            color: #ffffff;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 3rem;
        }
        .brand-logo span { color: var(--primary); }

        .left-title {
            font-family: 'Sora', sans-serif;
            font-weight: 900;
            font-size: 2.4rem;
            color: #fff;
            line-height: 1.2;
            margin-bottom: 1rem;
        }

        .left-subtitle {
            color: rgba(255,255,255,.55);
            font-size: .95rem;
            line-height: 1.75;
            max-width: 360px;
            margin-bottom: 2.5rem;
        }

        .feature-row {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .feature-icon-box {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            flex-shrink: 0;
        }
        .feature-label {
            color: rgba(255,255,255,.7);
            font-size: .9rem;
            font-weight: 500;
        }

        /* Mini card dekorasi di bawah */
        .deco-card {
            display: inline-flex;
            align-items: center;
            gap: .875rem;
            background: rgba(255,255,255,.06);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 14px;
            padding: .875rem 1.25rem;
            margin-top: 3rem;
            max-width: 320px;
        }
        .deco-card-icon {
            width: 38px; height: 38px;
            background: rgba(255,107,53,.2);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }
        .deco-card-title { font-size: .85rem; font-weight: 700; color: #fff; }
        .deco-card-sub   { font-size: .75rem; color: rgba(255,255,255,.4); }

        /* ── RIGHT PANEL ── */
        .right-panel {
            position: relative;
            z-index: 1;
            background: rgba(255,255,255,.04);
            border-left: 1px solid rgba(255,255,255,.07);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 2.5rem;
        }

        /* ── LOGIN CARD ── */
        .login-box {
            width: 100%;
            max-width: 360px;
        }

        .login-title {
            font-family: 'Sora', sans-serif;
            font-weight: 800;
            font-size: 1.65rem;
            color: #fff;
            margin-bottom: .4rem;
        }
        .login-sub {
            color: rgba(255,255,255,.45);
            font-size: .875rem;
            margin-bottom: 1.75rem;
        }

        /* Error */
        .err-box {
            background: rgba(252,129,129,.1);
            border: 1px solid rgba(252,129,129,.3);
            border-radius: 10px;
            padding: .7rem 1rem;
            color: #FCA5A5;
            font-size: .85rem;
            margin-bottom: 1.25rem;
        }

        /* Field label */
        .f-label {
            display: block;
            font-size: .82rem;
            font-weight: 700;
            color: rgba(255,255,255,.55);
            margin-bottom: .4rem;
            letter-spacing: .3px;
        }

        /* Input group custom */
        .field-group {
            position: relative;
            margin-bottom: 1.1rem;
        }
        .field-icon {
            position: absolute;
            left: .9rem;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,.25);
            font-size: .95rem;
            pointer-events: none;
            z-index: 2;
        }
        .f-input {
            display: block;
            width: 100%;
            background: rgba(255,255,255,.07);
            border: 1.5px solid rgba(255,255,255,.12);
            border-radius: 12px;
            padding: .8rem 1rem .8rem 2.6rem;
            color: #fff;
            font-size: .9rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: border-color .2s, background .2s, box-shadow .2s;
            outline: none;
            box-sizing: border-box;
        }
        .f-input::placeholder { color: rgba(255,255,255,.2); }
        .f-input:focus {
            border-color: var(--primary);
            background: rgba(255,107,53,.07);
            box-shadow: 0 0 0 3px rgba(255,107,53,.15);
        }

        /* Password toggle */
        .pw-toggle {
            position: absolute;
            right: .875rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: rgba(255,255,255,.3);
            cursor: pointer;
            padding: 0;
            font-size: 1rem;
            z-index: 2;
            transition: color .2s;
        }
        .pw-toggle:hover { color: rgba(255,255,255,.7); }

        /* Remember */
        .check-wrap {
            display: flex;
            align-items: center;
            gap: .5rem;
            margin: .25rem 0 1.5rem;
        }
        .check-wrap input {
            width: 16px; height: 16px;
            accent-color: var(--primary);
            cursor: pointer;
            flex-shrink: 0;
        }
        .check-wrap label {
            font-size: .85rem;
            color: rgba(255,255,255,.5);
            cursor: pointer;
        }

        /* Submit */
        .btn-masuk {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            width: 100%;
            padding: .875rem;
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: .95rem;
            font-weight: 700;
            font-family: 'Plus Jakarta Sans', sans-serif;
            cursor: pointer;
            transition: all .2s;
        }
        .btn-masuk:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(255,107,53,.4);
        }

        /* Divider */
        .or-divider {
            display: flex;
            align-items: center;
            gap: .75rem;
            margin: 1.25rem 0;
        }
        .or-line { flex: 1; height: 1px; background: rgba(255,255,255,.1); }
        .or-text  { font-size: .78rem; color: rgba(255,255,255,.3); }

        /* Bottom links */
        .bottom-links { text-align: center; }
        .bottom-links a {
            font-size: .85rem;
            color: rgba(255,255,255,.4);
            text-decoration: none;
            transition: color .2s;
        }
        .bottom-links a:hover { color: rgba(255,255,255,.75); }
        .bottom-links .link-reg {
            color: var(--primary);
            font-weight: 700;
        }
        .bottom-links .link-reg:hover { color: #FF8C65; }

        /* ── Responsive ── */
        @media (max-width: 992px) {
            .page-wrapper {
                grid-template-columns: 1fr;
            }
            .left-panel { display: none; }
            .right-panel {
                background: transparent;
                border-left: none;
                padding: 2rem 1.5rem;
                align-items: flex-start;
                padding-top: 3rem;
            }
            .login-box { max-width: 420px; margin: 0 auto; }
        }
    </style>
</head>
<body>

<div class="page-wrapper">

    {{-- Floating emoji --}}
    <span class="emoji-float" style="top:8%;left:6%;animation-delay:0s;">🍱</span>
    <span class="emoji-float" style="top:22%;left:14%;animation-delay:1.5s;font-size:1.5rem;">🥤</span>
    <span class="emoji-float" style="top:55%;left:5%;animation-delay:.8s;">🍜</span>
    <span class="emoji-float" style="top:78%;left:18%;animation-delay:2.2s;font-size:1.5rem;">🍔</span>
    <span class="emoji-float" style="top:40%;left:32%;animation-delay:1.1s;font-size:1.3rem;">⭐</span>
    <span class="emoji-float" style="top:70%;left:38%;animation-delay:3s;font-size:1.3rem;">🍿</span>

    {{-- ─── LEFT PANEL ──────────────────────────────────── --}}
    <div class="left-panel">
        <a href="{{ route('welcome') }}" class="brand-logo">Kantin<span>Ku</span></a>

        <div class="left-title">Selamat Datang<br>Kembali! 👋</div>
        <div class="left-subtitle">
            Masuk ke akun Anda untuk mulai memesan makanan favorit dari kantin sekolah.
        </div>

        <div>
            <div class="feature-row">
                <div class="feature-icon-box" style="background:rgba(255,107,53,.15);">🛒</div>
                <div class="feature-label">Pesan menu favoritmu dengan mudah</div>
            </div>
            <div class="feature-row">
                <div class="feature-icon-box" style="background:rgba(72,187,120,.15);">📱</div>
                <div class="feature-label">Pantau status pesanan real-time</div>
            </div>
            <div class="feature-row">
                <div class="feature-icon-box" style="background:rgba(246,173,85,.15);">⚡</div>
                <div class="feature-label">Tanpa antri, tinggal ambil!</div>
            </div>
        </div>

        <div class="deco-card">
            <div class="deco-card-icon">🔔</div>
            <div>
                <div class="deco-card-title">Pesanan Siap Diambil!</div>
                <div class="deco-card-sub">Notifikasi langsung untuk kamu</div>
            </div>
        </div>
    </div>

    {{-- ─── RIGHT PANEL ─────────────────────────────────── --}}
    <div class="right-panel">
        <div class="login-box">

            {{-- Mobile brand --}}
            <div class="d-block d-lg-none text-center mb-4">
                <a href="{{ route('welcome') }}" class="brand-logo" style="margin-bottom:0;">Kantin<span>Ku</span></a>
            </div>

            <div class="login-title">Masuk ke Akun</div>
            <div class="login-sub">Masukkan email dan password Anda</div>

            {{-- Error --}}
            @if($errors->any())
            <div class="err-box">
                <i class="bi bi-exclamation-circle me-1"></i>{{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email --}}
                <div>
                    <label class="f-label" for="email">Email</label>
                    <div class="field-group">
                        <i class="bi bi-envelope field-icon"></i>
                        <input type="email" name="email" id="email"
                               class="f-input"
                               placeholder="nama@email.com"
                               value="{{ old('email') }}"
                               required autofocus>
                    </div>
                </div>

                {{-- Password --}}
                <div>
                    <label class="f-label" for="pwInput">Password</label>
                    <div class="field-group">
                        <i class="bi bi-lock field-icon"></i>
                        <input type="password" name="password" id="pwInput"
                               class="f-input"
                               placeholder="••••••••"
                               required>
                        <button type="button" class="pw-toggle" onclick="togglePw()">
                            <i class="bi bi-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                {{-- Remember --}}
                <div class="check-wrap">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Ingat saya</label>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-masuk">
                    <i class="bi bi-box-arrow-in-right"></i> Masuk
                </button>
            </form>

            <div class="or-divider">
                <div class="or-line"></div>
                <div class="or-text">atau</div>
                <div class="or-line"></div>
            </div>

            <div class="bottom-links">
                <div style="margin-bottom:.5rem;">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="link-reg">Daftar di sini</a>
                </div>
                <a href="{{ route('welcome') }}">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Beranda
                </a>
            </div>

        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function togglePw() {
    const inp  = document.getElementById('pwInput');
    const icon = document.getElementById('eyeIcon');
    if (inp.type === 'password') {
        inp.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        inp.type = 'password';
        icon.className = 'bi bi-eye';
    }
}
</script>
</body>
</html>
