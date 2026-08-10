@extends('layouts.app')

@section('title', 'Profil & Data Diri Saya')
@section('subtitle', 'Kelola informasi identitas anggota dan kata sandi akun Anda')

@section('content')
<style>
    :root {
        --bi-navy: #002B66;
        --bi-navy-light: #003A8C;
        --bi-accent: #D90429;
        --bi-gold: #C9A84C;
    }

    /* Hero Profile Banner */
    .profile-hero {
        background: linear-gradient(135deg, #002B66 0%, #0A3D91 60%, #1a56d6 100%);
        border-radius: 20px;
        padding: 2rem 2.5rem;
        color: white;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0, 43, 102, 0.25);
    }

    .profile-hero::before {
        content: '';
        position: absolute;
        top: -40px;
        right: -40px;
        width: 200px;
        height: 200px;
        background: rgba(255,255,255,0.04);
        border-radius: 50%;
    }

    .profile-hero::after {
        content: '';
        position: absolute;
        bottom: -60px;
        right: 60px;
        width: 140px;
        height: 140px;
        background: rgba(255,255,255,0.035);
        border-radius: 50%;
    }

    .profile-avatar {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(255,255,255,0.25), rgba(255,255,255,0.1));
        border: 3px solid rgba(255,255,255,0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.85rem;
        font-weight: 800;
        color: #fff;
        letter-spacing: -1px;
        backdrop-filter: blur(4px);
        flex-shrink: 0;
    }

    .profile-hero-name {
        font-size: 1.35rem;
        font-weight: 800;
        margin-bottom: 0.2rem;
        letter-spacing: 0.2px;
    }

    .profile-hero-meta {
        font-size: 0.85rem;
        opacity: 0.75;
        margin-bottom: 0;
    }

    .profile-hero-badge {
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.25);
        color: #fff;
        font-size: 0.78rem;
        font-weight: 700;
        padding: 0.3rem 0.85rem;
        border-radius: 50px;
        backdrop-filter: blur(4px);
        letter-spacing: 0.5px;
        display: inline-block;
        margin-top: 0.5rem;
    }

    /* Form Cards */
    .profile-card {
        background: #FFFFFF;
        border-radius: 18px;
        border: 1px solid #E2E8F0;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        overflow: hidden;
        margin-bottom: 1.5rem;
        transition: box-shadow 0.2s ease;
    }

    .profile-card:hover {
        box-shadow: 0 6px 24px rgba(0, 43, 102, 0.08);
    }

    .profile-card-header {
        padding: 1.25rem 1.75rem 1rem;
        border-bottom: 1px solid #F1F5F9;
        display: flex;
        align-items: center;
        gap: 0.85rem;
    }

    .profile-card-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        flex-shrink: 0;
    }

    .icon-blue {
        background: linear-gradient(135deg, #EEF2FF, #DBEAFE);
        color: var(--bi-navy);
    }

    .icon-amber {
        background: linear-gradient(135deg, #FFFBEB, #FEF3C7);
        color: #92400E;
    }

    .profile-card-body {
        padding: 1.5rem 1.75rem;
    }

    /* Premium Form Controls */
    .form-label-premium {
        font-weight: 600;
        font-size: 0.85rem;
        color: #374151;
        margin-bottom: 0.45rem;
        letter-spacing: 0.2px;
    }

    .form-control-premium {
        border: 1.5px solid #E2E8F0;
        border-radius: 10px;
        padding: 0.65rem 1rem;
        font-size: 0.95rem;
        color: #1E293B;
        background-color: #FAFBFC;
        transition: all 0.2s ease;
        box-shadow: none;
    }

    .form-control-premium:focus {
        border-color: var(--bi-navy);
        background-color: #FFFFFF;
        box-shadow: 0 0 0 3px rgba(0, 43, 102, 0.08);
        outline: none;
    }

    .form-control-premium.is-invalid {
        border-color: #EF4444;
        background-color: #FFF5F5;
    }

    .form-control-premium.is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }

    .input-icon-group {
        position: relative;
    }

    .input-icon-group .input-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94A3B8;
        font-size: 0.9rem;
        pointer-events: none;
    }

    .input-icon-group .form-control-premium {
        padding-left: 2.5rem;
    }

    /* Buttons */
    .btn-save {
        background: linear-gradient(135deg, var(--bi-navy), #1a4db8);
        border: none;
        color: #fff;
        font-weight: 700;
        font-size: 0.9rem;
        padding: 0.65rem 1.75rem;
        border-radius: 12px;
        letter-spacing: 0.3px;
        transition: all 0.25s ease;
        box-shadow: 0 4px 14px rgba(0, 43, 102, 0.25);
    }

    .btn-save:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(0, 43, 102, 0.35);
        color: #fff;
        background: linear-gradient(135deg, #003A8C, #1a56d6);
    }

    .btn-save:active {
        transform: translateY(0);
    }

    .btn-password {
        background: linear-gradient(135deg, #F59E0B, #D97706);
        border: none;
        color: #fff;
        font-weight: 700;
        font-size: 0.9rem;
        padding: 0.65rem 1.75rem;
        border-radius: 12px;
        letter-spacing: 0.3px;
        transition: all 0.25s ease;
        box-shadow: 0 4px 14px rgba(245, 158, 11, 0.3);
    }

    .btn-password:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
        color: #fff;
        background: linear-gradient(135deg, #D97706, #B45309);
    }

    .btn-password:active {
        transform: translateY(0);
    }

    /* Alert styles */
    .alert-premium-success {
        background: linear-gradient(135deg, #ECFDF5, #D1FAE5);
        border: 1px solid #6EE7B7;
        border-left: 4px solid #10B981;
        border-radius: 12px;
        color: #065F46;
        padding: 0.9rem 1.25rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.1);
    }

    /* Divider */
    .field-divider {
        border: none;
        border-top: 1px solid #F1F5F9;
        margin: 1.25rem 0;
    }

    /* Info hint text */
    .input-hint {
        font-size: 0.78rem;
        color: #94A3B8;
        margin-top: 0.35rem;
    }

    @media (max-width: 767.98px) {
        .profile-hero {
            padding: 1.5rem;
        }
        .profile-card-body {
            padding: 1.25rem;
        }
        .profile-card-header {
            padding: 1rem 1.25rem 0.75rem;
        }
    }
</style>

<div class="row justify-content-center">
    <div class="col-xl-7 col-lg-8 col-md-10">

        <!-- Success Notifications -->
        @if (session('status') === 'profile-updated')
            <div class="alert-premium-success d-flex align-items-center gap-2 fade show">
                <i class="bi bi-check-circle-fill fs-5 text-success"></i>
                <div>
                    <strong class="d-block">Berhasil Disimpan</strong>
                    <span class="small">Data diri profil Anda berhasil diperbarui.</span>
                </div>
            </div>
        @endif

        @if (session('status') === 'password-updated')
            <div class="alert-premium-success d-flex align-items-center gap-2 fade show">
                <i class="bi bi-shield-check-fill fs-5 text-success"></i>
                <div>
                    <strong class="d-block">Kata Sandi Diperbarui</strong>
                    <span class="small">Kata sandi akun Anda berhasil diperbarui.</span>
                </div>
            </div>
        @endif

        <!-- Hero Profile Banner -->
        <div class="profile-hero">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 position-relative" style="z-index: 2;">
                <div class="d-flex align-items-center gap-3">
                    <div class="profile-avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="profile-hero-name">{{ auth()->user()->name }}</div>
                        <div class="profile-hero-meta">
                            <i class="bi bi-envelope-fill me-1 opacity-75"></i>{{ auth()->user()->email }}
                        </div>
                        @if(auth()->user()->nim)
                            <div class="profile-hero-meta">
                                <i class="bi bi-person-badge-fill me-1 opacity-75"></i>NIM: {{ auth()->user()->nim }}
                            </div>
                        @endif
                        <span class="profile-hero-badge">
                            <i class="bi bi-mortarboard-fill me-1"></i> Politeknik Negeri Manado · GenBI Polimdo
                        </span>
                    </div>
                </div>
                <div>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm rounded-pill px-3 fw-bold">
                            <i class="bi bi-box-arrow-right me-1"></i> Keluar (Logout)
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Card 1: Update Informasi Data Diri -->
        <div class="profile-card">
            <div class="profile-card-header">
                <div class="profile-card-icon icon-blue">
                    <i class="bi bi-person-vcard-fill"></i>
                </div>
                <div>
                    <div class="fw-bold" style="color: var(--bi-navy); font-size: 0.97rem;">Informasi Data Diri</div>
                    <div class="text-muted" style="font-size: 0.8rem;">Perbarui nama, NIM, dan email yang tertera pada laporan</div>
                </div>
            </div>
            <div class="profile-card-body">
                <form method="post" action="{{ route('profile.update') }}">
                    @csrf
                    @method('patch')

                    <div class="mb-3">
                        <label for="name" class="form-label-premium">Nama Lengkap</label>
                        <div class="input-icon-group">
                            <i class="bi bi-person-fill input-icon"></i>
                            <input type="text" id="name" name="name"
                                class="form-control form-control-premium @error('name') is-invalid @enderror"
                                value="{{ old('name', $user->name) }}" required>
                        </div>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="input-hint">Nama ini akan tampil pada sampul cover laporan</div>
                    </div>

                    <div class="mb-3">
                        <label for="nim" class="form-label-premium">NIM (Nomor Induk Mahasiswa)</label>
                        <div class="input-icon-group">
                            <i class="bi bi-person-badge-fill input-icon"></i>
                            <input type="text" id="nim" name="nim"
                                class="form-control form-control-premium @error('nim') is-invalid @enderror"
                                value="{{ old('nim', $user->nim) }}" placeholder="Contoh: 21021101">
                        </div>
                        @error('nim')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label-premium">Alamat Email</label>
                        <div class="input-icon-group">
                            <i class="bi bi-envelope-fill input-icon"></i>
                            <input type="email" id="email" name="email"
                                class="form-control form-control-premium @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email) }}" required>
                        </div>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="field-divider">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="text-muted small"><i class="bi bi-info-circle me-1"></i>Perubahan akan langsung tersimpan</span>
                        <button type="submit" class="btn btn-save">
                            <i class="bi bi-floppy-fill me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Card 2: Update Password -->
        <div class="profile-card">
            <div class="profile-card-header">
                <div class="profile-card-icon icon-amber">
                    <i class="bi bi-key-fill"></i>
                </div>
                <div>
                    <div class="fw-bold" style="color: #92400E; font-size: 0.97rem;">Ubah Kata Sandi</div>
                    <div class="text-muted" style="font-size: 0.8rem;">Pastikan akun Anda dilindungi kata sandi yang kuat</div>
                </div>
            </div>
            <div class="profile-card-body">
                <form method="post" action="{{ route('password.update') }}">
                    @csrf
                    @method('put')

                    <div class="mb-3">
                        <label for="update_password_current_password" class="form-label-premium">Password Saat Ini</label>
                        <div class="input-icon-group">
                            <i class="bi bi-lock-fill input-icon"></i>
                            <input type="password" id="update_password_current_password" name="current_password"
                                class="form-control form-control-premium @error('current_password', 'updatePassword') is-invalid @enderror"
                                autocomplete="current-password" placeholder="Masukkan password lama">
                        </div>
                        @error('current_password', 'updatePassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="update_password_password" class="form-label-premium">Password Baru</label>
                        <div class="input-icon-group">
                            <i class="bi bi-shield-lock-fill input-icon"></i>
                            <input type="password" id="update_password_password" name="password"
                                class="form-control form-control-premium @error('password', 'updatePassword') is-invalid @enderror"
                                autocomplete="new-password" placeholder="Minimal 8 karakter">
                        </div>
                        @error('password', 'updatePassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="update_password_password_confirmation" class="form-label-premium">Konfirmasi Password Baru</label>
                        <div class="input-icon-group">
                            <i class="bi bi-shield-check-fill input-icon"></i>
                            <input type="password" id="update_password_password_confirmation" name="password_confirmation"
                                class="form-control form-control-premium @error('password_confirmation', 'updatePassword') is-invalid @enderror"
                                autocomplete="new-password" placeholder="Ulangi password baru">
                        </div>
                        @error('password_confirmation', 'updatePassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="field-divider">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="text-muted small"><i class="bi bi-shield-fill-check me-1"></i>Gunakan kombinasi huruf, angka & simbol</span>
                        <button type="submit" class="btn btn-password">
                            <i class="bi bi-shield-lock-fill me-1"></i> Perbarui Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
