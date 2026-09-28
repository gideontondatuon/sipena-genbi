<x-guest-layout>
    <style>
        .auth-form-title {
            font-size: 1.6rem;
            font-weight: 800;
            color: #0F172A;
            letter-spacing: -0.5px;
        }

        .auth-input-group {
            background-color: #F8FAFC;
            border: 1.5px solid #E2E8F0;
            border-radius: 14px;
            overflow: hidden;
            transition: all 0.2s ease;
        }

        .auth-input-group:focus-within {
            border-color: #002B66;
            background-color: #FFFFFF;
            box-shadow: 0 0 0 4px rgba(0, 43, 102, 0.1);
        }

        .auth-input-group .input-group-text {
            background: transparent;
            border: none;
            color: #64748B;
            padding-left: 1rem;
            padding-right: 0.5rem;
        }

        .auth-input-group .form-control {
            background: transparent;
            border: none;
            padding: 0.75rem 1rem 0.75rem 0.25rem;
            font-size: 0.95rem;
            color: #0F172A;
            box-shadow: none !important;
        }

        .btn-genbi-submit {
            background: linear-gradient(135deg, #002B66 0%, #001A4D 100%);
            color: #FFFFFF;
            font-weight: 700;
            padding: 0.85rem 1.5rem;
            border-radius: 14px;
            border: none;
            transition: all 0.25s ease;
            box-shadow: 0 6px 20px rgba(0, 43, 102, 0.25);
            letter-spacing: 0.5px;
        }

        .btn-genbi-submit:hover {
            background: linear-gradient(135deg, #D90429 0%, #B90322 100%);
            color: #FFFFFF;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(217, 4, 41, 0.35);
        }

        .info-box {
            background: #EFF6FF;
            border: 1px solid #BFDBFE;
            border-radius: 14px;
            padding: 1rem 1.1rem;
        }
    </style>

    <!-- Header -->
    <div class="mb-4 text-center text-md-start">
        <!-- Logo Mobile Only -->
        <div class="d-md-none text-center mb-3">
            <div class="bg-white rounded-circle shadow-sm d-inline-flex align-items-center justify-content-center mb-2" style="padding: 5px; width: 52px; height: 52px;">
                <img src="{{ asset('images/genbi-polimdo.png') }}" alt="Logo GenBI Polimdo" style="height: 42px; width: 42px; object-fit: contain; border-radius: 50%;">
            </div>
            <div class="fw-extrabold text-dark fs-5">SIPENA <span class="text-danger">GenBI</span></div>
        </div>

        <h3 class="auth-form-title mb-1">Lupa Kata Sandi?</h3>
        <p class="text-muted small mb-0">Masukkan email Anda dan kami akan mengirimkan link untuk membuat kata sandi baru.</p>
    </div>

    <!-- Session Status (success message) -->
    @if (session('status'))
        <div class="alert border-0 rounded-3 mb-3 d-flex align-items-center gap-2" style="background: #DCFCE7; color: #166534;">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <span class="small fw-medium">{{ session('status') }}</span>
        </div>
    @endif

    <!-- Info Box -->
    <div class="info-box mb-4 d-flex align-items-start gap-2">
        <i class="bi bi-envelope-at-fill text-primary mt-1" style="font-size: 1.1rem;"></i>
        <div class="small text-secondary lh-base">
            Link reset kata sandi akan dikirim ke <strong>email terdaftar</strong> Anda. Pastikan email yang dimasukkan sesuai dengan yang didaftarkan ke sistem.
        </div>
    </div>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email -->
        <div class="mb-4">
            <label for="email" class="form-label fw-bold text-dark small mb-2">Alamat Email</label>
            <div class="input-group auth-input-group">
                <span class="input-group-text">
                    <i class="bi bi-envelope-fill fs-6"></i>
                </span>
                <input id="email"
                       type="email"
                       name="email"
                       value="{{ old('email') }}"
                       class="form-control @error('email') is-invalid @enderror"
                       placeholder="contoh@email.com"
                       required
                       autofocus
                       autocomplete="email">
            </div>
            @error('email')
                <div class="text-danger small mt-1">
                    <i class="bi bi-exclamation-circle-fill me-1"></i> {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Submit -->
        <button type="submit" class="btn btn-genbi-submit w-100 mb-3 d-flex align-items-center justify-content-center gap-2">
            <i class="bi bi-send-fill fs-5"></i>
            <span>KIRIM LINK RESET KATA SANDI</span>
        </button>
    </form>

    <!-- Back to Login -->
    <div class="text-center mt-3 pt-2 border-top">
        <a href="{{ route('login') }}" class="fw-semibold text-decoration-none small" style="color: #002B66;">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Halaman Masuk
        </a>
    </div>
</x-guest-layout>

