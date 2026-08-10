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
                        <label class="form-label fw-semibold">Akun Instagram</label>
                        <select name="akun_instagram_id" class="form-select" required>
                            <option value="">Pilih akun Instagram</option>
                            @foreach($akunList as $akun)
                                <option value="{{ $akun->id }}" {{ isset($selectedAkunId) && $selectedAkunId == $akun->id ? 'selected' : '' }}>
                                    {{ $akun->nama_akun }} ({{ $akun->username }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tanggal Postingan</label>
                        <input type="date" name="tanggal_postingan" class="form-control" value="{{ $selectedTanggal ?? date('Y-m-d') }}" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Link / URL Postingan Instagram (Opsional)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-primary"><i class="bi bi-link-45deg fs-5"></i></span>
                        <input type="url" name="link_postingan" id="linkPostinganInput" class="form-control" placeholder="Contoh: https://www.instagram.com/p/C3x9abc123/">
                        <button type="button" class="btn btn-outline-primary" id="btnDetectInfo" onclick="detectInstagramTitle()"><i class="bi bi-magic me-1"></i> Deteksi Judul</button>
                    </div>
                    <small id="linkDetectStatus" class="text-muted mt-1 d-block">Tempelkan link postingan untuk mendeteksi topik/judul postingan secara otomatis.</small>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Judul / Kalimat Pertama Postingan Instagram</label>
                    <input type="text" name="judul_postingan" id="judulPostinganInput" class="form-control" placeholder="Contoh: 🚨 WASPADA HOAKS! 🚨 (Salin kalimat pertama postingan)">
                    <small class="text-muted mt-1 d-block">Buka postingan Instagram, lalu salin (copy) kalimat pertama atau judul caption di sini agar tercetak di laporan.</small>
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

@section('scripts')
<script>
    function detectInstagramTitle() {
        const linkInput = document.getElementById('linkPostinganInput');
        const link = linkInput ? linkInput.value.trim() : '';
        const statusEl = document.getElementById('linkDetectStatus');
        const titleInput = document.getElementById('judulPostinganInput');

        if (!link) {
            if (statusEl) statusEl.innerHTML = '<span class="text-danger">Masukkan link postingan Instagram terlebih dahulu.</span>';
            return;
        }

        if (statusEl) statusEl.innerHTML = '<span class="text-primary"><i class="bi bi-hourglass-split me-1"></i> Mendeteksi informasi postingan...</span>';

        fetch("{{ route('user.laporan.fetch-ig-info') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ link: link })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success && data.title) {
                if (titleInput) titleInput.value = data.title;
                if (statusEl) statusEl.innerHTML = '<span class="text-success"><i class="bi bi-check-circle-fill me-1"></i> Berhasil mendeteksi topik postingan!</span>';
            } else {
                if (statusEl) statusEl.innerHTML = '<span class="text-muted">Gagal mendeteksi otomatis. Silakan ketik topik postingan secara manual.</span>';
            }
        })
        .catch(() => {
            if (statusEl) statusEl.innerHTML = '<span class="text-muted">Sistem siap. Silakan ketik topik postingan secara manual.</span>';
        });
    }

    document.getElementById('linkPostinganInput')?.addEventListener('blur', function() {
        if (this.value.trim() && !document.getElementById('judulPostinganInput').value.trim()) {
            detectInstagramTitle();
        }
    });
</script>
@endsection
@endsection