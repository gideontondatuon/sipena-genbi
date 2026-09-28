@extends('layouts.app')

@section('title', 'Upload Laporan')
@section('subtitle', 'Unggah bukti like, komen, dan share Instagram')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="content-card">
            <h5 class="fw-bold mb-1"><i class="bi bi-cloud-arrow-up-fill me-2 text-primary"></i>Form Upload Laporan</h5>
            <p class="text-muted mb-4">Pastikan screenshot sesuai dengan akun dan tanggal postingan.</p>

            <form action="{{ route('user.laporan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Akun Instagram <span class="text-danger">*</span></label>
                        <select name="akun_instagram_id" class="form-select" required>
                            <option value="">Pilih akun Instagram</option>
                            @foreach($akunList as $akun)
                                <option value="{{ $akun->id }}" {{ (old('akun_instagram_id') == $akun->id || (isset($selectedAkunId) && $selectedAkunId == $akun->id)) ? 'selected' : '' }}>
                                    {{ $akun->nama_akun }} ({{ $akun->username }})
                                </option>
                            @endforeach
                        </select>
                        @error('akun_instagram_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tanggal Postingan <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_postingan" class="form-control" value="{{ old('tanggal_postingan', $selectedTanggal ?? date('Y-m-d')) }}" required>
                        @error('tanggal_postingan')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-file-earmark-text text-primary me-1"></i> Topik Postingan <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-primary"><i class="bi bi-pen"></i></span>
                            <input type="text" 
                                   name="topik_postingan" 
                                   id="topik_postingan" 
                                   class="form-control @error('topik_postingan') is-invalid @enderror" 
                                   placeholder="Contoh: Edukasi CBP Rupiah / Sosialisasi QRIS / Pengumuman Beasiswa BI" 
                                   value="{{ old('topik_postingan') }}" 
                                   required>
                        </div>
                        <small class="text-muted d-block mt-1">
                            <i class="bi bi-info-circle me-1"></i>Topik postingan ini akan dicantumkan secara otomatis pada <strong>format cetak laporan</strong>.
                        </small>
                        @error('topik_postingan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

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
                                   value="{{ old('link_postingan') }}">
                        </div>
                        <small class="text-muted d-block mt-1">Tautan URL postingan Instagram (opsional).</small>
                        @error('link_postingan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-12 col-sm-4">
                        <label class="form-label fw-semibold">Bukti Like</label>
                        <div class="border rounded-4 p-3 text-center bg-light">
                            <div class="text-primary mb-2" style="font-size: 36px;"><i class="bi bi-hand-thumbs-up-fill"></i></div>
                            <div class="fw-semibold mb-2" style="font-size: 0.9rem;">Upload Screenshot</div>
                            <input type="file" name="bukti_like" class="form-control form-control-sm" accept="image/*">
                            <small class="text-muted d-block mt-1">JPG/PNG maks 5MB</small>
                        </div>
                    </div>

                    <div class="col-12 col-sm-4">
                        <label class="form-label fw-semibold">Bukti Komen</label>
                        <div class="border rounded-4 p-3 text-center bg-light">
                            <div class="text-primary mb-2" style="font-size: 36px;"><i class="bi bi-chat-left-text-fill"></i></div>
                            <div class="fw-semibold mb-2" style="font-size: 0.9rem;">Upload Screenshot</div>
                            <input type="file" name="bukti_komen" class="form-control form-control-sm" accept="image/*">
                            <small class="text-muted d-block mt-1">JPG/PNG maks 5MB</small>
                        </div>
                    </div>

                    <div class="col-12 col-sm-4">
                        <label class="form-label fw-semibold">Bukti Share</label>
                        <div class="border rounded-4 p-3 text-center bg-light">
                            <div class="text-primary mb-2" style="font-size: 36px;"><i class="bi bi-share-fill"></i></div>
                            <div class="fw-semibold mb-2" style="font-size: 0.9rem;">Upload Screenshot</div>
                            <input type="file" name="bukti_share" class="form-control form-control-sm" accept="image/*">
                            <small class="text-muted d-block mt-1">JPG/PNG maks 5MB</small>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Keterangan Tambahan (Opsional)</label>
                    <textarea name="keterangan" class="form-control" rows="3" placeholder="Tulis keterangan jika diperlukan"></textarea>
                </div>

                <div class="d-flex gap-2 flex-column flex-sm-row">
                    <button type="submit" class="btn btn-bi w-100 w-sm-auto"><i class="bi bi-send-fill me-1"></i> Kirim Laporan</button>
                    <a href="{{ route('user.tugas.index') }}" class="btn btn-outline-secondary rounded-4 w-100 w-sm-auto text-center">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const linkInput = document.getElementById('link_postingan');
        const topikInput = document.getElementById('topik_postingan');

        if (linkInput && topikInput) {
            linkInput.addEventListener('change', function () {
                const url = this.value.trim();
                if (url && !topikInput.value.trim()) {
                    const originalPlaceholder = topikInput.getAttribute('placeholder');
                    topikInput.setAttribute('placeholder', 'Mencoba membaca topik dari link Instagram...');
                    
                    fetch('{{ route("user.laporan.fetch-ig-info") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ link: url })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success && data.title && !topikInput.value.trim()) {
                            topikInput.value = data.title;
                        }
                    })
                    .catch(() => {})
                    .finally(() => {
                        topikInput.setAttribute('placeholder', originalPlaceholder);
                    });
                }
            });
        }
    });
</script>
@endsection