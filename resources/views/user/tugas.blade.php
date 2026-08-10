@extends('layouts.app')

@section('title', 'Tugas Laporan')
@section('subtitle', 'Daftar target laporan berdasarkan akun dan tanggal postingan')

@section('content')
<div class="content-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h5 class="fw-bold mb-1">Daftar Tugas</h5>
            <small class="text-muted">Sistem otomatis menghitung kelengkapan berdasarkan target admin</small>
        </div>
        <a href="{{ route('user.laporan.create') }}" class="btn btn-bi">+ Upload Laporan</a>
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Akun</th>
                    <th>Tanggal</th>
                    <th>Target</th>
                    <th>Upload</th>
                    <th>Kekurangan</th>
                    <th>Deadline</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tugasList as $t)
                    <tr>
                        <td class="fw-semibold">{{ $t->akun }}</td>
                        <td>{{ $t->tanggal }}</td>
                        <td><span class="badge bg-secondary rounded-pill px-2 py-1">{{ $t->target }}</span></td>
                        <td>{{ $t->upload }}</td>
                        <td class="{{ $t->kekurangan > 0 ? 'text-danger fw-bold' : '' }}">{{ $t->kekurangan > 0 ? $t->kekurangan : '-' }}</td>
                        <td><small class="text-muted">{{ $t->deadline }}</small></td>
                        <td><span class="badge {{ $t->badgeClass }} rounded-pill px-3 py-2">{{ $t->status }}</span></td>
                        <td>
                            @if($t->kekurangan > 0)
                                <a href="{{ route('user.laporan.create', ['akun_id' => $t->akun_id, 'tanggal' => $t->tanggal_raw]) }}" class="btn btn-sm btn-bi">Upload</a>
                            @else
                                <span class="badge bg-success-subtle text-success"><i class="bi bi-check-lg"></i> Selesai</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">Belum ada tugas laporan harian.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection