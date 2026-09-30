@extends('layouts.app')

@section('title', 'Perbaiki Laporan')
@section('subtitle', 'Perbarui bukti screenshot sesuai catatan dari admin')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">

        {{-- Alert catatan admin --}}
        @if($laporan->catatan_admin)
        <div class="alert border-0 rounded-4 mb-4 d-flex align-items-start gap-3"
             style="background: linear-gradient(135deg,#FFF8E1,#FFF3CD); border-left: 4px solid #F59E0B !important; border-left-width: 4px !important;">
            <i class="bi bi-exclamation-triangle-fill text-warning fs-4 mt-1"></i>
            <div>
                <div class="fw-bold text-warning-emphasis mb-1">Catatan Admin</div>
                <div class="text-dark small lh-base">{{ $laporan->catatan_admin }}</div>
            </div>
        </div>
        @endif

        <div class="content-card">
            <h5 class="fw-bold mb-1"><i class="bi bi-pencil-square me-2 text-warning"></i>Form Perbaikan Laporan</h5>
            <p class="text-muted mb-4 small">Ganti screenshot yang bermasalah dan perbarui keterangan jika diperlukan. Screenshot yang tidak diganti akan tetap digunakan.</p>

            <form action="{{ route('user.laporan.update', $laporan->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Info laporan (read-only) --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-muted small">Akun Instagram</label>
                        <div class="form-control bg-light fw-bold">
                            <i class="bi bi-instagram text-primary me-1"></i>
                            {{ $laporan->akunInstagram->nama_akun ?? '-' }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-muted small">Tanggal Postingan</label>
                        <div class="form-control bg-light fw-bold">
                            <i class="bi bi-calendar3 me-1"></i>
                            {{ $laporan->tanggal_postingan->format('d F Y') }}
                        </div>
                    </div>
                </div>

                <hr class="my-3">

                {{-- Bukti Like --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-hand-thumbs-up-fill text-success me-1"></i> Bukti Like
                        <span class="badge bg-secondary-subtle text-secondary ms-1 fw-normal" style="font-size:0.75rem;">Opsional — ganti jika perlu</span>
                    </label>
                    @if($laporan->bukti_like)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $laporan->bukti_like) }}" alt="Bukti Like" class="rounded-3 border" style="max-height:120px; object-fit:cover;">
                            <div class="text-muted small mt-1"><i class="bi bi-image me-1"></i>Screenshot saat ini</div>
                        </div>
                    @endif
                    <input type="file" name="bukti_like" class="form-control @error('bukti_like') is-invalid @enderror" accept="image/*">
                    @error('bukti_like')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                {{-- Bukti Komen --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-chat-fill text-info me-1"></i> Bukti Komen
                        <span class="badge bg-secondary-subtle text-secondary ms-1 fw-normal" style="font-size:0.75rem;">Opsional — ganti jika perlu</span>
                    </label>
                    @if($laporan->bukti_komen)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $laporan->bukti_komen) }}" alt="Bukti Komen" class="rounded-3 border" style="max-height:120px; object-fit:cover;">
                            <div class="text-muted small mt-1"><i class="bi bi-image me-1"></i>Screenshot saat ini</div>
                        </div>
                    @endif
                    <input type="file" name="bukti_komen" class="form-control @error('bukti_komen') is-invalid @enderror" accept="image/*">
                    @error('bukti_komen')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                {{-- Bukti Share --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-share-fill text-primary me-1"></i> Bukti Share
                        <span class="badge bg-secondary-subtle text-secondary ms-1 fw-normal" style="font-size:0.75rem;">Opsional — ganti jika perlu</span>
                    </label>
                    @if($laporan->bukti_share)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $laporan->bukti_share) }}" alt="Bukti Share" class="rounded-3 border" style="max-height:120px; object-fit:cover;">
                            <div class="text-muted small mt-1"><i class="bi bi-image me-1"></i>Screenshot saat ini</div>
                        </div>
                    @endif
                    <input type="file" name="bukti_share" class="form-control @error('bukti_share') is-invalid @enderror" accept="image/*">
                    @error('bukti_share')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                {{-- Keterangan --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Keterangan Tambahan</label>
                    <textarea name="keterangan" rows="3" class="form-control" placeholder="Tambahkan keterangan perbaikan jika diperlukan...">{{ old('keterangan', $laporan->keterangan) }}</textarea>
                </div>

                <hr class="my-3">

                <div class="d-flex gap-2 flex-column flex-sm-row justify-content-end">
                    <a href="{{ route('user.riwayat.index') }}" class="btn btn-outline-secondary rounded-4 px-4 w-100 w-sm-auto order-2 order-sm-1 text-center">
                        <i class="bi bi-arrow-left me-1"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-warning fw-bold rounded-4 px-4 w-100 w-sm-auto order-1 order-sm-2" style="color:#1a1a1a;">
                        <i class="bi bi-send-fill me-1"></i> Kirim Perbaikan
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
