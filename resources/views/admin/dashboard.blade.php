@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('subtitle', 'Ringkasan pelaporan seluruh anggota GenBI Komisariat Polimdo')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-label">Total Anggota</div>
            <div class="stat-number">{{ number_format($totalAnggota) }}</div>
            <small class="text-muted"><i class="bi bi-people me-1"></i>anggota aktif</small>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-label">Laporan Masuk</div>
            <div class="stat-number">{{ number_format($laporanMasuk) }}</div>
            <small class="text-muted"><i class="bi bi-file-earmark-text me-1"></i>total laporan</small>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-label">Menunggu Validasi</div>
            <div class="stat-number text-warning">{{ number_format($menungguValidasi) }}</div>
            <small class="text-muted"><i class="bi bi-clock-history me-1"></i>perlu diperiksa</small>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-label">Ditolak</div>
            <div class="stat-number text-danger">{{ number_format($ditolak) }}</div>
            <small class="text-muted"><i class="bi bi-x-circle me-1"></i>butuh perbaikan</small>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="content-card h-100 mb-0">
            <h5 class="fw-bold mb-3"><i class="bi bi-pie-chart me-2"></i>Status Kelengkapan Anggota</h5>

            @php
                $sumTotal = max(1, $totalAnggota);
                $pctLengkap = round(($lengkapCount / $sumTotal) * 100, 1);
                $pctBelumLengkap = round(($belumLengkapCount / $sumTotal) * 100, 1);
                $pctBelumUpload = round(($belumUploadCount / $sumTotal) * 100, 1);
            @endphp

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Jumlah</th>
                            <th>Persentase</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">Lengkap</span></td>
                            <td class="fw-bold">{{ $lengkapCount }} orang</td>
                            <td>{{ $pctLengkap }}%</td>
                        </tr>
                        <tr>
                            <td><span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2">Belum Lengkap</span></td>
                            <td class="fw-bold">{{ $belumLengkapCount }} orang</td>
                            <td>{{ $pctBelumLengkap }}%</td>
                        </tr>
                        <tr>
                            <td><span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2">Belum Upload</span></td>
                            <td class="fw-bold">{{ $belumUploadCount }} orang</td>
                            <td>{{ $pctBelumUpload }}%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="content-card h-100 mb-0">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0"><i class="bi bi-bullseye me-2"></i>Target Hari Ini</h5>
                <a href="{{ route('admin.target-harian.index') }}" class="btn btn-bi btn-sm">+ Input Target</a>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Akun</th>
                            <th>Target</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($targetsToday as $target)
                            <tr>
                                <td class="fw-semibold">{{ $target->akunInstagram->nama_akun ?? '-' }}</td>
                                <td><span class="badge bg-secondary rounded-pill px-2 py-1">{{ $target->jumlah_target }}</span></td>
                                <td><span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">Sudah Diset</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-3">Belum ada target harian yang diset untuk hari ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection