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

        .btn-toggle-pwd {
            background: transparent;
            border: none;
            color: #64748B;
            padding-right: 1rem;
            padding-left: 0.5rem;
            transition: color 0.2s;
            cursor: pointer;
            z-index: 5;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-toggle-pwd:hover {
            color: #002B66;
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

        <h3 class="auth-form-title mb-1">Buat Kata Sandi Baru</h3>
        <p class="text-muted small mb-0">Masukkan kata sandi baru untuk akun Anda.</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email (hidden pre-filled) -->
        <div class="mb-3">
            <label for="email" class="form-label fw-bold text-dark small mb-2">Alamat Email</label>
            <div class="input-group auth-input-group">
                <span class="input-group-text">
                    <i class="bi bi-envelope-fill fs-6"></i>
                </span>
                <input id="email"
                       type="email"
                       name="email"
                       value="{{ old('email', $request->email) }}"
                       class="form-control @error('email') is-invalid @enderror"
                       required
                       autocomplete="username"
                       readonly
                       style="background: #F1F5F9; cursor: not-allowed;">
            </div>
            @error('email')
                <div class="text-danger small mt-1">
                    <i class="bi bi-exclamation-circle-fill me-1"></i> {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Password Baru -->
        <div class="mb-3">
            <label for="password" class="form-label fw-bold text-dark small mb-2">Kata Sandi Baru</label>
            <div class="input-group auth-input-group">
                <span class="input-group-text">
                    <i class="bi bi-lock-fill fs-6"></i>
                </span>
                <input id="password"
                       type="password"
                       name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="min. 8 karakter"
                       required
                       autocomplete="new-password">
                <button class="btn-toggle-pwd" type="button" onclick="togglePwd('password', 'icon1')" title="Lihat Kata Sandi">
                    <i class="bi bi-eye-fill fs-5" id="icon1"></i>
                </button>
            </div>
            @error('password')
                <div class="text-danger small mt-1">
                    <i class="bi bi-exclamation-circle-fill me-1"></i> {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Konfirmasi Password -->
        <div class="mb-4">
            <label for="password_confirmation" class="form-label fw-bold text-dark small mb-2">Konfirmasi Kata Sandi</label>
            <div class="input-group auth-input-group">
                <span class="input-group-text">
                    <i class="bi bi-shield-lock-fill fs-6"></i>
                </span>
                <input id="password_confirmation"
                       type="password"
                       name="password_confirmation"
                       class="form-control @error('password_confirmation') is-invalid @enderror"
                       placeholder="ulangi kata sandi baru"
                       required
                       autocomplete="new-password">
                <button class="btn-toggle-pwd" type="button" onclick="togglePwd('password_confirmation', 'icon2')" title="Lihat Kata Sandi">
                    <i class="bi bi-eye-fill fs-5" id="icon2"></i>
                </button>
            </div>
            @error('password_confirmation')
                <div class="text-danger small mt-1">
                    <i class="bi bi-exclamation-circle-fill me-1"></i> {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Submit -->
        <button type="submit" class="btn btn-genbi-submit w-100 mb-3 d-flex align-items-center justify-content-center gap-2">
            <i class="bi bi-key-fill fs-5"></i>
            <span>SIMPAN KATA SANDI BARU</span>
        </button>
    </form>

    <!-- Back to Login -->
    <div class="text-center mt-3 pt-2 border-top">
        <a href="{{ route('login') }}" class="fw-semibold text-decoration-none small" style="color: #002B66;">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Halaman Masuk
        </a>
    </div>

    <script>
        function togglePwd(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input && icon) {
                const isPassword = input.getAttribute('type') === 'password';
                input.setAttribute('type', isPassword ? 'text' : 'password');
                icon.classList.toggle('bi-eye-fill', !isPassword);
                icon.classList.toggle('bi-eye-slash-fill', isPassword);
            }
        }
    </script>
</x-guest-layout>

