@extends('layouts.app')

@section('title', 'Notifikasi')
@section('subtitle', 'Pemberitahuan status target dan laporan')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="content-card">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <div>
                    <h5 class="fw-bold mb-1"><i class="bi bi-bell-fill me-2 text-primary"></i>Notifikasi Saya</h5>
                    <small class="text-muted">Informasi terbaru terkait target dan status laporan Anda</small>
                </div>

                <div class="d-flex align-items-center gap-2 flex-wrap w-100 w-sm-auto justify-content-start justify-content-sm-end">
                    @php
                        $unreadCount = $notifikasis->where('is_read', false)->count();
                    @endphp
                    @if($unreadCount > 0)
                        <form action="{{ route('notifikasi.read-all') }}" method="POST" class="m-0 flex-grow-1 flex-sm-grow-0">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary rounded-4 btn-sm fw-semibold w-100"><i class="bi bi-check-all me-1"></i> Tandai Semua Dibaca</button>
                        </form>
                    @endif

                    @if($notifikasis->count() > 0)
                        <form action="{{ route('notifikasi.clear-all') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus seluruh notifikasi?')" class="m-0 flex-grow-1 flex-sm-grow-0">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger rounded-4 btn-sm fw-semibold w-100"><i class="bi bi-trash3-fill me-1"></i> Hapus Semua Notifikasi</button>
                        </form>
                    @endif
                </div>
            </div>

            @forelse($notifikasis as $notif)
                <div class="border rounded-4 p-3 mb-3 transition-all {{ $notif->is_read ? 'bg-white' : 'bg-light border-primary shadow-sm' }}">
                    <div class="d-flex gap-3 align-items-start">
                        <div class="fs-2 pt-1">
                            @if($notif->tipe === 'target')
                                <i class="bi bi-bullseye text-primary"></i>
                            @elseif($notif->tipe === 'warning')
                                <i class="bi bi-exclamation-triangle-fill text-warning"></i>
                            @elseif($notif->tipe === 'ditolak')
                                <i class="bi bi-x-circle-fill text-danger"></i>
                            @else
                                <i class="bi bi-check-circle-fill text-success"></i>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <a href="{{ route('notifikasi.open', $notif->id) }}" class="text-decoration-none text-dark fw-bold fs-6 flex-grow-1">
                                    {{ $notif->judul }}
                                    @if(!$notif->is_read)
                                        <span class="badge bg-danger ms-2 rounded-pill fs-7">Baru</span>
                                    @endif
                                </a>

                                <form action="{{ route('notifikasi.destroy', $notif->id) }}" method="POST" onsubmit="return confirm('Hapus notifikasi ini?')" class="m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link text-danger p-0 border-0 fs-6" title="Hapus Notifikasi"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>

                            <a href="{{ route('notifikasi.open', $notif->id) }}" class="text-decoration-none text-muted d-block small mt-1">
                                {{ $notif->pesan }}
                            </a>

                            <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top border-light">
                                <small class="text-muted" style="font-size: 0.75rem;">
                                    <i class="bi bi-clock me-1"></i> {{ $notif->created_at->diffForHumans() }}
                                </small>
                                @if(!$notif->is_read)
                                    <a href="{{ route('notifikasi.open', $notif->id) }}" class="btn btn-sm btn-light text-primary fw-semibold rounded-pill py-0.5 px-2.5 fs-7">
                                        <i class="bi bi-envelope-open me-1"></i> Tandai Dibaca
                                    </a>
                                @else
                                    <span class="text-muted fs-7"><i class="bi bi-check2-all text-success me-1"></i> Sudah Dibaca</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-bell-slash fs-1 d-block mb-2 text-secondary"></i>
                    Belum ada notifikasi saat ini.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection