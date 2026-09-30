@extends('layouts.app')

@section('title', 'Daftar Laporan Saya')
@section('subtitle', 'Daftar seluruh bukti screenshot postingan yang telah Anda unggah')

@section('content')
<div class="content-card">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h5 class="fw-bold mb-1"><i class="bi bi-journal-text me-2 text-primary"></i>Semua Postingan Terlaporkan</h5>
            <small class="text-muted">Kelola dan tinjau berkas postingan yang telah Anda simpan</small>
        </div>
        <div class="d-flex gap-2 flex-column flex-sm-row w-100 w-sm-auto">
            <a href="{{ route('user.laporan.create') }}" class="btn btn-bi w-100 w-sm-auto">+ Upload Laporan Baru</a>
            <a href="{{ route('user.preview-laporan') }}" class="btn btn-outline-primary rounded-4 w-100 w-sm-auto"><i class="bi bi-printer me-1"></i> Preview Laporan Bulanan</a>
        </div>
    </div>

    <div class="d-md-none text-muted small mb-2">
        <i class="bi bi-arrow-left-right me-1 text-primary"></i>Geser ke samping untuk melihat status & aksi
    </div>

    <div class="table-responsive">
        <table class="table table-sm table-hover align-middle" style="min-width: 650px;">
            <thead>
                <tr>
                    <th class="d-none d-sm-table-cell">No</th>
                    <th>Akun Instagram</th>
                    <th>Tanggal Postingan</th>
                    <th>Bukti Screenshot</th>
                    <th>Status</th>
                    <th class="d-none d-md-table-cell">Diupload Pada</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayatList as $index => $item)
                    <tr>
                        <td class="d-none d-sm-table-cell">{{ $index + 1 }}</td>
                        <td class="fw-semibold">
                            <div><i class="bi bi-instagram text-primary me-1"></i> {{ $item->akunInstagram->nama_akun ?? '-' }}</div>
                            @if($item->judul_postingan)
                                <small class="text-muted d-block fw-normal mt-0.5"><i class="bi bi-file-text me-1 text-primary"></i>{{ $item->judul_postingan }}</small>
                            @endif
                        </td>
                        <td><span class="badge bg-primary-subtle text-primary fw-bold px-2.5 py-1.5" style="font-size: 0.75rem;">{{ $item->tanggal_postingan->format('d M Y') }}</span></td>
                        <td>
                            <div class="d-flex gap-1 flex-wrap">
                                @if($item->bukti_like)<span class="badge bg-success-subtle text-success">Like</span>@endif
                                @if($item->bukti_komen)<span class="badge bg-info-subtle text-info">Komen</span>@endif
                                @if($item->bukti_share)<span class="badge bg-primary-subtle text-primary">Share</span>@endif
                            </div>
                        </td>
                        <td>
                            @if($item->status === 'valid')
                                <span class="badge bg-success-subtle text-success rounded-pill px-2.5 py-1">
                                    <i class="bi bi-check-circle-fill me-1"></i> Valid
                                </span>
                            @elseif($item->status === 'ditolak')
                                <span class="badge bg-danger-subtle text-danger rounded-pill px-2.5 py-1" title="{{ $item->catatan_admin ?? 'Ditolak' }}">
                                    <i class="bi bi-x-circle-fill me-1"></i> Ditolak
                                </span>
                                @if($item->catatan_admin)
                                    <small class="d-block text-danger mt-1" style="font-size: 0.72rem;"><i class="bi bi-chat-left-quote me-1"></i>{{ Str::limit($item->catatan_admin, 35) }}</small>
                                @endif
                            @elseif($item->status === 'perlu_perbaikan')
                                <span class="badge bg-warning-subtle text-warning rounded-pill px-2.5 py-1" title="{{ $item->catatan_admin ?? 'Perlu Perbaikan' }}">
                                    <i class="bi bi-exclamation-circle-fill me-1"></i> Perlu Perbaikan
                                </span>
                                @if($item->catatan_admin)
                                    <small class="d-block text-warning-emphasis mt-1" style="font-size: 0.72rem;"><i class="bi bi-chat-left-quote me-1"></i>{{ Str::limit($item->catatan_admin, 35) }}</small>
                                @endif
                            @else
                                <span class="badge bg-primary-subtle text-primary rounded-pill px-2.5 py-1">
                                    <i class="bi bi-hourglass-split me-1"></i> Menunggu Validasi
                                </span>
                            @endif
                        </td>
                        <td class="d-none d-md-table-cell"><small class="text-muted">{{ $item->created_at->format('d M Y, H:i') }}</small></td>
                        <td>
                            <div class="d-flex gap-1 flex-wrap">
                                <a href="{{ route('user.preview-laporan') }}" class="btn btn-sm btn-outline-primary rounded-3" title="Lihat"><i class="bi bi-eye"></i> <span class="d-none d-sm-inline">Lihat</span></a>
                                @if($item->status === 'perlu_perbaikan')
                                    <a href="{{ route('user.laporan.edit', $item->id) }}" class="btn btn-sm btn-warning rounded-3" title="Perbaiki" style="color:#1a1a1a;">
                                        <i class="bi bi-pencil-square"></i> <span class="d-none d-sm-inline">Perbaiki</span>
                                    </a>
                                @endif
                                <form action="{{ route('user.laporan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus entry laporan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-3" title="Hapus"><i class="bi bi-trash"></i> <span class="d-none d-sm-inline">Hapus</span></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Belum ada riwayat pengunggahan laporan postingan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection