<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — KantinKu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root { --primary: #FF6B35; --primary-dark: #E55A25; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #F7F8FC; min-height: 100vh; }
        .auth-wrap { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem 1rem; }
        .auth-card { background: #fff; border-radius: 24px; padding: 2.5rem; border: 1px solid #E2E8F0; box-shadow: 0 20px 60px rgba(0,0,0,.08); width: 100%; max-width: 520px; }
        .form-control { border: 1.5px solid #E2E8F0; border-radius: 10px; padding: .7rem 1rem; font-size: .9rem; transition: all .2s; }
        .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(255,107,53,.12); }
        .input-group-text { background: #F7F8FC; border: 1.5px solid #E2E8F0; color: #718096; }
        .input-group .form-control { border-left: none; }
        .input-group .form-control:focus { border-color: var(--primary); }
        .btn-auth { background: var(--primary); color: #fff; border: none; border-radius: 10px; padding: .8rem; font-weight: 700; font-size: .95rem; width: 100%; transition: all .2s; }
        .btn-auth:hover { background: var(--primary-dark); transform: translateY(-1px); box-shadow: 0 4px 15px rgba(255,107,53,.35); }
        .form-label { font-weight: 600; font-size: .85rem; color: #4A5568; }
        .brand { font-family: 'Sora', sans-serif; font-weight: 900; font-size: 1.5rem; color: #1A202C; }
        .brand span { color: var(--primary); }
        .divider { display: flex; align-items: center; gap: .75rem; color: #CBD5E0; font-size: .8rem; margin: 1rem 0; }
        .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: #E2E8F0; }
    </style>
</head>
<body>
<div class="auth-wrap">
    <div class="auth-card">
        <div class="text-center mb-4">
            <div class="brand mb-1">Kantin<span>Ku</span></div>
            <h3 class="fw-800 mb-1" style="font-family:'Sora',sans-serif;font-size:1.5rem;">Buat Akun Baru</h3>
            <p class="text-muted" style="font-size:.88rem;">Daftar sebagai siswa untuk mulai memesan makanan</p>
        </div>

        @if($errors->any())
        <div class="alert alert-danger py-2 px-3 mb-3" style="font-size:.85rem;border-radius:10px;">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="name" class="form-control" placeholder="Nama lengkapmu" value="{{ old('name') }}" required>
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control" placeholder="email@sekolah.com" value="{{ old('email') }}" required>
                    </div>
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Kelas</label>
                    <input type="text" name="kelas" class="form-control" placeholder="Contoh: XII RPL 1" value="{{ old('kelas') }}">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">NIS</label>
                    <input type="text" name="nis" class="form-control" placeholder="Nomor Induk Siswa" value="{{ old('nis') }}">
                </div>
                <div class="col-12">
                    <label class="form-label">No. HP</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-phone"></i></span>
                        <input type="text" name="phone" class="form-control" placeholder="08xxxxxxxxxx" value="{{ old('phone') }}">
                    </div>
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" id="pass1" class="form-control" placeholder="Min. 8 karakter" required>
                        <button type="button" class="btn btn-outline-secondary border-start-0" onclick="togglePw('pass1','eye1')">
                            <i class="bi bi-eye" id="eye1"></i>
                        </button>
                    </div>
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" name="password_confirmation" id="pass2" class="form-control" placeholder="Ulangi password" required>
                        <button type="button" class="btn btn-outline-secondary border-start-0" onclick="togglePw('pass2','eye2')">
                            <i class="bi bi-eye" id="eye2"></i>
                        </button>
                    </div>
                </div>
                <div class="col-12 mt-2">
                    <button type="submit" class="btn-auth">
                        <i class="bi bi-person-plus me-2"></i>Buat Akun
                    </button>
                </div>
            </div>
        </form>

        <div class="text-center mt-4" style="font-size:.88rem;">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="fw-700 text-decoration-none" style="color:var(--primary);">Masuk di sini</a>
        </div>
        <div class="text-center mt-2" style="font-size:.85rem;">
            <a href="{{ route('welcome') }}" class="text-muted text-decoration-none">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function togglePw(id, iconId) {
    const inp = document.getElementById(id);
    const icon = document.getElementById(iconId);
    if (inp.type === 'password') { inp.type = 'text'; icon.className = 'bi bi-eye-slash'; }
    else { inp.type = 'password'; icon.className = 'bi bi-eye'; }
}
</script>
</body>
</html>
