<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Jadi Penjual — KantinKu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root { --primary: #FF6B35; --primary-dark: #E55A25; }

        * { box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #F7F8FC;
            min-height: 100vh;
        }

        /* ── Navbar ── */
        .navbar {
            background: #fff;
            border-bottom: 1px solid #E2E8F0;
            padding: .75rem 1.5rem;
        }
        .navbar-brand {
            font-family: 'Sora', sans-serif;
            font-weight: 900;
            font-size: 1.4rem;
            color: #1A202C;
            text-decoration: none;
        }
        .navbar-brand span { color: var(--primary); }

        /* ── Hero mini ── */
        .hero-mini {
            background: linear-gradient(135deg, #1A202C 0%, #2D3748 100%);
            padding: 2.5rem 0;
            color: #fff;
            text-align: center;
        }
        .hero-mini h1 {
            font-family: 'Sora', sans-serif;
            font-weight: 900;
            font-size: 1.75rem;
            margin: .5rem 0;
        }
        .hero-mini p {
            color: rgba(255,255,255,.65);
            font-size: .9rem;
            margin: 0;
        }

        /* ── Steps ── */
        .steps-wrap {
            display: flex;
            align-items: center;
            gap: .5rem;
            margin-bottom: 1.5rem;
        }
        .step-item {
            display: flex;
            align-items: center;
            gap: .5rem;
        }
        .step-circle {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: rgba(255,107,53,.15);
            border: 2px solid rgba(255,107,53,.35);
            display: flex; align-items: center; justify-content: center;
            font-size: .8rem; font-weight: 800;
            color: var(--primary);
            flex-shrink: 0;
        }
        .step-circle.inactive {
            background: #F0F0F0;
            border-color: #DDD;
            color: #AAA;
        }
        .step-label { font-size: .82rem; font-weight: 600; color: #4A5568; }
        .step-label.inactive { color: #A0AEC0; }
        .step-divider {
            flex: 1;
            height: 1px;
            background: #E2E8F0;
            max-width: 40px;
        }

        /* ── Cards ── */
        .section-card {
            background: #fff;
            border: 1px solid #E2E8F0;
            border-radius: 14px;
            margin-bottom: 1.25rem;
            overflow: hidden;
        }
        .section-card-header {
            padding: .875rem 1.25rem;
            border-bottom: 1px solid #E2E8F0;
            font-weight: 700;
            font-size: .95rem;
            color: #2D3748;
            display: flex;
            align-items: center;
            gap: .5rem;
            background: #FAFAFA;
        }
        .section-card-header i { color: var(--primary); }
        .section-card-body { padding: 1.25rem; }

        /* ── Form ── */
        .form-label {
            font-weight: 600;
            font-size: .85rem;
            color: #4A5568;
            margin-bottom: .35rem;
        }
        .form-control, .form-select {
            border: 1.5px solid #E2E8F0;
            border-radius: 10px;
            padding: .65rem 1rem;
            font-size: .9rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: all .2s;
            background: #fff;
            color: #2D3748;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(255,107,53,.12);
            outline: none;
        }
        .form-control.is-invalid { border-color: #FC8181; }
        .input-group .form-control { border-radius: 10px 0 0 10px; }
        .input-group .btn-eye {
            border: 1.5px solid #E2E8F0;
            border-left: none;
            border-radius: 0 10px 10px 0;
            background: #F7F8FC;
            color: #718096;
            padding: 0 .875rem;
            cursor: pointer;
            transition: all .2s;
        }
        .input-group .btn-eye:hover { background: #EDF2F7; }
        .form-text { font-size: .78rem; color: #718096; margin-top: .25rem; }

        /* ── Toggle switch ── */
        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        /* ── Rekening section ── */
        .rekening-box {
            background: #FFF8F5;
            border: 1px solid #FFD4C2;
            border-radius: 10px;
            padding: .875rem 1rem;
            margin-bottom: 1rem;
        }
        .rekening-box .info-title {
            font-size: .85rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: .25rem;
        }
        .rekening-box .info-text {
            font-size: .8rem;
            color: #C04000;
        }

        /* ── Error alert ── */
        .error-alert {
            background: #FFF5F5;
            border: 1px solid #FEB2B2;
            border-radius: 12px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.25rem;
        }
        .error-alert ul {
            margin: 0;
            padding-left: 1.25rem;
            color: #C53030;
            font-size: .875rem;
        }

        /* ── Buttons ── */
        .btn-submit {
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: .8rem 2rem;
            font-weight: 700;
            font-size: .95rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: all .2s;
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            cursor: pointer;
        }
        .btn-submit:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(255,107,53,.35);
        }
        .btn-cancel {
            background: transparent;
            color: #718096;
            border: 1.5px solid #E2E8F0;
            border-radius: 12px;
            padding: .8rem 1.5rem;
            font-weight: 600;
            font-size: .95rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: all .2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }
        .btn-cancel:hover {
            border-color: #CBD5E0;
            color: #4A5568;
            background: #F7F8FC;
        }

        /* ── Rekening section hidden ── */
        #rekeningSection { display: none; }
        #rekeningSection.show { display: block; }
    </style>
</head>
<body>

{{-- Navbar --}}
<nav class="navbar d-flex align-items-center justify-content-between">
    <a class="navbar-brand" href="{{ route('welcome') }}">Kantin<span>Ku</span></a>
    <a href="{{ route('welcome') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
</nav>

{{-- Hero --}}
<div class="hero-mini">
    <div style="font-size:2.5rem;margin-bottom:.5rem;">🏪</div>
    <h1>Daftar Jadi Penjual Kantin</h1>
    <p>Isi formulir di bawah ini. Admin akan meninjau pendaftaran Anda.</p>
</div>

{{-- Content --}}
<div class="container py-4" style="max-width: 780px;">

    {{-- Error --}}
    @if($errors->any())
    <div class="error-alert">
        <div class="fw-700 mb-1" style="color:#C53030;font-size:.9rem;">
            <i class="bi bi-exclamation-circle me-1"></i>Harap perbaiki kesalahan berikut:
        </div>
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Steps --}}
    <div class="steps-wrap">
        <div class="step-item">
            <div class="step-circle">1</div>
            <div class="step-label">Isi Formulir</div>
        </div>
        <div class="step-divider"></div>
        <div class="step-item">
            <div class="step-circle inactive">2</div>
            <div class="step-label inactive">Review Admin</div>
        </div>
        <div class="step-divider"></div>
        <div class="step-item">
            <div class="step-circle inactive">3</div>
            <div class="step-label inactive">Akun Dibuat</div>
        </div>
    </div>

    <form action="{{ route('pendaftaran-penjual.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- ①  Data Diri ─────────────────────────────────────────── --}}
        <div class="section-card">
            <div class="section-card-header">
                <i class="bi bi-person-circle"></i> Data Diri
            </div>
            <div class="section-card-body">
                <div class="row g-3">
                    {{-- Nama Lengkap --}}
                    <div class="col-sm-6">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama_lengkap"
                               class="form-control @error('nama_lengkap') is-invalid @enderror"
                               value="{{ old('nama_lengkap') }}"
                               placeholder="Nama lengkap sesuai KTP" required>
                        @error('nama_lengkap')
                        <div style="font-size:.78rem;color:#C53030;margin-top:.25rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="col-sm-6">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}"
                               placeholder="email@aktif.com" required>
                        <div class="form-text">Email ini digunakan untuk login ke sistem</div>
                        @error('email')
                        <div style="font-size:.78rem;color:#C53030;margin-top:.25rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="col-sm-6">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" name="password" id="pw1"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Min. 8 karakter" required minlength="8">
                            <button type="button" class="btn-eye" onclick="togglePw('pw1','eye1')">
                                <i class="bi bi-eye" id="eye1"></i>
                            </button>
                        </div>
                        @error('password')
                        <div style="font-size:.78rem;color:#C53030;margin-top:.25rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="col-sm-6">
                        <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" name="password_confirmation" id="pw2"
                                   class="form-control"
                                   placeholder="Ulangi password" required>
                            <button type="button" class="btn-eye" onclick="togglePw('pw2','eye2')">
                                <i class="bi bi-eye" id="eye2"></i>
                            </button>
                        </div>
                    </div>

                    {{-- No HP --}}
                    <div class="col-sm-6">
                        <label class="form-label">No. HP / WhatsApp <span class="text-danger">*</span></label>
                        <input type="text" name="phone"
                               class="form-control @error('phone') is-invalid @enderror"
                               value="{{ old('phone') }}"
                               placeholder="08xxxxxxxxxx" required>
                        @error('phone')
                        <div style="font-size:.78rem;color:#C53030;margin-top:.25rem;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- ②  Informasi Warung ─────────────────────────────────── --}}
        <div class="section-card">
            <div class="section-card-header">
                <i class="bi bi-shop"></i> Informasi Warung
            </div>
            <div class="section-card-body">
                <div class="row g-3">
                    {{-- Nama Warung --}}
                    <div class="col-sm-6">
                        <label class="form-label">Nama Warung <span class="text-danger">*</span></label>
                        <input type="text" name="nama_warung"
                               class="form-control @error('nama_warung') is-invalid @enderror"
                               value="{{ old('nama_warung') }}"
                               placeholder="Contoh: Warung Makan Bu Ani" required>
                        @error('nama_warung')
                        <div style="font-size:.78rem;color:#C53030;margin-top:.25rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Jenis Makanan --}}
                    <div class="col-sm-6">
                        <label class="form-label">Jenis Makanan <span class="text-danger">*</span></label>
                        <input type="text" name="jenis_makanan"
                               class="form-control @error('jenis_makanan') is-invalid @enderror"
                               value="{{ old('jenis_makanan') }}"
                               placeholder="Nasi, Gorengan, Minuman..." required>
                        @error('jenis_makanan')
                        <div style="font-size:.78rem;color:#C53030;margin-top:.25rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div class="col-12">
                        <label class="form-label">Deskripsi Warung <span class="text-danger">*</span></label>
                        <textarea name="deskripsi_warung" rows="3"
                                  class="form-control @error('deskripsi_warung') is-invalid @enderror"
                                  placeholder="Ceritakan tentang warung Anda, menu andalan, keunikan, dll..."
                                  required minlength="20">{{ old('deskripsi_warung') }}</textarea>
                        @error('deskripsi_warung')
                        <div style="font-size:.78rem;color:#C53030;margin-top:.25rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Foto KTP --}}
                    <div class="col-sm-6">
                        <label class="form-label">Foto KTP <span class="text-muted fw-400">(Opsional)</span></label>
                        <input type="file" name="foto_ktp" class="form-control" accept="image/*">
                        <div class="form-text">Membantu mempercepat verifikasi · JPG/PNG maks 2MB</div>
                    </div>

                    {{-- Foto Warung --}}
                    <div class="col-sm-6">
                        <label class="form-label">Foto Warung <span class="text-muted fw-400">(Opsional)</span></label>
                        <input type="file" name="foto_warung" class="form-control" accept="image/*">
                        <div class="form-text">Foto depan warung · JPG/PNG maks 2MB</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ③  Metode Pembayaran ────────────────────────────────── --}}
        <div class="section-card">
            <div class="section-card-header">
                <i class="bi bi-credit-card"></i> Metode Pembayaran
            </div>
            <div class="section-card-body">
                <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" role="switch"
                           name="terima_transfer" id="terimaTransfer" value="1"
                           {{ old('terima_transfer') ? 'checked' : '' }}
                           onchange="toggleRekening(this.checked)"
                           style="width:2.5em;height:1.25em;cursor:pointer;">
                    <label class="form-check-label ms-2" for="terimaTransfer">
                        <span class="fw-700">Menerima Pembayaran Transfer Bank</span>
                        <div class="text-muted" style="font-size:.82rem;font-weight:400;">
                            Aktifkan jika ingin menerima pembayaran via transfer bank dari siswa
                        </div>
                    </label>
                </div>

                {{-- Rekening section --}}
                <div id="rekeningSection" class="{{ old('terima_transfer') ? 'show' : '' }}">
                    <hr style="border-color:#E2E8F0;margin:1rem 0;">
                    <div class="rekening-box">
                        <div class="info-title"><i class="bi bi-info-circle me-1"></i>Informasi Rekening</div>
                        <div class="info-text">Nomor rekening ini akan ditampilkan kepada siswa saat melakukan pembayaran transfer.</div>
                    </div>
                    <div class="row g-3">
                        <div class="col-sm-4">
                            <label class="form-label">Nama Bank <span class="text-danger">*</span></label>
                            <select name="nama_bank" class="form-select">
                                <option value="">Pilih Bank</option>
                                @foreach(['BCA','BRI','BNI','Mandiri','BSI','CIMB Niaga','Danamon','Permata','BTN','BRImo'] as $bank)
                                <option value="{{ $bank }}" {{ old('nama_bank') === $bank ? 'selected' : '' }}>
                                    {{ $bank }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label">Nomor Rekening <span class="text-danger">*</span></label>
                            <input type="text" name="nomor_rekening" class="form-control"
                                   value="{{ old('nomor_rekening') }}" placeholder="1234567890">
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label">Nama Pemilik <span class="text-danger">*</span></label>
                            <input type="text" name="nama_pemilik_rekening" class="form-control"
                                   value="{{ old('nama_pemilik_rekening') }}" placeholder="Sesuai buku tabungan">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div class="d-flex align-items-center gap-3 pb-5">
            <button type="submit" class="btn-submit">
                <i class="bi bi-send-fill"></i> Kirim Pendaftaran
            </button>
            <a href="{{ route('welcome') }}" class="btn-cancel">Batal</a>
        </div>

    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function togglePw(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type    = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type    = 'password';
        icon.className = 'bi bi-eye';
    }
}

function toggleRekening(show) {
    const section = document.getElementById('rekeningSection');
    if (show) {
        section.classList.add('show');
    } else {
        section.classList.remove('show');
    }
}

// Inisialisasi state toggle
toggleRekening(document.getElementById('terimaTransfer').checked);
</script>
</body>
</html>
