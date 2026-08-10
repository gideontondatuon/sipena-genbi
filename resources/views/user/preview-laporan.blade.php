@extends('layouts.app')

@section('title', 'Preview & Cetak Laporan')
@section('subtitle', 'Format cetak Landscape A4 Presisi: Halaman 1 Cover Sampul, 1 postingan per 1 Halaman')

@section('header_actions')
<div class="d-flex gap-2 flex-column flex-sm-row no-print w-100 w-sm-auto">
    <a href="{{ route('user.laporan.create') }}" class="btn btn-outline-primary rounded-4 w-100 w-sm-auto"><i class="bi bi-plus-circle-fill me-1"></i> Upload Postingan Baru</a>
    <button onclick="window.print()" class="btn btn-bi w-100 w-sm-auto"><i class="bi bi-printer-fill me-1"></i> Cetak Dokumen / Simpan PDF</button>
</div>
@endsection

@section('content')
<style>
    :root {
        --bi-navy: #002B66;
        --bi-blue-soft: #1E40AF;
        --bi-border: #CBD5E1;
        --bi-bg-subtle: #F8FAFC;
    }

    .report-landscape-wrapper {
        background-color: #FFFFFF;
        width: 100%;
        max-width: 1120px;
        margin: 0 auto;
        padding: 1.5rem;
    }

    @media (max-width: 767.98px) {
        .report-landscape-wrapper {
            padding: 0.5rem;
            overflow-x: auto;
        }
        .cover-page {
            padding: 1.5rem 1rem !important;
            min-height: auto !important;
        }
        .cover-title {
            font-size: 1.75rem !important;
        }
        .cover-logo-img {
            height: 50px !important;
        }
    }

    /* HALAMAN 1: COVER SAMPUL UTAMA CENTERED (FULL PAGE) */
    .cover-page {
        background-color: #FFFFFF;
        border: 3px solid var(--bi-navy);
        border-radius: 20px;
        padding: 3rem 2.5rem;
        margin-bottom: 2.5rem;
        text-align: center;
        box-shadow: 0 8px 25px rgba(0, 43, 102, 0.08);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: center;
        min-height: 620px;
    }

    .cover-logo-img {
        height: 84px;
        width: auto;
        object-fit: contain;
    }

    .cover-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 800;
        color: var(--bi-navy);
        letter-spacing: 1.5px;
        text-transform: uppercase;
        font-size: 3.1rem;
        margin-bottom: 0.65rem;
        line-height: 1.15;
    }

    .cover-subtitle-text {
        font-weight: 700;
        font-size: 1.05rem;
        color: var(--bi-navy);
        margin-top: 0.5rem;
        margin-bottom: 1rem;
        padding: 0.5rem 2rem;
        background-color: var(--bi-bg-subtle);
        border: 1.5px solid var(--bi-border);
        border-radius: 50px;
        display: inline-block;
    }

    .member-info-box {
        background: #FFFFFF;
        border-radius: 14px;
        border: 1.5px solid var(--bi-border);
        border-top: 5px solid var(--bi-navy);
        padding: 1.5rem 2.2rem;
        width: 100%;
        max-width: 780px;
        margin: 1.25rem auto;
        text-align: left;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    }

    .cover-footer-note {
        font-size: 0.95rem;
        font-weight: 600;
        color: #475569;
        margin-top: 1.5rem;
        border-top: 1px solid var(--bi-border);
        padding-top: 0.75rem;
        width: 100%;
    }

    /* HALAMAN POSTINGAN */
    .post-page-card {
        background-color: #FFFFFF;
        border: 1px solid var(--bi-border);
        border-radius: 14px;
        padding: 1.25rem;
        margin-bottom: 2.5rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    }

    .account-badge-header {
        background-color: var(--bi-navy) !important;
        color: #FFFFFF;
        padding: 0.75rem 1.25rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .post-meta-header {
        border-bottom: 1px solid #E2E8F0;
        padding-bottom: 0.5rem;
    }

    /* 3 Side-by-Side Screenshots Banner */
    .screenshot-row {
        display: flex;
        flex-direction: row;
        align-items: stretch;
        justify-content: center;
        gap: 8px;
        background-color: #F8FAFC;
        padding: 8px;
        border-radius: 10px;
        border: 1px solid var(--bi-border);
        overflow: hidden;
        margin-top: 0.5rem;
    }

    @media (max-width: 575.98px) {
        .report-preview-wrapper {
            padding: 1rem !important;
            border-radius: 8px !important;
        }
        .screenshot-row {
            flex-direction: column !important;
            gap: 12px !important;
        }
        .screenshot-col {
            width: 100% !important;
        }
        .cover-title {
            font-size: 1.3rem !important;
        }
    }

    .screenshot-col {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-between;
        background-color: #FFFFFF;
        border-radius: 6px;
        border: 1px solid #E2E8F0;
        overflow: hidden;
    }

    .screenshot-caption {
        width: 100%;
        background-color: #F1F5F9;
        color: var(--bi-navy);
        font-weight: 700;
        font-size: 0.8rem;
        padding: 0.4rem 0.5rem;
        text-align: center;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        border-bottom: 1px solid #E2E8F0;
    }

    .screenshot-img {
        width: 100%;
        height: auto;
        max-height: 420px;
        object-fit: contain;
        display: block;
    }

    .screenshot-placeholder {
        padding: 3.5rem 1rem;
        color: #94A3B8;
        font-size: 0.8rem;
        text-align: center;
    }

    /* Mobile Screen Adjustments */
    @media (max-width: 767.98px) {
        .report-landscape-wrapper {
            padding: 0.25rem;
        }
        .cover-page {
            padding: 1.25rem 0.85rem;
        }
        .cover-logo-img {
            height: 48px;
        }
        .cover-title {
            font-size: 1.2rem !important;
            line-height: 1.3;
        }
        .cover-subtitle-text {
            font-size: 0.78rem;
            padding: 0.25rem 0.85rem;
        }
        .member-info-box {
            padding: 0.85rem 1rem;
            margin: 0.5rem auto;
        }
        .post-page-card {
            padding: 0.85rem;
        }
        .account-badge-header {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 8px;
            padding: 0.65rem 0.85rem;
        }
        .screenshot-row {
            flex-direction: column !important;
            gap: 10px !important;
        }
        .screenshot-col {
            width: 100% !important;
        }
        .screenshot-img {
            max-height: 300px;
        }
    }

    /* Landscape A4 Print Setup */
    @media print {
        @page {
            size: A4 landscape;
            margin: 6mm 8mm;
        }

        body {
            background: #FFFFFF !important;
            padding: 0 !important;
        }

        .report-landscape-wrapper {
            max-width: 100% !important;
            padding: 0 !important;
            box-shadow: none !important;
            border: none !important;
        }

        .cover-page {
            display: flex !important;
            flex-direction: column !important;
            justify-content: space-between !important;
            align-items: center !important;
            height: 192mm !important;
            min-height: 192mm !important;
            border: 3px solid #002B66 !important;
            margin: 0 !important;
            padding: 2.2rem 2.5rem !important;
            page-break-after: always !important;
            break-after: page !important;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
            box-sizing: border-box !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .cover-title {
            font-size: 34pt !important;
            font-weight: 800 !important;
            letter-spacing: 1.5px !important;
            line-height: 1.15 !important;
            color: #002B66 !important;
            margin-bottom: 0.65rem !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .cover-subtitle-text {
            font-size: 14pt !important;
            font-weight: 700 !important;
            background-color: #F8FAFC !important;
            border: 1.5px solid #CBD5E1 !important;
            color: #002B66 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .post-page-card {
            height: 185mm !important;
            max-height: 185mm !important;
            page-break-after: always !important;
            break-after: page !important;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
            margin: 0 !important;
            border: 1px solid #CBD5E1 !important;
            padding: 0.75rem !important;
            box-shadow: none !important;
            overflow: hidden !important;
            box-sizing: border-box !important;
        }

        .account-badge-header {
            background-color: #002B66 !important;
            color: #FFFFFF !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .screenshot-row {
            flex-direction: row !important;
            background-color: #F8FAFC !important;
            border: 1px solid #CBD5E1 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .screenshot-caption {
            background-color: #F1F5F9 !important;
            color: #002B66 !important;
            border-bottom: 1px solid #CBD5E1 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .screenshot-img {
            max-height: 360px !important;
        }

        .no-print {
            display: none !important;
        }
    }
</style>

<!-- Filter Custom Date Range Toolbar (No-Print) -->
<div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white no-print max-w-1120 mx-auto" style="max-width: 1120px; border-left: 4px solid var(--bi-navy) !important;">
    <form method="GET" action="{{ route('user.preview-laporan') }}" class="row g-3 align-items-center">
        <div class="col-md-5">
            <label class="form-label fw-bold mb-1 small text-muted"><i class="bi bi-calendar-event me-1 text-primary"></i> Dari Tanggal</label>
            <input type="date" name="tanggal_mulai" class="form-control" value="{{ $tanggalMulai }}">
        </div>
        <div class="col-md-5">
            <label class="form-label fw-bold mb-1 small text-muted"><i class="bi bi-calendar-check me-1 text-primary"></i> Sampai Tanggal</label>
            <input type="date" name="tanggal_selesai" class="form-control" value="{{ $tanggalSelesai }}">
        </div>
        <div class="col-md-2 text-md-end pt-md-4">
            <button type="submit" class="btn btn-bi rounded-4 w-100"><i class="bi bi-filter me-1"></i> Terapkan Filter Tanggal</button>
        </div>
    </form>
</div>

<!-- Main Printable Document Container -->
<div class="report-landscape-wrapper">

    <!-- HALAMAN 1: COVER SAMPUL DOKUMEN -->
    <div class="cover-page">
        <!-- Logo Header -->
        <div class="d-flex align-items-center justify-content-center gap-4 pt-2 mb-2">
            <img src="{{ asset('images/genbi-logo.png') }}" alt="Logo GenBI" class="cover-logo-img mb-0">
            <div style="height: 60px; width: 2px; background-color: var(--bi-navy); opacity: 0.35;"></div>
            <img src="{{ asset('images/genbi-polimdo.png') }}" alt="Logo GenBI Polimdo" style="height: 84px; width: 84px; object-fit: contain;">
        </div>

        <!-- Title Header -->
        <div class="my-auto py-3">
            <h1 class="cover-title mb-2">
                LAPORAN ENGAGEMENT INSTAGRAM
            </h1>
            <div class="fw-bold text-uppercase mb-3" style="font-size: 1.15rem; letter-spacing: 1px; color: #334155 !important;">
                Generasi Baru Indonesia Komisariat Politeknik Negeri Manado
            </div>
            <div class="cover-subtitle-text fw-bold fs-5">
                Periode: {{ $rentangTanggal }}
            </div>
        </div>

        <!-- Member Identity Box -->
        <div class="member-info-box my-auto">
            <div class="fw-bold text-uppercase border-bottom pb-2 mb-3 text-primary d-flex justify-content-between align-items-center flex-wrap gap-1" style="color: var(--bi-navy) !important; font-size: 0.95rem;">
                <span><i class="bi bi-person-badge me-2"></i> Identitas Anggota</span>
                <span class="badge bg-light text-dark border fw-semibold fs-7" style="text-transform: none !important;">GenBI Polimdo</span>
            </div>
            <div class="row g-3">
                <div class="col-4 col-sm-4 text-muted fw-semibold fs-6">Nama</div>
                <div class="col-8 col-sm-8 fw-bold text-dark fs-6">: {{ $user->name }}</div>

                <div class="col-4 col-sm-4 text-muted fw-semibold fs-6">Komisariat</div>
                <div class="col-8 col-sm-8 fw-bold text-dark fs-6">: Politeknik Negeri Manado</div>

                <div class="col-4 col-sm-4 text-muted fw-semibold fs-6">Rentang Tanggal</div>
                <div class="col-8 col-sm-8 fw-bold text-dark fs-6">: {{ $rentangTanggal }}</div>

                <div class="col-4 col-sm-4 text-muted fw-semibold fs-6">Total Postingan</div>
                <div class="col-8 col-sm-8 fw-bold text-primary fs-6" style="color: var(--bi-navy) !important;">: {{ $laporansGrouped->flatten()->count() }} Postingan Terlampir</div>
            </div>
        </div>

        <!-- Footer Watermark Note -->
        <div class="cover-footer-note text-center pb-1">
            <span>Generasi Baru Indonesia Komisariat Politeknik Negeri Manado</span>
        </div>
    </div>

    <!-- HALAMAN 2 DST: 1 POSTINGAN PER 1 HALAMAN LANDSCAPE -->
    @forelse($laporansGrouped as $akunId => $laporans)
        @php
            $firstLaporan = $laporans->first();
            $akun = $firstLaporan->akunInstagram;
        @endphp

        @foreach($laporans as $index => $laporan)
            <div class="post-page-card">
                
                <!-- PENANDA AKUN INSTAGRAM (Tampil di postingan pertama akun tersebut) -->
                @if($index === 0)
                    <div class="account-badge-header flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-instagram fs-5 text-warning"></i>
                            <div>
                                <h6 class="fw-bold mb-0 text-white" style="letter-spacing: 0.5px;">AKUN INSTAGRAM: {{ strtoupper($akun->nama_akun ?? 'AKUN INSTAGRAM') }}</h6>
                                <small class="text-white-50">{{ $akun->username ?? '' }}</small>
                            </div>
                        </div>
                        <span class="badge bg-light text-dark fw-bold px-3 py-1 rounded-pill">
                            {{ $laporans->count() }} Postingan Terlampir
                        </span>
                    </div>
                @endif

                <!-- Header Bar Postingan -->
                @php
                    $months = [
                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                    ];
                    $days = [
                        0 => 'Minggu', 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu',
                        4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu'
                    ];
                    $tgl = $laporan->tanggal_postingan;
                    $tglFormatted = $tgl->format('d') . ' ' . $months[(int)$tgl->format('m')] . ' ' . $tgl->format('Y');
                    $hariFormatted = $days[(int)$tgl->format('w')];
                @endphp

                <div class="d-flex justify-content-between align-items-center post-meta-header mb-2 pb-2 border-bottom flex-wrap gap-2">
                    <!-- Left Side: Number Badge + Post Date -->
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge rounded-circle text-white d-flex align-items-center justify-content-center shadow-sm" style="width: 28px; height: 28px; font-size: 0.85rem; background-color: var(--bi-navy);">
                            {{ $index + 1 }}
                        </span>
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="bi bi-calendar3 me-1 text-primary" style="color: var(--bi-navy) !important;"></i> Postingan Tanggal: {{ $tglFormatted }} <span class="text-muted fs-7 fw-normal">({{ $hariFormatted }})</span>
                        </h6>
                    </div>

                    <!-- Right Side: Instagram Handle + Hapus Button (Right-aligned) -->
                    <div class="d-flex align-items-center gap-2 ms-auto flex-wrap">
                        <span class="badge bg-light text-dark border px-2.5 py-1.5 fw-semibold small">
                            <i class="bi bi-instagram me-1 text-primary"></i> {{ $akun->username ? (str_starts_with($akun->username, '@') ? $akun->username : '@'.$akun->username) : $akun->nama_akun }}
                        </span>
                        <div class="no-print">
                            <form action="{{ route('user.laporan.destroy', $laporan->id) }}" method="POST" onsubmit="return confirm('Hapus entry postingan tanggal {{ $laporan->tanggal_postingan->format('d M Y') }}?')" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-2.5 py-1 fw-semibold" style="font-size: 0.78rem;">
                                    <i class="bi bi-trash-fill me-1"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                @if($laporan->judul_postingan || $laporan->link_postingan)
                    <div class="mb-2 px-3 py-2 bg-light border-start border-3 border-primary rounded-2 small text-dark">
                        @if($laporan->judul_postingan)
                            <div><strong><i class="bi bi-file-text me-1 text-primary"></i> Topik Postingan:</strong> {{ $laporan->judul_postingan }}</div>
                        @endif
                        @if($laporan->link_postingan)
                            <div class="mt-1" style="word-break: break-all;">
                                <strong><i class="bi bi-link-45deg me-1 text-primary"></i> Link Postingan:</strong> 
                                <a href="{{ $laporan->link_postingan }}" target="_blank" class="text-primary text-decoration-underline fw-semibold">{{ $laporan->link_postingan }}</a>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Row 3 Screenshots Berjejer Ke Samping (Like, Komen, Share) -->
                <div class="screenshot-row">
                    <!-- 1. Bukti Like -->
                    <div class="screenshot-col">
                        <div class="screenshot-caption">
                            <i class="bi bi-hand-thumbs-up-fill me-1" style="color: var(--bi-navy);"></i> LIKE
                        </div>
                        @if($laporan->bukti_like)
                            <img src="{{ asset('storage/' . $laporan->bukti_like) }}" alt="Bukti Like" class="screenshot-img">
                        @else
                            <div class="screenshot-placeholder">
                                <i class="bi bi-hand-thumbs-up fs-3 d-block mb-1"></i>
                                Bukti Like (Kosong)
                            </div>
                        @endif
                    </div>

                    <!-- 2. Bukti Komen -->
                    <div class="screenshot-col">
                        <div class="screenshot-caption">
                            <i class="bi bi-chat-left-text-fill me-1" style="color: var(--bi-navy);"></i> KOMEN
                        </div>
                        @if($laporan->bukti_komen)
                            <img src="{{ asset('storage/' . $laporan->bukti_komen) }}" alt="Bukti Komen" class="screenshot-img">
                        @else
                            <div class="screenshot-placeholder">
                                <i class="bi bi-chat-left-text fs-3 d-block mb-1"></i>
                                Bukti Komen (Kosong)
                            </div>
                        @endif
                    </div>

                    <!-- 3. Bukti Share -->
                    <div class="screenshot-col">
                        <div class="screenshot-caption">
                            <i class="bi bi-share-fill me-1" style="color: var(--bi-navy);"></i> SHARE
                        </div>
                        @if($laporan->bukti_share)
                            <img src="{{ asset('storage/' . $laporan->bukti_share) }}" alt="Bukti Share" class="screenshot-img">
                        @else
                            <div class="screenshot-placeholder">
                                <i class="bi bi-share fs-3 d-block mb-1"></i>
                                Bukti Share (Kosong)
                            </div>
                        @endif
                    </div>
                </div>

                @if($laporan->keterangan)
                    <div class="mt-1 pt-1 text-muted small fs-7">
                        <strong>Keterangan:</strong> {{ $laporan->keterangan }}
                    </div>
                @endif

            </div>
        @endforeach
    @empty
        <div class="text-center py-5 text-muted border rounded-4 bg-light">
            <i class="bi bi-journal-x fs-1 d-block mb-2 text-secondary"></i>
            <h5 class="fw-bold text-dark">Belum Ada Laporan untuk Tanggal {{ $rentangTanggal }}</h5>
            <p class="small mb-3">Klik tombol di bawah untuk mengunggah screenshot laporan postingan Instagram pertama Anda.</p>
            <a href="{{ route('user.laporan.create') }}" class="btn btn-bi"><i class="bi bi-plus-circle-fill me-1"></i> Upload Laporan Postingan</a>
        </div>
    @endforelse

</div>
@endsection