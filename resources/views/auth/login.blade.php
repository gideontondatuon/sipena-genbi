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

        .demo-box-premium {
            background: #F0F4FA;
            border: 1px solid #CBD5E1;
            border-radius: 16px;
            padding: 1rem;
        }
    </style>

    <!-- Header Form Section -->
    <div class="mb-4 text-center text-md-start">
        <!-- Logo Branding Mobile Only -->
        <div class="d-md-none text-center mb-3">
            <div class="bg-white px-3 py-2 rounded-3 shadow-sm d-inline-flex align-items-center justify-content-center gap-2 mb-2 border">
                <img src="{{ asset('images/genbi-logo.png') }}" alt="Logo GenBI" style="height: 30px; width: auto; object-fit: contain;">
                <div style="height: 18px; width: 1px; background-color: #CBD5E1;"></div>
                <img src="{{ asset('images/genbi-polimdo.png') }}" alt="Logo GenBI Polimdo" style="height: 30px; width: 30px; object-fit: contain;">
            </div>
            <div class="fw-extrabold text-dark fs-5">SIPENA <span class="text-danger">GenBI</span></div>
        </div>

        <h3 class="auth-form-title mb-1">Masuk ke Sistem</h3>
        <p class="text-muted small mb-0">Silakan masukkan alamat email dan kata sandi akun Anda.</p>
    </div>

    <!-- Session Status Alert -->
    <x-auth-session-status class="alert alert-info rounded-3 border-0 shadow-sm mb-3" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" id="loginForm">
        @csrf

        <!-- Input Email -->
        <div class="mb-3">
            <label for="email" class="form-label fw-bold text-dark small mb-1.5">Alamat Email</label>
            <div class="input-group auth-input-group">
                <span class="input-group-text">
                    <i class="bi bi-envelope-fill fs-6"></i>
                </span>
                <input id="email" 
                       type="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       class="form-control @error('email') is-invalid @enderror" 
                       placeholder="masukkan email anda..." 
                       required 
                       autofocus 
                       autocomplete="username">
            </div>
            @error('email')
                <div class="text-danger small mt-1">
                    <i class="bi bi-exclamation-circle-fill me-1"></i> {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Input Password -->
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1.5">
                <label for="password" class="form-label fw-bold text-dark small mb-0">Kata Sandi</label>
                @if (Route::has('password.request'))
                    <a class="text-decoration-none small text-danger fw-semibold" href="{{ route('password.request') }}">
                        Lupa kata sandi?
                    </a>
                @endif
            </div>
            <div class="input-group auth-input-group">
                <span class="input-group-text">
                    <i class="bi bi-lock-fill fs-6"></i>
                </span>
                <input id="password" 
                       type="password" 
                       name="password" 
                       class="form-control @error('password') is-invalid @enderror" 
                       placeholder="••••••••" 
                       required 
                       autocomplete="current-password">
                <button class="btn-toggle-pwd" type="button" id="togglePassword" onclick="togglePasswordVisibility()" title="Lihat Kata Sandi">
                    <i class="bi bi-eye-fill fs-5" id="togglePasswordIcon"></i>
                </button>
            </div>
            @error('password')
                <div class="text-danger small mt-1">
                    <i class="bi bi-exclamation-circle-fill me-1"></i> {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Remember Me Checkbox -->
        <div class="mb-4 d-flex align-items-center">
            <input id="remember_me" type="checkbox" class="form-check-input me-2" name="remember" style="width: 18px; height: 18px; cursor: pointer;">
            <label for="remember_me" class="form-check-label small text-muted user-select-none" style="cursor: pointer;">
                Ingat saya di perangkat ini
            </label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-genbi-submit w-100 mb-3 d-flex align-items-center justify-content-center gap-2">
            <i class="bi bi-shield-lock-fill fs-5"></i>
            <span>MASUK KE SISTEM</span>
        </button>
    </form>

    <!-- Register Link Footer -->
    <div class="text-center mt-4 pt-2 border-top">
        <span class="text-muted small">Belum memiliki akun?</span>
        <a href="{{ route('register') }}" class="fw-bold text-decoration-none text-danger small ms-1">
            Daftar Anggota Baru <i class="bi bi-arrow-right ms-0.5"></i>
        </a>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('togglePasswordIcon');
            if (passwordInput && toggleIcon) {
                const isPassword = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                if (isPassword) {
                    toggleIcon.classList.remove('bi-eye-fill');
                    toggleIcon.classList.add('bi-eye-slash-fill');
                } else {
                    toggleIcon.classList.remove('bi-eye-slash-fill');
                    toggleIcon.classList.add('bi-eye-fill');
                }
            }
        }
    </script>
</x-guest-layout>

