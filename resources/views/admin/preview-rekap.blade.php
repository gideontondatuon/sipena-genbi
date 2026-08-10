@extends('layouts.app')

@section('title', 'Preview Rekap Anggota')
@section('subtitle', 'Format cetak Landscape A4 Presisi Rekap Kelengkapan Laporan Seluruh Anggota')

@section('content')
<style>
    @media print {
        @page {
            size: A4 landscape;
            margin: 12mm 12mm 12mm 12mm;
        }

        body {
            background-color: #FFFFFF !important;
            color: #000000 !important;
            font-family: 'Inter', system-ui, -apple-system, sans-serif !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .no-print, nav, header, footer, .btn, .navbar, .sidebar {
            display: none !important;
        }

        .main-content, .container-fluid, .container {
            padding: 0 !important;
            margin: 0 !important;
            max-width: 100% !important;
            width: 100% !important;
        }

        .report-landscape-wrapper {
            box-shadow: none !important;
            border: none !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
        }
    }

    .report-landscape-wrapper {
        background-color: #FFFFFF;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #CBD5E1;
        padding: 2.5rem;
        max-width: 1120px;
        margin: 0 auto 2.5rem auto;
        color: #1E293B;
    }

    .logo-container {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        background-color: #FFFFFF;
        padding: 6px 16px;
        border-radius: 8px;
    }

    .cover-title {
        color: #002B66;
        font-weight: 800;
        letter-spacing: 0.5px;
        font-size: 1.65rem;
        text-transform: uppercase;
        margin-bottom: 0.25rem;
    }

    .cover-subtitle-text {
        font-size: 0.9rem;
        font-weight: 600;
        color: #475569;
        background-color: #F1F5F9;
        display: inline-block;
        padding: 0.35rem 1.25rem;
        border-radius: 50px;
        margin-top: 0.5rem;
        border: 1px solid #E2E8F0;
    }

    .rekap-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.85rem;
        margin-top: 1.5rem;
    }

    .rekap-table th {
        background-color: #002B66 !important;
        color: #FFFFFF !important;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        padding: 0.75rem 0.5rem;
        border: 1px solid #002B66;
        text-align: center;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    .rekap-table td {
        padding: 0.65rem 0.5rem;
        border: 1px solid #E2E8F0;
        color: #1E293B;
    }

    .rekap-table tbody tr:nth-child(even) {
        background-color: #F8FAFC;
    }

    .signature-section {
        margin-top: 2.5rem;
        display: flex;
        justify-content: flex-end;
    }

    .signature-box {
        text-align: center;
        width: 250px;
    }
</style>

<!-- Top Toolbar Navigation (No-Print) -->
<div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white no-print max-w-1120 mx-auto" style="max-width: 1120px; border-left: 4px solid var(--bi-navy) !important;">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <form method="GET" action="{{ route('admin.preview-rekap') }}" class="row g-2 align-items-end mb-0 w-100 w-lg-auto">
            <div class="col-6 col-sm-auto">
                <label class="form-label fw-bold mb-1 small text-muted">Dari:</label>
                <input type="date" name="tanggal_mulai" class="form-control form-control-sm" value="{{ $tanggalMulai }}">
            </div>
            <div class="col-6 col-sm-auto">
                <label class="form-label fw-bold mb-1 small text-muted">Sampai:</label>
                <input type="date" name="tanggal_selesai" class="form-control form-control-sm" value="{{ $tanggalSelesai }}">
            </div>
            <div class="col-8 col-sm-auto">
                <label class="form-label fw-bold mb-1 small text-muted">Status:</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="Semua Status">Semua Status</option>
                    <option value="Lengkap" {{ request('status') === 'Lengkap' ? 'selected' : '' }}>Lengkap</option>
                    <option value="Belum Lengkap" {{ request('status') === 'Belum Lengkap' ? 'selected' : '' }}>Belum Lengkap</option>
                    <option value="Belum Upload" {{ request('status') === 'Belum Upload' ? 'selected' : '' }}>Belum Upload</option>
                </select>
            </div>
            <div class="col-4 col-sm-auto">
                <button type="submit" class="btn btn-sm btn-bi rounded-3 w-100"><i class="bi bi-filter me-1"></i> Filter</button>
            </div>
        </form>

        <div class="d-flex gap-2 w-100 w-lg-auto flex-column flex-sm-row">
            <a href="{{ route('admin.export.xlsx', ['tanggal_mulai' => $tanggalMulai, 'tanggal_selesai' => $tanggalSelesai, 'status' => request('status')]) }}" class="btn btn-success rounded-4 fw-bold px-3 w-100 w-sm-auto text-center">
                <i class="bi bi-file-earmark-excel me-1"></i> Download Excel
            </a>
            <button onclick="window.print()" class="btn btn-primary rounded-4 fw-bold px-3 w-100 w-sm-auto" style="background-color: #002B66 !important; border-color: #002B66 !important;">
                <i class="bi bi-printer me-1"></i> Cetak / PDF
            </button>
        </div>
    </div>
</div>

<!-- Printable Landscape Document Wrapper -->
<div class="report-landscape-wrapper">

    <!-- Header & Logos -->
    <div class="text-center mb-4">
        <div class="logo-container mb-3">
            <img src="{{ asset('images/genbi-logo.png') }}" alt="Logo GenBI" style="height: 48px; width: auto; object-fit: contain;">
            <div style="height: 28px; width: 1.5px; background-color: #CBD5E1;"></div>
            <img src="{{ asset('images/genbi-polimdo.png') }}" alt="Logo GenBI Polimdo" style="height: 48px; width: 48px; object-fit: contain;">
        </div>

        <h1 class="cover-title">REKAP KELENGKAPAN LAPORAN ANGGOTA</h1>
        <div class="fw-bold text-uppercase text-muted" style="font-size: 0.85rem; letter-spacing: 0.5px; color: #475569 !important;">
            Generasi Baru Indonesia Komisariat Politeknik Negeri Manado
        </div>

        <div class="cover-subtitle-text">
            Periode: {{ $rentangTanggal }}
        </div>
    </div>

    <!-- Summary Stats Bar -->
    <div class="row g-3 mb-4 text-center">
        <div class="col-md-3 col-6">
            <div class="p-2 border rounded-3 bg-light">
                <small class="text-muted d-block fw-semibold">Total Anggota</small>
                <span class="fs-5 fw-bold text-dark">{{ $members->count() }}</span>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="p-2 border rounded-3 bg-light">
                <small class="text-muted d-block fw-semibold">Status Lengkap</small>
                <span class="fs-5 fw-bold text-success">{{ $members->where('status', 'Lengkap')->count() }}</span>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="p-2 border rounded-3 bg-light">
                <small class="text-muted d-block fw-semibold">Belum Lengkap</small>
                <span class="fs-5 fw-bold text-warning">{{ $members->where('status', 'Belum Lengkap')->count() }}</span>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="p-2 border rounded-3 bg-light">
                <small class="text-muted d-block fw-semibold">Belum Upload</small>
                <span class="fs-5 fw-bold text-danger">{{ $members->where('status', 'Belum Upload')->count() }}</span>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="table-responsive">
        <table class="rekap-table">
            <thead>
                <tr>
                    <th style="width: 40px;">No</th>
                    <th style="text-align: left; padding-left: 0.75rem;">Nama Anggota</th>
                    <th style="text-align: left; padding-left: 0.75rem;">Email</th>
                    <th style="width: 100px;">NIM</th>
                    <th style="width: 70px;">Target</th>
                    <th style="width: 70px;">Upload</th>
                    <th style="width: 70px;">Kurang</th>
                    <th style="width: 70px;">Valid</th>
                    <th style="width: 70px;">Ditolak</th>
                    <th style="width: 120px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @forelse($members as $m)
                    <tr>
                        <td style="text-align: center; font-weight: 600;">{{ $no++ }}</td>
                        <td style="font-weight: 600; padding-left: 0.75rem;">{{ $m->name }}</td>
                        <td style="color: #475569; padding-left: 0.75rem;">{{ $m->email }}</td>
                        <td style="text-align: center; font-weight: 500;">{{ $m->nim }}</td>
                        <td style="text-align: center;">{{ $m->target }}</td>
                        <td style="text-align: center; font-weight: 600;">{{ $m->upload }}</td>
                        <td style="text-align: center; font-weight: bold; color: {{ $m->kurang > 0 ? '#DC2626' : '#64748B' }};">
                            {{ $m->kurang > 0 ? $m->kurang : '-' }}
                        </td>
                        <td style="text-align: center; font-weight: bold; color: #16A34A;">{{ $m->valid }}</td>
                        <td style="text-align: center; font-weight: bold; color: {{ $m->ditolak > 0 ? '#DC2626' : '#64748B' }};">
                            {{ $m->ditolak }}
                        </td>
                        <td style="text-align: center;">
                            <span class="badge {{ $m->badgeClass }} rounded-pill px-3 py-1 fw-bold fs-7">
                                {{ $m->status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" style="text-align: center; padding: 2rem; color: #64748B;">
                            Data rekap anggota tidak ditemukan untuk kriteria filter ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Signature Approval Section -->
    <div class="signature-section">
        <div class="signature-box">
            <p class="mb-1 text-muted small">Manado, {{ date('d F Y') }}</p>
            <p class="fw-bold mb-5" style="color: #002B66;">Pengurus GenBI Polimdo</p>
            <div class="border-bottom border-dark mx-auto style-line" style="width: 180px;"></div>
            <p class="fw-bold mt-1 mb-0 small">Ketua / Pembina</p>
        </div>
    </div>

    <!-- Footer Watermark Note -->
    <div class="text-center mt-4 pt-3 border-top" style="font-size: 0.72rem; color: #94A3B8;">
        <span>Generasi Baru Indonesia Komisariat Politeknik Negeri Manado &mdash; Laporan Resmi Rekapitulasi</span>
    </div>
</div>
@endsection
