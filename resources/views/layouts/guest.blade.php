<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Masuk - SIPENA GenBI (Generasi Baru Indonesia)</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --genbi-blue: #002B66;
            --genbi-blue-dark: #0A192F;
            --genbi-red: #D90429;
            --genbi-blue-light: #F0F4FA;
        }

        html, body {
            width: 100% !important;
            max-width: 100% !important;
            overflow-x: hidden !important;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #071325 0%, #002B66 60%, #0F172A 100%);
            min-height: 100vh;
            color: #0F172A;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .auth-wrapper {
            width: 100%;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.25rem;
            position: relative;
            z-index: 2;
            overflow: hidden;
        }

        /* Decorative background glowing elements */
        .bg-glow-1 {
            position: absolute;
            top: -10%;
            left: -10%;
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, rgba(217, 4, 41, 0.25) 0%, rgba(217, 4, 41, 0) 70%);
            border-radius: 50%;
            z-index: 0;
            pointer-events: none;
            max-width: 100vw;
        }

        .bg-glow-2 {
            position: absolute;
            bottom: -10%;
            right: 0;
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, rgba(0, 43, 102, 0.6) 0%, rgba(0, 43, 102, 0) 70%);
            border-radius: 50%;
            z-index: 0;
            pointer-events: none;
            max-width: 100vw;
        }

        .auth-card-container {
            width: 100%;
            max-width: 920px;
            margin: 0 auto;
            position: relative;
            z-index: 10;
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.97);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 28px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.35);
            z-index: 1;
            overflow: hidden;
            width: 100%;
        }

        .brand-section {
            background: linear-gradient(135deg, #0A192F 0%, var(--genbi-blue) 100%);
            border-right: 3px solid var(--genbi-red);
            color: #FFFFFF;
            position: relative;
        }

        .btn-genbi-primary {
            background: linear-gradient(135deg, var(--genbi-blue) 0%, #001A4D 100%);
            color: #FFFFFF;
            font-weight: 700;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            border: none;
            transition: all 0.25s ease;
            box-shadow: 0 4px 15px rgba(0, 43, 102, 0.3);
        }

        .btn-genbi-primary:hover {
            background: linear-gradient(135deg, var(--genbi-red) 0%, #B30000 100%);
            color: #FFFFFF;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(217, 4, 41, 0.4);
        }

        .form-control:focus {
            border-color: var(--genbi-blue);
            box-shadow: 0 0 0 0.25rem rgba(0, 43, 102, 0.15);
        }

        @media (max-width: 767.98px) {
            .auth-wrapper {
                padding: 1.75rem 1rem;
            }
            .auth-card-container {
                max-width: 395px !important;
            }
            .auth-card {
                border-radius: 24px !important;
            }
        }
    </style>
</head>
<body>
    <div class="bg-glow-1"></div>
    <div class="bg-glow-2"></div>

    <div class="auth-wrapper">
        <div class="auth-card-container">
            <div class="auth-card">
                <div class="row g-0">
                    <!-- Left Hero Branding (visible on md and up) -->
                    <div class="col-md-5 brand-section p-4 p-lg-5 d-none d-md-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-4">
                                <div class="bg-white px-3 py-2 rounded-3 shadow-sm d-inline-flex align-items-center justify-content-center gap-2">
                                    <img src="{{ asset('images/genbi-logo.png') }}" alt="Logo GenBI" style="height: 38px; width: auto; object-fit: contain;">
                                    <div style="height: 24px; width: 1px; background-color: #CBD5E1;"></div>
                                    <img src="{{ asset('images/genbi-polimdo.png') }}" alt="Logo GenBI Polimdo" style="height: 38px; width: 38px; object-fit: contain;">
                                </div>
                                <div class="d-flex flex-column ms-1">
                                    <span class="fs-4 fw-extrabold text-white lh-1">SIPENA <span class="text-danger">GenBI</span></span>
                                    <span class="text-white-50 mt-1" style="font-size: 0.65rem; font-weight: 500; letter-spacing: 0.2px;">Sistem Pelaporan Engagement Instagram</span>
                                </div>
                            </div>
                            <h3 class="fw-bold text-white mb-3">Sistem Pelaporan Engagement Instagram</h3>
                            <p class="text-white-50 fs-6">Platform monitoring dan pelaporan tugas harian Instagram bagi anggota Generasi Baru Indonesia (GenBI).</p>
                        </div>

                        <div class="pt-4 border-top border-white-10">
                            <div class="d-flex align-items-center gap-2 text-white-50 small mb-2">
                                <i class="bi bi-shield-check text-danger fs-5"></i>
                                <span>Diproteksi dengan Enkripsi & Peran Akses</span>
                            </div>
                            <div class="text-white-50 small">
                                &copy; {{ date('Y') }} GenBI - Pembina Bank Indonesia
                            </div>
                        </div>
                    </div>

                    <!-- Right Form Section -->
                    <div class="col-12 col-md-7 p-4 p-sm-4 p-lg-5 d-flex flex-column justify-content-center">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

