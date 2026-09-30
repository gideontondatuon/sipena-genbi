@extends('layouts.app')

@section('title', 'Dashboard Personal SIPENA GenBI')
@section('subtitle', 'Pantau pencapaian target dan kelengkapan pelaporan engagement Anda')

@section('content')
<div class="row g-2 g-md-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label">Total Target</div>
                    <div class="stat-number text-primary">{{ number_format($totalTarget) }}</div>
                    <small class="text-muted"><i class="bi bi-bullseye me-1"></i>postingan wajib</small>
                </div>
                <div class="stat-icon bg-primary-subtle text-primary d-none d-sm-flex">
                    <i class="bi bi-bullseye"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="stat-card h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label">Diunggah</div>
                    <div class="stat-number text-info">{{ number_format($sudahUpload) }}</div>
                    <small class="text-muted"><i class="bi bi-cloud-arrow-up me-1"></i>laporan terkirim</small>
                </div>
                <div class="stat-icon bg-info-subtle text-info d-none d-sm-flex">
                    <i class="bi bi-journal-check"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="stat-card h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label">Valid</div>
                    <div class="stat-number text-success">{{ number_format($laporanValid) }}</div>
                    <small class="text-muted"><i class="bi bi-patch-check-fill me-1"></i>terverifikasi</small>
                </div>
                <div class="stat-icon bg-success-subtle text-success d-none d-sm-flex">
                    <i class="bi bi-check2-circle"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="stat-card h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label">Kekurangan</div>
                    <div class="stat-number {{ $kekurangan > 0 ? 'text-danger' : 'text-success' }}">{{ number_format($kekurangan) }}</div>
                    <small class="text-muted"><i class="bi {{ $kekurangan > 0 ? 'bi-exclamation-triangle' : 'bi-check-all' }} me-1"></i>{{ $kekurangan > 0 ? 'belum dipenuhi' : 'semua tuntas' }}</small>
                </div>
                <div class="stat-icon {{ $kekurangan > 0 ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success' }} d-none d-sm-flex">
                    <i class="bi {{ $kekurangan > 0 ? 'bi-exclamation-circle-fill' : 'bi-stars' }}"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content-card">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h5 class="fw-bold mb-1"><i class="bi bi-instagram text-primary me-2"></i>Status Laporan Per Akun Instagram</h5>
            <small class="text-muted">Setiap kali akun Instagram target posting, unggah bukti screenshot di sini</small>
        </div>
        <div class="d-flex gap-2 flex-column flex-sm-row w-100 w-sm-auto">
            <a href="{{ route('user.laporan.create') }}" class="btn btn-bi w-100 w-sm-auto"><i class="bi bi-plus-circle me-1"></i> + Upload Laporan Baru</a>
            <a href="{{ route('user.preview-laporan') }}" class="btn btn-outline-primary rounded-4 w-100 w-sm-auto"><i class="bi bi-printer me-1"></i> Preview Laporan Bulanan</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table align-middle" style="min-width: 600px;">
            <thead>
                <tr>
                    <th>Akun Instagram</th>
                    <th>Postingan Terakhir</th>
                    <th>Target</th>
                    <th>Ter-upload</th>
                    <th>Status</th>
                    <th>Aksi Cepat</th>
                </tr>
            </thead>
            <tbody>
                @forelse($targets as $t)
                    <tr>
                        <td class="fw-semibold">
                            <i class="bi bi-instagram text-primary me-2"></i>{{ $t->akun }}
                        </td>
                        <td>{{ $t->tanggal }}</td>
                        <td><span class="badge bg-secondary-subtle text-secondary rounded-pill px-2.5 py-1">{{ $t->target }}</span></td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary fw-bold px-2.5 py-1">
                                {{ $t->upload }} Postingan
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $t->badgeClass }} rounded-pill px-2.5 py-1">
                                {{ $t->status }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('user.laporan.create') }}" class="btn btn-sm btn-outline-primary rounded-3"><i class="bi bi-upload me-1"></i>Upload</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Belum ada akun Instagram yang terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection