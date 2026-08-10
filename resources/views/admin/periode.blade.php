@extends('layouts.app')

@section('title', 'Periode Laporan')
@section('subtitle', 'Kelola periode mingguan pelaporan GenBI Komisariat Polimdo')

@section('content')
<div class="row g-4">
    <div class="col-lg-5">
        <div class="content-card">
            <h5 class="fw-bold mb-1">Tambah Periode</h5>
            <p class="text-muted mb-4">Buat periode pelaporan mingguan agar laporan lebih terstruktur.</p>

            <form action="{{ route('admin.periode.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Periode</label>
                    <input type="text" name="nama_periode" class="form-control" placeholder="Contoh: Minggu 1 Juli 2026" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" class="form-control" required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="aktif">Aktif</option>
                        <option value="arsip">Arsip</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-bi w-100">Simpan Periode</button>
            </form>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="content-card">
            <h5 class="fw-bold mb-3">Daftar Periode</h5>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Periode</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($periodeList as $p)
                            <tr>
                                <td class="fw-semibold">{{ $p->nama_periode }}</td>
                                <td>{{ $p->tanggal_mulai->format('d M') }} - {{ $p->tanggal_selesai->format('d M Y') }}</td>
                                <td>
                                    @if($p->status === 'aktif')
                                        <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2">Arsip</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary rounded-4" data-bs-toggle="modal" data-bs-target="#modalEditPeriode{{ $p->id }}">Edit</button>
                                </td>
                            </tr>

                            <!-- Modal Edit Periode -->
                            <div class="modal fade" id="modalEditPeriode{{ $p->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content rounded-4 border-0">
                                        <form action="{{ route('admin.periode.update', $p->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header border-0 pb-0">
                                                <h5 class="modal-title fw-bold">Edit Periode {{ $p->nama_periode }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Nama Periode</label>
                                                    <input type="text" name="nama_periode" class="form-control" value="{{ $p->nama_periode }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Tanggal Mulai</label>
                                                    <input type="date" name="tanggal_mulai" class="form-control" value="{{ $p->tanggal_mulai->format('Y-m-d') }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Tanggal Selesai</label>
                                                    <input type="date" name="tanggal_selesai" class="form-control" value="{{ $p->tanggal_selesai->format('Y-m-d') }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Status</label>
                                                    <select name="status" class="form-select">
                                                        <option value="aktif" {{ $p->status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                                        <option value="arsip" {{ $p->status === 'arsip' ? 'selected' : '' }}>Arsip</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0">
                                                <button type="button" class="btn btn-secondary rounded-4" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-bi">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Belum ada periode yang dibuat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection