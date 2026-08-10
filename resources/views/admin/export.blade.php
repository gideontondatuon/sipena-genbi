@extends('layouts.app')

@section('title', 'Export Data')
@section('subtitle', 'Unduh laporan dalam bentuk PDF atau Excel')

@section('content')
<div class="row g-4">
    <div class="col-lg-6">
        <div class="content-card h-100">
            <h5 class="fw-bold mb-1"><i class="bi bi-file-earmark-excel me-2"></i>Export Rekap Anggota</h5>
            <p class="text-muted mb-4">Unduh rekap seluruh anggota dalam bentuk Excel (.xlsx) atau cetak PDF.</p>

            <form action="{{ route('admin.export.xlsx') }}" method="GET">
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label fw-semibold small">Dari Tanggal</label>
                        <input type="date" name="tanggal_mulai" class="form-control" value="{{ date('Y-m-01') }}" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold small">Sampai Tanggal</label>
                        <input type="date" name="tanggal_selesai" class="form-control" value="{{ date('Y-m-t') }}" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="Semua Status">Semua Status</option>
                        <option value="Lengkap">Lengkap</option>
                        <option value="Belum Lengkap">Belum Lengkap</option>
                        <option value="Belum Upload">Belum Upload</option>
                    </select>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" formaction="{{ route('admin.preview-rekap') }}" formmethod="GET" class="btn btn-outline-primary rounded-4 w-100"><i class="bi bi-eye me-1"></i> Preview / Cetak PDF</button>
                    <button type="submit" formaction="{{ route('admin.export.xlsx') }}" formmethod="GET" class="btn btn-success rounded-4 w-100"><i class="bi bi-file-earmark-excel me-1"></i> Download Excel (.xlsx)</button>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="content-card h-100">
            <h5 class="fw-bold mb-1"><i class="bi bi-file-earmark-pdf me-2"></i>Export PDF / Preview Individu</h5>
            <p class="text-muted mb-4">Unduh laporan individu anggota dalam bentuk PDF atau cetak.</p>

            <form action="{{ route('admin.preview-laporan') }}" method="GET">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Anggota</label>
                    <select name="user_id" class="form-select" required>
                        @foreach($members as $m)
                            <option value="{{ $m->id }}">{{ $m->name }} ({{ $m->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="row g-2 mb-4">
                    <div class="col-6">
                        <label class="form-label fw-semibold small">Dari Tanggal</label>
                        <input type="date" name="tanggal_mulai" class="form-control" value="{{ date('Y-m-01') }}" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold small">Sampai Tanggal</label>
                        <input type="date" name="tanggal_selesai" class="form-control" value="{{ date('Y-m-t') }}" required>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-outline-primary rounded-4 w-100"><i class="bi bi-eye me-1"></i> Preview</button>
                    <button type="submit" class="btn btn-bi w-100"><i class="bi bi-file-earmark-pdf me-1"></i> Cetak / PDF</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection