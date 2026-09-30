@extends('layouts.app')

@section('title', 'Edit Laporan Postingan')
@section('subtitle', 'Perbarui informasi postingan, ganti screenshot, atau revisi keterangan laporan')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">

        {{-- Alert Catatan Admin jika ada --}}
        @if($laporan->catatan_admin)
        <div class="alert border-0 rounded-4 mb-4 d-flex align-items-start gap-3 shadow-sm"
             style="background: linear-gradient(135deg,#FFF8E1,#FFF3CD); border-left: 5px solid #F59E0B !important;">
            <i class="bi bi-exclamation-triangle-fill text-warning fs-3 mt-1"></i>
            <div>
                <div class="fw-bold text-warning-emphasis mb-1" style="font-size: 1rem;">Catatan Review Admin</div>
                <div class="text-dark small lh-base">{{ $laporan->catatan_admin }}</div>
            </div>
        </div>
        @endif

        <div class="content-card shadow-sm border-0 rounded-4 p-4 bg-white">
            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                <div>
                    <h5 class="fw-bold mb-1 text-primary">
                        <i class="bi bi-pencil-square me-2"></i>Edit Data Laporan
                    </h5>
                    <p class="text-muted small mb-0">Ubah judul postingan, tanggal, tautan, atau unggah ulang bukti screenshot.</p>
                </div>
                <div>
                    <span class="badge {{ $laporan->status === 'valid' ? 'bg-success' : ($laporan->status === 'ditolak' ? 'bg-danger' : ($laporan->status === 'perlu_perbaikan' ? 'bg-warning text-dark' : 'bg-primary')) }} px-3 py-2 rounded-pill text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                        Status: {{ $laporan->status === 'perlu_perbaikan' ? 'Perlu Perbaikan' : ucfirst($laporan->status) }}
                    </span>
                </div>
            </div>

            <form action="{{ route('user.laporan.update', $laporan->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3 mb-4">
                    {{-- Pilihan Akun Instagram --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Akun Instagram <span class="text-danger">*</span></label>
                        <select name="akun_instagram_id" class="form-select @error('akun_instagram_id') is-invalid @enderror" required>
                            <option value="">Pilih akun Instagram</option>
                            @foreach($akunList as $akun)
                                <option value="{{ $akun->id }}" {{ (old('akun_instagram_id', $laporan->akun_instagram_id) == $akun->id) ? 'selected' : '' }}>
                                    {{ $akun->nama_akun }} ({{ $akun->username }})
                                </option>
                            @endforeach
                        </select>
                        @error('akun_instagram_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tanggal Postingan --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tanggal Postingan <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_postingan" class="form-control @error('tanggal_postingan') is-invalid @enderror" 
                               value="{{ old('tanggal_postingan', $laporan->tanggal_postingan ? $laporan->tanggal_postingan->format('Y-m-d') : '') }}" required>
                        @error('tanggal_postingan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Judul / Topik Postingan --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-file-earmark-text text-primary me-1"></i> Judul / Topik Postingan <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-primary"><i class="bi bi-pen"></i></span>
                            <input type="text" 
                                   name="judul_postingan" 
                                   id="judul_postingan" 
                                   class="form-control @error('judul_postingan') is-invalid @enderror" 
                                   placeholder="Contoh: Edukasi CBP Rupiah / Sosialisasi QRIS / Pengumuman Beasiswa BI" 
                                   value="{{ old('judul_postingan', $laporan->judul_postingan) }}" 
                                   required>
                        </div>
                        <small class="text-muted d-block mt-1">
                            <i class="bi bi-info-circle me-1"></i>Topik postingan ini akan dicantumkan pada <strong>format cetak laporan PDF</strong>.
                        </small>
                        @error('judul_postingan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Link Postingan Instagram --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-link-45deg text-primary me-1"></i> Link Postingan Instagram <span class="text-muted fw-normal">(Opsional)</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-primary"><i class="bi bi-instagram"></i></span>
                            <input type="url" 
                                   name="link_postingan" 
                                   id="link_postingan" 
                                   class="form-control @error('link_postingan') is-invalid @enderror" 
                                   placeholder="https://www.instagram.com/p/..." 
                                   value="{{ old('link_postingan', $laporan->link_postingan) }}">
                        </div>
                        <small class="text-muted d-block mt-1">Tautan URL postingan Instagram.</small>
                        @error('link_postingan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Upload / Ganti Gambar Bukti --}}
                <div class="mb-3">
                    <label class="form-label fw-bold fs-6 mb-1 text-dark">
                        <i class="bi bi-images text-primary me-1"></i> Bukti Screenshot
                    </label>
                    <p class="text-muted small mb-3">Pilih file baru hanya jika ingin mengganti screenshot yang sudah ada. Jika tidak diubah, screenshot lama akan tetap disimpan.</p>
                </div>

                <div class="row g-3 mb-4">
                    {{-- 1. Bukti Like --}}
                    <div class="col-12 col-md-4">
                        <div class="border rounded-4 p-3 text-center bg-light position-relative h-100 d-flex flex-column justify-content-between" id="box_like">
                            <div>
                                <div class="fw-bold text-success mb-2" style="font-size: 0.9rem;">
                                    <i class="bi bi-hand-thumbs-up-fill me-1"></i> Bukti Like
                                </div>
                                @if($laporan->bukti_like)
                                    <div class="mb-2" id="current_wrap_like">
                                        <img src="{{ asset('storage/' . $laporan->bukti_like) }}" alt="Bukti Like Saat Ini" class="rounded-3 shadow-sm border" style="max-height: 140px; max-width: 100%; object-fit: contain;">
                                        <div class="badge bg-success-subtle text-success border border-success-subtle rounded-pill mt-1" style="font-size: 0.7rem;">Screenshot Tersimpan</div>
                                    </div>
                                @endif
                                <div id="preview_wrap_like" class="d-none mb-2">
                                    <img id="preview_img_like" src="" alt="Preview Like Baru" class="rounded-3 shadow-sm border" style="max-height: 140px; max-width: 100%; object-fit: contain;">
                                    <div class="badge bg-warning-subtle text-warning-emphasis border rounded-pill mt-1" style="font-size: 0.7rem;">File Baru Dipilih</div>
                                </div>
                            </div>
                            <div class="mt-2">
                                <label class="btn btn-sm btn-outline-primary rounded-3 w-100 mb-1" for="file_like" style="cursor: pointer;">
                                    <i class="bi bi-arrow-repeat me-1"></i> {{ $laporan->bukti_like ? 'Ganti Screenshot' : 'Upload Screenshot' }}
                                </label>
                                <input type="file" id="file_like" name="bukti_like" class="d-none" accept="image/*" onchange="previewUpload(this, 'like')">
                                <div id="file_name_like" class="text-muted text-truncate small" style="font-size: 0.75rem;">Tidak ada file baru</div>
                            </div>
                        </div>
                    </div>

                    {{-- 2. Bukti Komen --}}
                    <div class="col-12 col-md-4">
                        <div class="border rounded-4 p-3 text-center bg-light position-relative h-100 d-flex flex-column justify-content-between" id="box_komen">
                            <div>
                                <div class="fw-bold text-info mb-2" style="font-size: 0.9rem;">
                                    <i class="bi bi-chat-left-text-fill me-1"></i> Bukti Komen
                                </div>
                                @if($laporan->bukti_komen)
                                    <div class="mb-2" id="current_wrap_komen">
                                        <img src="{{ asset('storage/' . $laporan->bukti_komen) }}" alt="Bukti Komen Saat Ini" class="rounded-3 shadow-sm border" style="max-height: 140px; max-width: 100%; object-fit: contain;">
                                        <div class="badge bg-success-subtle text-success border border-success-subtle rounded-pill mt-1" style="font-size: 0.7rem;">Screenshot Tersimpan</div>
                                    </div>
                                @endif
                                <div id="preview_wrap_komen" class="d-none mb-2">
                                    <img id="preview_img_komen" src="" alt="Preview Komen Baru" class="rounded-3 shadow-sm border" style="max-height: 140px; max-width: 100%; object-fit: contain;">
                                    <div class="badge bg-warning-subtle text-warning-emphasis border rounded-pill mt-1" style="font-size: 0.7rem;">File Baru Dipilih</div>
                                </div>
                            </div>
                            <div class="mt-2">
                                <label class="btn btn-sm btn-outline-primary rounded-3 w-100 mb-1" for="file_komen" style="cursor: pointer;">
                                    <i class="bi bi-arrow-repeat me-1"></i> {{ $laporan->bukti_komen ? 'Ganti Screenshot' : 'Upload Screenshot' }}
                                </label>
                                <input type="file" id="file_komen" name="bukti_komen" class="d-none" accept="image/*" onchange="previewUpload(this, 'komen')">
                                <div id="file_name_komen" class="text-muted text-truncate small" style="font-size: 0.75rem;">Tidak ada file baru</div>
                            </div>
                        </div>
                    </div>

                    {{-- 3. Bukti Share --}}
                    <div class="col-12 col-md-4">
                        <div class="border rounded-4 p-3 text-center bg-light position-relative h-100 d-flex flex-column justify-content-between" id="box_share">
                            <div>
                                <div class="fw-bold text-primary mb-2" style="font-size: 0.9rem;">
                                    <i class="bi bi-share-fill me-1"></i> Bukti Share
                                </div>
                                @if($laporan->bukti_share)
                                    <div class="mb-2" id="current_wrap_share">
                                        <img src="{{ asset('storage/' . $laporan->bukti_share) }}" alt="Bukti Share Saat Ini" class="rounded-3 shadow-sm border" style="max-height: 140px; max-width: 100%; object-fit: contain;">
                                        <div class="badge bg-success-subtle text-success border border-success-subtle rounded-pill mt-1" style="font-size: 0.7rem;">Screenshot Tersimpan</div>
                                    </div>
                                @endif
                                <div id="preview_wrap_share" class="d-none mb-2">
                                    <img id="preview_img_share" src="" alt="Preview Share Baru" class="rounded-3 shadow-sm border" style="max-height: 140px; max-width: 100%; object-fit: contain;">
                                    <div class="badge bg-warning-subtle text-warning-emphasis border rounded-pill mt-1" style="font-size: 0.7rem;">File Baru Dipilih</div>
                                </div>
                            </div>
                            <div class="mt-2">
                                <label class="btn btn-sm btn-outline-primary rounded-3 w-100 mb-1" for="file_share" style="cursor: pointer;">
                                    <i class="bi bi-arrow-repeat me-1"></i> {{ $laporan->bukti_share ? 'Ganti Screenshot' : 'Upload Screenshot' }}
                                </label>
                                <input type="file" id="file_share" name="bukti_share" class="d-none" accept="image/*" onchange="previewUpload(this, 'share')">
                                <div id="file_name_share" class="text-muted text-truncate small" style="font-size: 0.75rem;">Tidak ada file baru</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Keterangan Tambahan --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Keterangan Tambahan (Opsional)</label>
                    <textarea name="keterangan" rows="3" class="form-control" placeholder="Tambahkan keterangan perbaikan jika diperlukan...">{{ old('keterangan', $laporan->keterangan) }}</textarea>
                </div>

                <div class="d-flex gap-2 flex-column flex-sm-row justify-content-end pt-2 border-top">
                    <a href="{{ route('user.riwayat.index') }}" class="btn btn-outline-secondary rounded-4 px-4 w-100 w-sm-auto order-2 order-sm-1 text-center">
                        <i class="bi bi-arrow-left me-1"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-bi fw-bold rounded-4 px-4 w-100 w-sm-auto order-1 order-sm-2">
                        <i class="bi bi-check-circle-fill me-1"></i> Simpan Perubahan Laporan
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
    function previewUpload(input, type) {
        const file = input.files[0];
        const previewWrap = document.getElementById('preview_wrap_' + type);
        const previewImg = document.getElementById('preview_img_' + type);
        const currentWrap = document.getElementById('current_wrap_' + type);
        const nameLabel = document.getElementById('file_name_' + type);

        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewWrap.classList.remove('d-none');
                if (currentWrap) currentWrap.classList.add('d-none');
                if (nameLabel) nameLabel.textContent = file.name;
            };
            reader.readAsDataURL(file);
        } else {
            previewWrap.classList.add('d-none');
            if (currentWrap) currentWrap.classList.remove('d-none');
            if (nameLabel) nameLabel.textContent = 'Tidak ada file baru';
        }
    }
</script>
@endsection
