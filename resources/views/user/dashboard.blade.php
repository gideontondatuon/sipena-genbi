@extends('layouts.app')

@section('title', 'Dashboard Personal SIPENA GenBI')
@section('subtitle', 'Asisten otomatis pembuatan laporan engagement Instagram pengganti Word')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-md-4">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label">Total Postingan Terlaporkan</div>
                    <div class="stat-number text-primary">{{ number_format($sudahUpload) }}</div>
                    <small class="text-muted"><i class="bi bi-file-earmark-check me-1"></i>laporan tersimpan</small>
                </div>
                <div class="stat-icon bg-primary-subtle text-primary">
                    <i class="bi bi-journal-check"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-4">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label">Format Dokumen</div>
                    <div class="stat-number text-success">PDF / Print</div>
                    <small class="text-muted"><i class="bi bi-printer me-1"></i>siap cetak otomatis</small>
                </div>
                <div class="stat-icon bg-success-subtle text-success">
                    <i class="bi bi-printer-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-4">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label">Aksi Cepat</div>
                    <div class="mt-2">
                        <a href="{{ route('user.laporan.create') }}" class="btn btn-bi btn-sm w-100 mb-1">+ Upload Screenshot</a>
                    </div>
                </div>
                <div class="stat-icon bg-danger-subtle text-danger">
                    <i class="bi bi-cloud-arrow-up-fill"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content-card">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h5 class="fw-bold mb-1"><i class="bi bi-star-fill text-warning me-2"></i>Status Laporan Per Akun Instagram</h5>
            <small class="text-muted">Setiap kali akun Instagram target posting, upload bukti screenshot di sini</small>
        </div>
        <div class="d-flex gap-2 flex-column flex-sm-row w-100 w-sm-auto">
            <a href="{{ route('user.laporan.create') }}" class="btn btn-bi w-100 w-sm-auto">+ Upload Laporan Baru</a>
            <a href="{{ route('user.preview-laporan') }}" class="btn btn-outline-primary rounded-4 w-100 w-sm-auto"><i class="bi bi-printer me-1"></i> Preview Laporan Bulanan</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Akun Instagram</th>
                    <th>Postingan Terakhir</th>
                    <th>Jumlah Ter-upload</th>
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
                        <td>
                            <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-2">
                                {{ $t->upload }} Postingan
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('user.laporan.create') }}" class="btn btn-sm btn-outline-primary rounded-3">+ Upload Screenshot</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">Belum ada akun Instagram yang terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection