@extends('layouts.app')

@section('title', 'Rekap Anggota')
@section('subtitle', 'Rekap kelengkapan laporan anggota GenBI Komisariat Polimdo')

@section('content')
<div class="content-card">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h5 class="fw-bold mb-1">Rekap Kelengkapan Anggota</h5>
            <small class="text-muted">Daftar anggota lengkap, belum lengkap, dan belum upload</small>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.export.xlsx', ['tanggal_mulai' => $tanggalMulai, 'tanggal_selesai' => $tanggalSelesai, 'status' => request('status')]) }}" class="btn btn-outline-success rounded-4"><i class="bi bi-file-earmark-excel me-1"></i> Export Excel (.xlsx)</a>
            <a href="{{ route('admin.preview-rekap', ['tanggal_mulai' => $tanggalMulai, 'tanggal_selesai' => $tanggalSelesai, 'status' => request('status')]) }}" class="btn btn-bi rounded-4"><i class="bi bi-file-earmark-pdf me-1"></i> Preview / Cetak PDF Rekap</a>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.rekap.index') }}" class="row g-3 mb-4 align-items-end">
        <div class="col-md-3">
            <label class="form-label fw-semibold small text-muted"><i class="bi bi-calendar-event me-1 text-primary"></i> Dari Tanggal</label>
            <input type="date" name="tanggal_mulai" class="form-control" value="{{ $tanggalMulai }}" onchange="this.form.submit()">
        </div>

        <div class="col-md-3">
            <label class="form-label fw-semibold small text-muted"><i class="bi bi-calendar-check me-1 text-primary"></i> Sampai Tanggal</label>
            <input type="date" name="tanggal_selesai" class="form-control" value="{{ $tanggalSelesai }}" onchange="this.form.submit()">
        </div>

        <div class="col-md-3">
            <label class="form-label fw-semibold small text-muted"><i class="bi bi-funnel me-1 text-primary"></i> Status Filter</label>
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="Semua Status">Semua Status</option>
                <option value="Lengkap" {{ request('status') === 'Lengkap' ? 'selected' : '' }}>Lengkap</option>
                <option value="Belum Lengkap" {{ request('status') === 'Belum Lengkap' ? 'selected' : '' }}>Belum Lengkap</option>
                <option value="Belum Upload" {{ request('status') === 'Belum Upload' ? 'selected' : '' }}>Belum Upload</option>
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label fw-semibold small text-muted"><i class="bi bi-search me-1 text-primary"></i> Cari Anggota</label>
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari nama..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Nama Anggota</th>
                    <th>Target</th>
                    <th>Upload</th>
                    <th>Kurang</th>
                    <th>Valid</th>
                    <th>Ditolak</th>
                    <th>Status</th>
                    <th>Laporan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $m)
                    <tr>
                        <td class="fw-semibold">{{ $m->name }}</td>
                        <td>{{ $m->target }}</td>
                        <td>{{ $m->upload }}</td>
                        <td class="{{ $m->kurang > 0 ? 'text-danger fw-bold' : '' }}">{{ $m->kurang > 0 ? $m->kurang : '-' }}</td>
                        <td class="text-success fw-bold">{{ $m->valid }}</td>
                        <td class="{{ $m->ditolak > 0 ? 'text-danger' : '' }}">{{ $m->ditolak }}</td>
                        <td><span class="badge {{ $m->badgeClass }} rounded-pill px-3 py-2">{{ $m->status }}</span></td>
                        <td>
                            <a href="{{ route('admin.preview-laporan', ['user_id' => $m->id, 'tanggal_mulai' => $tanggalMulai, 'tanggal_selesai' => $tanggalSelesai]) }}" class="btn btn-sm btn-outline-primary rounded-4">Preview</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">Data rekap anggota tidak ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection