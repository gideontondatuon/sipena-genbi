<x-guest-layout>
    <style>
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

        .envelope-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #EFF6FF, #DBEAFE);
            border: 2px solid #BFDBFE;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(0,43,102,0.15); }
            50% { transform: scale(1.04); box-shadow: 0 0 0 10px rgba(0,43,102,0); }
        }

        .info-box {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 14px;
            padding: 1rem 1.1rem;
        }
    </style>

    <!-- Header -->
    <div class="mb-4 text-center">
        <!-- Logo Mobile Only -->
        <div class="d-md-none text-center mb-3">
            <div class="bg-white rounded-circle shadow-sm d-inline-flex align-items-center justify-content-center mb-2" style="padding: 5px; width: 52px; height: 52px;">
                <img src="{{ asset('images/genbi-polimdo.png') }}" alt="Logo GenBI Polimdo" style="height: 42px; width: 42px; object-fit: contain; border-radius: 50%;">
            </div>
            <div class="fw-extrabold text-dark fs-5">SIPENA <span class="text-danger">GenBI</span></div>
        </div>

        <!-- Envelope Animation -->
        <div class="envelope-icon">
            <i class="bi bi-envelope-check-fill text-primary" style="font-size: 2rem;"></i>
        </div>

        <h3 class="fw-bold mb-1" style="font-size: 1.5rem; color: #0F172A; letter-spacing: -0.3px;">Verifikasi Email Anda</h3>
        <p class="text-muted small mb-0">Satu langkah lagi sebelum Anda bisa menggunakan sistem SIPENA GenBI.</p>
    </div>

    <!-- Info Box -->
    <div class="info-box mb-4 d-flex align-items-start gap-2">
        <i class="bi bi-info-circle-fill text-primary mt-1" style="font-size: 1rem; flex-shrink: 0;"></i>
        <div class="small text-secondary lh-base">
            Kami telah mengirimkan link verifikasi ke <strong class="text-dark">{{ auth()->user()->email }}</strong>.
            Silakan buka email Anda dan klik link verifikasi untuk mengaktifkan akun.
        </div>
    </div>

    <!-- Success Alert -->
    @if (session('status') == 'verification-link-sent')
        <div class="alert border-0 rounded-3 mb-3 d-flex align-items-center gap-2" style="background: #DCFCE7; color: #166534;">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <span class="small fw-medium">Link verifikasi baru telah dikirim ke email Anda. Periksa folder Spam jika tidak ada di Inbox.</span>
        </div>
    @endif

    <!-- Resend Button -->
    <form method="POST" action="{{ route('verification.send') }}" class="mb-3">
        @csrf
        <button type="submit" class="btn btn-genbi-submit w-100 d-flex align-items-center justify-content-center gap-2">
            <i class="bi bi-send-fill fs-5"></i>
            <span>KIRIM ULANG EMAIL VERIFIKASI</span>
        </button>
    </form>

    <!-- Logout -->
    <div class="text-center pt-2 border-top">
        <form method="POST" action="{{ route('logout') }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-link text-muted small text-decoration-none p-0">
                <i class="bi bi-box-arrow-left me-1"></i> Keluar & Ganti Akun
            </button>
        </form>
    </div>
</x-guest-layout>

