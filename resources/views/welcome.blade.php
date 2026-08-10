<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIPENA GenBI - Sistem Penilaian & Pelaporan GenBI</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --genbi-blue: #002B66;
            --genbi-blue-dark: #0A192F;
            --genbi-red: #D90429;
            --genbi-red-hover: #B90322;
            --genbi-gold: #F59E0B;
            --genbi-bg: #F8FAFC;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--genbi-bg);
            color: #0F172A;
            overflow-x: hidden;
        }

        /* Hero Section Premium Styling */
        .hero-section {
            background: linear-gradient(135deg, #071326 0%, #002B66 50%, #001A4D 100%);
            position: relative;
            color: #FFFFFF;
            padding: 5.5rem 0 6rem;
            border-bottom-left-radius: 48px;
            border-bottom-right-radius: 48px;
            border-bottom: 4px solid var(--genbi-red);
            box-shadow: 0 12px 40px rgba(0, 43, 102, 0.25);
            overflow: hidden;
        }

        .hero-bg-glow {
            position: absolute;
            top: -20%;
            left: 50%;
            transform: translateX(-50%);
            width: 700px;
            height: 700px;
            background: radial-gradient(circle, rgba(0, 102, 255, 0.18) 0%, rgba(217, 4, 41, 0.08) 50%, rgba(0, 0, 0, 0) 70%);
            pointer-events: none;
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .navbar-logo-glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 6px 16px;
            border-radius: 14px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            display: inline-flex;
            align-items: center;
            gap: 10px;
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .btn-genbi-red {
            background: linear-gradient(135deg, var(--genbi-red) 0%, #B90322 100%);
            color: #FFFFFF;
            font-weight: 700;
            border-radius: 14px;
            border: none;
            transition: all 0.25s ease;
            box-shadow: 0 6px 20px rgba(217, 4, 41, 0.35);
        }

        .btn-genbi-red:hover {
            background: linear-gradient(135deg, #B90322 0%, #8A0017 100%);
            color: #FFFFFF;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(217, 4, 41, 0.5);
        }

        .btn-glass-outline {
            background: rgba(255, 255, 255, 0.08);
            border: 1.5px solid rgba(255, 255, 255, 0.25);
            color: #FFFFFF;
            backdrop-filter: blur(8px);
            transition: all 0.25s ease;
        }

        .btn-glass-outline:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.45);
            color: #FFFFFF;
            transform: translateY(-2px);
        }

        /* User Welcome Card (If Logged In) */
        .user-welcome-card {
            background: rgba(255, 255, 255, 0.09);
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(14px);
            border-radius: 24px;
            padding: 1.75rem 2rem;
            max-width: 520px;
            margin: 0 auto 1.5rem auto;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
        }

        .avatar-circle-hero {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, #FFFFFF 0%, #E2E8F0 100%);
            color: var(--genbi-blue);
            font-weight: 800;
            font-size: 1.35rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.75rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            border: 3px solid rgba(255, 255, 255, 0.8);
        }

        /* Stats Bar Floating */
        .stats-floating-bar {
            background: #FFFFFF;
            border-radius: 20px;
            border: 1px solid #E2E8F0;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
            margin-top: -3.5rem;
            position: relative;
            z-index: 10;
            padding: 1.5rem;
        }

        .stat-item-number {
            font-size: 1.85rem;
            font-weight: 800;
            color: var(--genbi-blue);
            line-height: 1.1;
        }

        .stat-item-label {
            font-size: 0.825rem;
            font-weight: 600;
            color: #64748B;
            margin-top: 2px;
        }

        /* Feature Cards */
        .feature-card {
            background: #FFFFFF;
            border-radius: 22px;
            padding: 2.25rem 1.75rem;
            border: 1px solid #E2E8F0;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.03);
            transition: all 0.25s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 32px rgba(0, 43, 102, 0.09);
            border-color: #CBD5E1;
        }

        .icon-box-feature {
            width: 64px;
            height: 64px;
            border-radius: 18px;
            background: linear-gradient(135deg, #F0F4FA 0%, #E2EAF4 100%);
            color: var(--genbi-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.85rem;
            margin: 0 auto 1.25rem;
            box-shadow: 0 4px 10px rgba(0, 43, 102, 0.06);
        }

        @media (max-width: 575.98px) {
            .hero-section {
                padding: 4rem 0 4.5rem;
                border-bottom-left-radius: 30px;
                border-bottom-right-radius: 30px;
            }
            .stats-floating-bar {
                margin-top: -2.5rem;
                padding: 1rem;
            }
            .stat-item-number {
                font-size: 1.4rem;
            }
            .user-welcome-card {
                padding: 1.25rem;
                border-radius: 18px;
            }
        }
    </style>
</head>
<body>

    <!-- Header Navigation Bar -->
    <nav class="navbar navbar-dark bg-transparent position-absolute w-100 py-3 no-print" style="z-index: 20;">
        <div class="container justify-content-center">
            <a class="navbar-brand d-flex align-items-center gap-2 m-0" href="/">
                <div class="navbar-logo-glass">
                    <img src="{{ asset('images/genbi-logo.png') }}" alt="Logo GenBI" style="height: 32px; width: auto; object-fit: contain;">
                    <div style="height: 20px; width: 1.5px; background-color: #CBD5E1;"></div>
                    <img src="{{ asset('images/genbi-polimdo.png') }}" alt="Logo GenBI Polimdo" style="height: 32px; width: 32px; object-fit: contain;">
                </div>
                <div class="d-flex flex-column ms-2 text-start">
                    <span class="fs-4 fw-extrabold text-white lh-1">SIPENA <span class="text-danger">GenBI</span></span>
                    <span class="text-white-50 mt-1" style="font-size: 0.65rem; font-weight: 500; letter-spacing: 0.2px;">Sistem Pelaporan Engagement Instagram</span>
                </div>
            </a>
        </div>
    </nav>

    <!-- Hero Section Container -->
    <section class="hero-section text-center">
        <div class="hero-bg-glow"></div>
        <div class="container pt-5 hero-content">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <!-- Badge Top -->
                    <span class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-20 px-3.5 py-2 rounded-pill fw-bold mb-3 shadow-sm d-inline-flex align-items-center gap-1.5" style="font-size: 0.85rem;">
                        <i class="bi bi-award-fill text-warning"></i> Generasi Baru Indonesia · Komisariat Polimdo
                    </span>

                    <!-- Main Title -->
                    <h1 class="display-4 fw-extrabold mb-3 text-white lh-sm">
                        Sistem Penilaian & Pelaporan <span class="text-danger">Engagement</span> Instagram
                    </h1>

                    <!-- Lead Paragraph -->
                    <p class="lead text-white-50 mb-4 mx-auto fs-6" style="max-width: 720px; line-height: 1.6;">
                        Platform digital resmi terpusat untuk memantau, memvalidasi, dan merekapitulasi kelengkapan laporan kegiatan sosialisasi Instagram seluruh anggota GenBI Polimdo.
                    </p>

                    <!-- Centered Auth Conditions & Buttons -->
                    @auth
                        <!-- If Logged In: Sleek User Profile Card Centered -->
                        <div class="user-welcome-card">
                            <div class="avatar-circle-hero">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </div>
                            <h5 class="fw-bold text-white mb-1">Selamat Datang Kembali, {{ Auth::user()->name }}!</h5>
                            <p class="text-white-50 small mb-3">
                                Status Akun: <span class="badge bg-danger rounded-pill px-2.5 py-1" style="font-size: 0.68rem; letter-spacing: 0.5px;">{{ strtoupper(Auth::user()->role ?? 'ANGGOTA') }}</span>
                            </p>
                            <div class="d-flex justify-content-center gap-2 flex-wrap">
                                <a href="{{ route('dashboard') }}" class="btn btn-genbi-red px-4 py-2.5 fs-6">
                                    <i class="bi bi-speedometer2 me-1.5"></i> Buka Dashboard
                                </a>
                                <a href="{{ route('profile.edit') }}" class="btn btn-glass-outline px-3 py-2.5 fs-6 rounded-3">
                                    <i class="bi bi-person-circle me-1.5"></i> Profil Saya
                                </a>
                            </div>
                        </div>
                    @else
                        <!-- If Guest: Centered Login & Register Action Buttons -->
                        <div class="d-flex justify-content-center align-items-center gap-3 flex-wrap mt-2">
                            <a href="{{ route('login') }}" class="btn btn-genbi-red fs-5 px-4 py-3 shadow-lg">
                                <i class="bi bi-shield-lock-fill me-2"></i> Masuk ke Sistem
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-glass-outline fs-5 px-4 py-3 rounded-4 fw-semibold">
                                <i class="bi bi-person-plus-fill me-2"></i> Daftar Anggota Baru
                            </a>
                        </div>
                    @endauth

                </div>
            </div>
        </div>
    </section>

    <!-- Stats Bar Banner -->
    <div class="container">
        <div class="stats-floating-bar">
            <div class="row text-center g-3">
                <div class="col-4 border-end">
                    <div class="stat-item-number"><i class="bi bi-people-fill text-primary me-1"></i> 100+</div>
                    <div class="stat-item-label">Anggota GenBI Polimdo</div>
                </div>
                <div class="col-4 border-end">
                    <div class="stat-item-number text-danger"><i class="bi bi-instagram me-1"></i> Target</div>
                    <div class="stat-item-label">Posting Harian</div>
                </div>
                <div class="col-4">
                    <div class="stat-item-number text-success"><i class="bi bi-check-circle-fill me-1"></i> 100%</div>
                    <div class="stat-item-label">Rekap Otomatis</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Features Section -->
    <section class="py-5 mt-2">
        <div class="container my-3">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card h-100 text-center">
                        <div class="icon-box-feature">
                            <i class="bi bi-bullseye"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Target Postingan Harian</h5>
                        <p class="text-muted small mb-0">Admin menetapkan jumlah postingan wajib berdasarkan akun Instagram resmi dan deadline pengunggahan.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card h-100 text-center">
                        <div class="icon-box-feature">
                            <i class="bi bi-cloud-arrow-up-fill text-primary"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Upload Bukti Screenshot</h5>
                        <p class="text-muted small mb-0">Anggota mengunggah bukti screenshot like, komen, dan share secara praktis dan terorganisir.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card h-100 text-center">
                        <div class="icon-box-feature">
                            <i class="bi bi-bar-chart-line-fill text-success"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Validasi & Rekap Otomatis</h5>
                        <p class="text-muted small mb-0">Admin memvalidasi bukti laporan, mengukur tingkat kelengkapan anggota, dan mengekspor rekapitulasi.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-top py-4 text-center text-muted small mt-4">
        <div class="container">
            &copy; {{ date('Y') }} <strong>SIPENA GenBI</strong> — Generasi Baru Indonesia Komisariat Polimdo. All rights reserved.
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
