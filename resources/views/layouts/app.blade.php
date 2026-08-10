<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'SIPENA GenBI') - Generasi Baru Indonesia</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --genbi-blue: #002B66;
            --genbi-blue-light: #F0F4FA;
            --genbi-red: #D90429;
            --genbi-red-soft: #FFEAEB;
            --genbi-bg: #F8FAFC;
            --genbi-card-bg: #FFFFFF;
            --genbi-text: #0F172A;
            --genbi-muted: #64748B;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--genbi-bg);
            color: var(--genbi-text);
            min-height: 100vh;
        }

        /* Navbar Premium GenBI */
        .navbar-genbi {
            background: linear-gradient(135deg, #0A192F 0%, var(--genbi-blue) 100%);
            box-shadow: 0 4px 20px rgba(0, 43, 102, 0.18);
            border-bottom: 3px solid var(--genbi-red);
        }

        /* Live Clock Pill Widget */
        .live-clock-widget {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.16);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            padding: 6px 14px;
            border-radius: 50px;
            color: #FFFFFF;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .live-clock-time {
            font-weight: 700;
            letter-spacing: 0.8px;
            font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
            color: #38BDF8;
        }

        /* Glassmorphic Action Pills */
        .nav-pill-item {
            background: rgba(255, 255, 255, 0.08) !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            backdrop-filter: blur(6px);
            border-radius: 50px !important;
            padding: 4px 10px !important;
            transition: all 0.25s ease;
        }
        .nav-pill-item:hover {
            background: rgba(255, 255, 255, 0.18) !important;
            border-color: rgba(255, 255, 255, 0.3) !important;
            transform: translateY(-1px);
        }

        /* Profile Avatar Pill */
        .user-profile-pill {
            background: rgba(255, 255, 255, 0.1) !important;
            border: 1px solid rgba(255, 255, 255, 0.18) !important;
            border-radius: 50px !important;
            padding: 3px 12px 3px 3px !important;
            transition: all 0.25s ease;
            box-shadow: 0 2px 10px rgba(0,0,0,0.12);
        }
        .user-profile-pill:hover {
            background: rgba(255, 255, 255, 0.2) !important;
            border-color: rgba(255, 255, 255, 0.35) !important;
            transform: translateY(-1px);
        }

        .avatar-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #FFFFFF 0%, #E2E8F0 100%);
            color: var(--genbi-blue);
            font-weight: 800;
            font-size: 0.78rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
            border: 2px solid rgba(255, 255, 255, 0.8);
        }

        .role-badge-pill {
            background: linear-gradient(135deg, #EF4444 0%, #B91C1C 100%);
            color: #FFFFFF;
            font-size: 0.62rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            padding: 2px 8px;
            border-radius: 50px;
            text-transform: uppercase;
            box-shadow: 0 1px 4px rgba(239, 68, 68, 0.4);
        }

        .navbar-genbi .navbar-brand {
            color: #FFFFFF;
            font-weight: 800;
            letter-spacing: -0.3px;
        }

        .navbar-logo-container {
            background: #FFFFFF;
            padding: 4px 10px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.12);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .navbar-logo-img {
            height: 32px;
            width: auto;
            object-fit: contain;
        }

        .navbar-logo-polimdo {
            height: 32px;
            width: 32px;
            object-fit: contain;
        }

        .logo-divider {
            height: 20px;
            width: 1px;
            background-color: #CBD5E1;
        }

        .navbar-genbi .nav-link {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .navbar-genbi .nav-link:hover,
        .navbar-genbi .nav-link.active {
            color: #FFFFFF;
            background: rgba(255, 255, 255, 0.15);
        }

        /* Sub-Navbar Navigation Bar */
        .subnav {
            background: #FFFFFF;
            border-bottom: 1px solid #E2E8F0;
            overflow-x: auto;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }

        .subnav .nav-link {
            color: var(--genbi-muted);
            font-weight: 600;
            font-size: 0.9rem;
            padding: 0.85rem 1.25rem;
            border-bottom: 3px solid transparent;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
        }

        .subnav .nav-link:hover {
            color: var(--genbi-blue);
            background-color: var(--genbi-blue-light);
        }

        .subnav .nav-link.active {
            color: var(--genbi-blue);
            border-bottom-color: var(--genbi-blue);
            background-color: var(--genbi-blue-light);
            font-weight: 700;
        }

        /* Premium Buttons */
        .btn-bi {
            background: linear-gradient(135deg, var(--genbi-blue) 0%, #001A4D 100%);
            color: #FFFFFF;
            font-weight: 600;
            border-radius: 10px;
            padding: 0.55rem 1.35rem;
            border: none;
            transition: all 0.2s ease;
            box-shadow: 0 3px 10px rgba(0, 43, 102, 0.2);
        }

        .btn-bi:hover {
            background: linear-gradient(135deg, #001A4D 0%, #0A192F 100%);
            color: #FFFFFF;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(0, 43, 102, 0.3);
        }

        .btn-bi-red {
            background: linear-gradient(135deg, var(--genbi-red) 0%, #B30000 100%);
            color: #FFFFFF;
            font-weight: 600;
            border-radius: 10px;
            border: none;
        }

        /* Premium Cards */
        .stat-card {
            background: #FFFFFF;
            border-radius: 16px;
            padding: 1.35rem 1.6rem;
            border: 1px solid #E2E8F0;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.03);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 43, 102, 0.08);
            border-color: #CBD5E1;
        }

        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            background-color: var(--genbi-blue-light);
            color: var(--genbi-blue);
        }

        .stat-card .stat-label {
            font-size: 0.825rem;
            font-weight: 700;
            color: var(--genbi-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-card .stat-number {
            font-size: 2.1rem;
            font-weight: 800;
            color: var(--genbi-text);
            line-height: 1.2;
            margin: 0.25rem 0;
        }

        .content-card {
            background: #FFFFFF;
            border-radius: 18px;
            padding: 1.75rem;
            border: 1px solid #E2E8F0;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.03);
            margin-bottom: 1.5rem;
        }

        /* Badges */
        .badge-soft-blue {
            background-color: var(--genbi-blue-light);
            color: var(--genbi-blue);
            font-weight: 600;
        }

        .badge-soft-success {
            background-color: #DCFCE7;
            color: #15803D;
            font-weight: 600;
        }

        .badge-soft-warning {
            background-color: #FEF9C3;
            color: #A16207;
            font-weight: 600;
        }

        .badge-soft-danger {
            background-color: var(--genbi-red-soft);
            color: var(--genbi-red);
            font-weight: 600;
        }

        .table > :not(caption) > * > * {
            padding: 0.9rem 1rem;
        }

        /* Mobile Navbar Responsive Enhancements */
        .mobile-clock-strip {
            background: rgba(0, 0, 0, 0.25);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: #FFFFFF;
            font-size: 0.76rem;
            padding: 5px 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        @media (max-width: 767.98px) {
            .navbar-logo-container {
                padding: 3px 6px;
                border-radius: 8px;
            }
            .navbar-logo-img {
                height: 24px;
            }
            .navbar-logo-polimdo {
                height: 24px;
                width: 24px;
            }
            .logo-divider {
                height: 16px;
            }
            .navbar-brand-title {
                font-size: 1.15rem !important;
            }
            .navbar-brand-subtitle {
                display: none !important;
            }
            .navbar-toggler-custom {
                background: rgba(255, 255, 255, 0.15) !important;
                border: 1px solid rgba(255, 255, 255, 0.25) !important;
                border-radius: 10px !important;
                padding: 4px 10px !important;
                color: #FFFFFF !important;
            }
            .content-card {
                padding: 1.1rem;
                border-radius: 14px;
            }
            .subnav .nav-link {
                padding: 0.65rem 0.9rem;
                font-size: 0.825rem;
            }
            .nav-pill-item {
                padding: 3px 8px !important;
            }
            .notif-bell-icon {
                font-size: 0.95rem !important;
            }
            .user-avatar-link {
                padding: 2px !important;
                border-radius: 50% !important;
            }
            .avatar-circle {
                width: 28px !important;
                height: 28px !important;
                font-size: 0.72rem !important;
                border-width: 1.5px !important;
            }
        }

        .notif-dropdown-menu {
            min-width: 320px;
            max-width: 360px;
        }

        @media (max-width: 575.98px) {
            .stat-card {
                padding: 1rem;
                border-radius: 12px;
            }
            .stat-card .stat-number {
                font-size: 1.5rem;
            }
            .stat-card .stat-icon {
                width: 40px;
                height: 40px;
                font-size: 1.1rem;
            }
            .notif-dropdown-menu {
                position: fixed !important;
                top: 50px !important;
                left: 12px !important;
                right: 12px !important;
                width: calc(100vw - 24px) !important;
                min-width: 0 !important;
                max-width: none !important;
                margin: 0 auto !important;
                transform: none !important;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3) !important;
                z-index: 1060 !important;
            }
            .btn-responsive-mobile {
                width: 100%;
            }
        }

        .user-avatar-link {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.25);
            transition: all 0.2s ease;
            padding: 4px 14px 4px 5px !important;
            gap: 10px !important;
        }
        .user-avatar-link::after {
            display: none !important;
        }
        .user-avatar-link:hover {
            background: rgba(255, 255, 255, 0.22);
            border-color: rgba(255, 255, 255, 0.4);
            transform: translateY(-1px);
        }

        /* Print Media Styles */
        @media print {
            .navbar-genbi, .subnav, .no-print, header, footer {
                display: none !important;
            }
            body {
                background-color: #FFFFFF !important;
                color: #000000 !important;
            }
            .content-card {
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
                margin-bottom: 0 !important;
            }
            .page-break {
                page-break-after: always;
            }
        }
    </style>
</head>
<body>

    <!-- Header Navbar -->
    <nav class="navbar navbar-genbi p-0 no-print">
        <div class="py-2 px-3 px-lg-4 w-100 d-flex align-items-center justify-content-between">
            <a class="navbar-brand d-flex align-items-center gap-2 m-0" href="{{ route('dashboard') }}">
                <div class="navbar-logo-container">
                    <img src="{{ asset('images/genbi-logo.png') }}" alt="Logo GenBI" class="navbar-logo-img">
                    <div class="logo-divider"></div>
                    <img src="{{ asset('images/genbi-polimdo.png') }}" alt="Logo GenBI Polimdo" class="navbar-logo-polimdo">
                </div>
                <div class="d-flex flex-column ms-1">
                    <span class="fs-4 fw-extrabold text-white lh-1 navbar-brand-title">SIPENA <span class="text-danger">GenBI</span></span>
                    <span class="text-white-50 mt-1 navbar-brand-subtitle" style="font-size: 0.65rem; font-weight: 500; letter-spacing: 0.2px;">Sistem Pelaporan Engagement Instagram</span>
                </div>
            </a>

            <!-- Right Controls: Live Clock + Notification Bell + Clickable Profile Avatar -->
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <!-- Live Running Clock Widget (Desktop & Tablet) -->
                <div class="live-clock-widget d-none d-md-flex">
                    <i class="bi bi-clock-history text-info fs-6"></i>
                    <span id="liveClockDate" class="text-white-50 small fw-medium"></span>
                    <span class="text-white-50">•</span>
                    <span id="liveClockTime" class="live-clock-time fs-6">--:--:--</span>
                </div>

                @php
                    $unreadCount = \App\Models\Notifikasi::where('is_read', false)
                        ->where(function($q) {
                            $q->whereNull('user_id')->orWhere('user_id', auth()->id());
                        })->count();
                    $latestNotifs = \App\Models\Notifikasi::where(function($q) {
                            $q->whereNull('user_id')->orWhere('user_id', auth()->id());
                        })->latest()->take(5)->get();
                @endphp

                <!-- Notification Bell Button Dropdown Popup -->
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle text-white nav-pill-item d-inline-flex align-items-center position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" title="Notifikasi" style="gap: 6px;">
                        <i class="bi bi-bell-fill text-warning notif-bell-icon" style="font-size: 1.05rem;"></i>
                        <span class="fw-semibold small d-none d-lg-inline">Notifikasi</span>
                        @if($unreadCount > 0)
                            <span class="badge rounded-pill bg-danger border border-light shadow-sm" style="font-size: 0.6rem; padding: 2px 6px; margin-left: 2px;">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </a>

                    <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 mt-2 p-0 overflow-hidden notif-dropdown-menu">
                        <div class="d-flex justify-content-between align-items-center p-3 bg-light border-bottom">
                            <div class="d-flex align-items-center gap-2">
                                <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.9rem;"><i class="bi bi-bell-fill text-primary me-1"></i> Notifikasi</h6>
                                @if($unreadCount > 0)
                                    <span class="badge bg-danger rounded-pill fs-7">{{ $unreadCount }} Baru</span>
                                @endif
                            </div>
                            @if($unreadCount > 0)
                                <form action="{{ route('notifikasi.read-all') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="btn btn-link p-0 text-decoration-none text-primary fw-semibold" style="font-size: 0.75rem;">
                                        <i class="bi bi-check-all me-0.5"></i> Baca Semua
                                    </button>
                                </form>
                            @endif
                        </div>

                        <div class="notification-popup-list" style="max-height: 320px; overflow-y: auto;">
                            @forelse($latestNotifs as $notif)
                                <a href="{{ route('notifikasi.open', $notif->id) }}" class="dropdown-item p-3 border-bottom text-decoration-none {{ $notif->is_read ? 'bg-white opacity-75' : 'bg-light-subtle' }}" style="display: flex; align-items: flex-start; gap: 10px;">
                                    <div style="flex-shrink: 0; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; margin-top: 2px; font-size: 1.1rem;">
                                        @if($notif->tipe === 'target')
                                            <i class="bi bi-bullseye text-primary"></i>
                                        @elseif($notif->tipe === 'warning')
                                            <i class="bi bi-exclamation-triangle-fill text-warning"></i>
                                        @elseif($notif->tipe === 'ditolak')
                                            <i class="bi bi-x-circle-fill text-danger"></i>
                                        @else
                                            <i class="bi bi-check-circle-fill text-success"></i>
                                        @endif
                                    </div>
                                    <div style="flex: 1; min-width: 0; overflow: hidden;">
                                        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 6px; margin-bottom: 3px;">
                                            <span class="fw-bold text-dark" style="font-size: 0.825rem; line-height: 1.3; word-break: break-word;">{{ $notif->judul }}</span>
                                            @if(!$notif->is_read)
                                                <span class="badge bg-primary rounded-pill flex-shrink-0" style="font-size: 0.6rem;">BARU</span>
                                            @endif
                                        </div>
                                        <p class="text-muted mb-1" style="font-size: 0.78rem; line-height: 1.3; word-break: break-word; margin: 0 0 4px 0;">
                                            {{ $notif->pesan }}
                                        </p>
                                        <small class="text-muted" style="font-size: 0.7rem; display: block;">
                                            <i class="bi bi-clock me-1"></i> {{ $notif->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </a>
                            @empty
                                <div class="p-4 text-center text-muted small">
                                    <i class="bi bi-bell-slash fs-4 d-block mb-1 text-secondary"></i>
                                    Belum ada notifikasi.
                                </div>
                            @endforelse
                        </div>

                        <a href="{{ route('notifikasi.index') }}" class="d-block text-center py-2.5 bg-light text-primary fw-bold small text-decoration-none border-top">
                            Lihat Semua Notifikasi <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>

                <!-- Clickable Profile Avatar Dropdown Menu (Includes Profile & Logout) -->
                <div class="dropdown">
                    <a href="#" class="user-avatar-link dropdown-toggle text-decoration-none d-flex align-items-center rounded-pill" role="button" data-bs-toggle="dropdown" aria-expanded="false" title="Menu Pengguna">
                        <div class="avatar-circle">
                            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}
                        </div>
                        <div class="text-start d-none d-md-block pe-1">
                            <div class="fw-bold lh-sm text-white" style="font-size: 0.85rem; margin-bottom: 3px;">{{ Auth::user()->name ?? 'Pengguna' }}</div>
                            <div class="d-flex align-items-center">
                                <span class="role-badge-pill">{{ strtoupper(Auth::user()->role ?? 'ANGGOTA') }}</span>
                            </div>
                        </div>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 mt-2 p-2" style="min-width: 230px;">
                        <li class="px-3 py-2 border-bottom mb-1 bg-light rounded-3">
                            <div class="fw-bold text-dark lh-sm" style="font-size: 0.875rem;">{{ Auth::user()->name }}</div>
                            <small class="text-muted d-block" style="font-size: 0.75rem;">{{ Auth::user()->email }}</small>
                            <span class="role-badge-pill mt-1.5 d-inline-block">{{ strtoupper(Auth::user()->role ?? 'ANGGOTA') }}</span>
                        </li>
                        <li>
                            <a class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-2 fw-semibold" href="{{ route('profile.edit') }}">
                                <i class="bi bi-person-circle text-primary fs-5"></i>
                                <span>Profil Saya</span>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button type="submit" class="dropdown-item rounded-3 py-2 text-danger d-flex align-items-center gap-2 fw-semibold w-100">
                                    <i class="bi bi-box-arrow-right fs-5 text-danger"></i>
                                    <span>Keluar (Logout)</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Always-Visible Live Clock Bar for Mobile Screens -->
        <div class="mobile-clock-strip d-md-none w-100">
            <i class="bi bi-clock-history text-info"></i>
            <span id="liveClockDateMobile" class="fw-medium text-white-50"></span>
            <span>•</span>
            <span id="liveClockTimeMobile" class="live-clock-time">--:--:--</span>
        </div>
    </nav>

    <!-- Sub Navigation Bar for Module Links -->
    <div class="subnav shadow-sm mb-4 no-print">
        <div class="container-fluid px-lg-4 d-flex">
            @if(auth()->user()->isAdmin())
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-grid-fill me-1"></i> Dashboard
                </a>
                <a class="nav-link {{ request()->routeIs('admin.akun-instagram.*') ? 'active' : '' }}" href="{{ route('admin.akun-instagram.index') }}">
                    <i class="bi bi-instagram me-1"></i> Akun Instagram
                </a>
                <a class="nav-link {{ request()->routeIs('admin.anggota.*') ? 'active' : '' }}" href="{{ route('admin.anggota.index') }}">
                    <i class="bi bi-people-fill me-1"></i> Data Anggota
                </a>
                <a class="nav-link {{ request()->routeIs('admin.target-harian.*') ? 'active' : '' }}" href="{{ route('admin.target-harian.index') }}">
                    <i class="bi bi-bullseye me-1"></i> Target Harian
                </a>
                <a class="nav-link {{ request()->routeIs('admin.validasi.*') ? 'active' : '' }}" href="{{ route('admin.validasi.index') }}">
                    <i class="bi bi-patch-check-fill me-1"></i> Validasi Laporan
                </a>
                <a class="nav-link {{ request()->routeIs('admin.rekap.*') ? 'active' : '' }}" href="{{ route('admin.rekap.index') }}">
                    <i class="bi bi-bar-chart-fill me-1"></i> Rekap
                </a>
                <a class="nav-link {{ request()->routeIs('admin.export.*') || request()->routeIs('admin.preview-laporan') || request()->routeIs('admin.preview-rekap') ? 'active' : '' }}" href="{{ route('admin.export.index') }}">
                    <i class="bi bi-file-earmark-arrow-down-fill me-1"></i> Export & Preview
                </a>
            @else
                <a class="nav-link {{ request()->routeIs('user.laporan.create') ? 'active' : '' }}" href="{{ route('user.laporan.create') }}">
                    <i class="bi bi-plus-circle-fill me-1"></i> + Upload Laporan Baru
                </a>
                <a class="nav-link {{ request()->routeIs('user.preview-laporan') ? 'active' : '' }}" href="{{ route('user.preview-laporan') }}">
                    <i class="bi bi-file-earmark-pdf-fill me-1"></i> Preview & Cetak Laporan
                </a>
                <a class="nav-link {{ request()->routeIs('user.riwayat.*') ? 'active' : '' }}" href="{{ route('user.riwayat.index') }}">
                    <i class="bi bi-journal-text me-1"></i> Daftar Laporan Saya
                </a>
                <a class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
                    <i class="bi bi-person-circle me-1"></i> Profil Saya
                </a>
                <a class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}" href="{{ route('user.dashboard') }}">
                    <i class="bi bi-grid-fill me-1"></i> Ringkasan
                </a>
            @endif
        </div>
    </div>

    <!-- Main Content Container -->
    <main class="container-fluid px-lg-4 pb-5">
        
        <!-- Page Title Header -->
        <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2 no-print">
            <div>
                <h4 class="fw-bold mb-1" style="color: var(--genbi-blue);">@yield('title')</h4>
                <p class="text-muted mb-0 small">@yield('subtitle')</p>
            </div>
            @yield('header_actions')
        </div>

        <!-- Alert Notifications -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4 no-print" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4 no-print" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4 no-print" role="alert">
                <strong><i class="bi bi-x-circle-fill me-1"></i> Terdapat kesalahan input:</strong>
                <ul class="mb-0 mt-1 ps-3 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Page Dynamic Content -->
        @yield('content')

    </main>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Live Running Clock Script -->
    <script>
        function updateLiveClock() {
            const now = new Date();
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];

            const dayName = days[now.getDay()];
            const dateNum = String(now.getDate()).padStart(2, '0');
            const monthName = months[now.getMonth()];
            const year = now.getFullYear();

            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');

            const dateStr = `${dayName}, ${dateNum} ${monthName} ${year}`;
            const timeStr = `${hours}:${minutes}:${seconds} WITA`;

            const dateEl = document.getElementById('liveClockDate');
            const timeEl = document.getElementById('liveClockTime');
            const dateMobileEl = document.getElementById('liveClockDateMobile');
            const timeMobileEl = document.getElementById('liveClockTimeMobile');

            if (dateEl) dateEl.textContent = dateStr;
            if (timeEl) timeEl.textContent = timeStr;
            if (dateMobileEl) dateMobileEl.textContent = dateStr;
            if (timeMobileEl) timeMobileEl.textContent = timeStr;
        }

        document.addEventListener('DOMContentLoaded', function() {
            updateLiveClock();
            setInterval(updateLiveClock, 1000);
        });
    </script>

    @yield('scripts')
</body>
</html>
