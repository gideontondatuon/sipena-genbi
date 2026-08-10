@extends('layouts.app')

@section('title', 'Validasi Laporan')
@section('subtitle', 'Periksa bukti screenshot like, komen, dan share anggota')

@section('content')
<style>
    .bulk-action-bar {
        background: #002B66;
        color: #FFFFFF;
        border-radius: 16px;
        padding: 1rem 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 43, 102, 0.2);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .preset-btn {
        font-size: 0.72rem;
        padding: 2px 8px;
        border-radius: 50px;
        border: 1px solid #CBD5E1;
        background: #FFFFFF;
        color: #475569;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .preset-btn:hover {
        background: #F1F5F9;
        color: #002B66;
        border-color: #002B66;
    }
    @media (max-width: 767.98px) {
        .bulk-action-bar {
            padding: 0.85rem 1rem;
            flex-direction: column;
            align-items: stretch;
        }
        .bulk-action-bar .d-flex {
            width: 100%;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .bulk-action-bar input[name="catatan_admin"] {
            width: 100% !important;
            margin-bottom: 6px;
        }
    }
</style>

<div class="content-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h5 class="fw-bold mb-1"><i class="bi bi-patch-check-fill me-2 text-primary"></i>Daftar Validasi Laporan</h5>
            <small class="text-muted">Periksa keabsahan bukti screenshot dan gunakan validasi masal untuk mempercepat verifikasi.</small>
        </div>
    </div>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('admin.validasi.index') }}" class="row g-3 mb-4">
        <div class="col-md-3">
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="Menunggu Validasi" {{ request('status') === 'Menunggu Validasi' || !request('status') ? 'selected' : '' }}>Menunggu Validasi</option>
                <option value="Valid" {{ request('status') === 'Valid' ? 'selected' : '' }}>Valid</option>
                <option value="Ditolak" {{ request('status') === 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                <option value="Perlu Perbaikan" {{ request('status') === 'Perlu Perbaikan' ? 'selected' : '' }}>Perlu Perbaikan</option>
            </select>
        </div>

        <div class="col-md-3">
            <select name="akun_id" class="form-select" onchange="this.form.submit()">
                <option value="Semua Akun">Semua Akun</option>
                @foreach($akunList as $akun)
                    <option value="{{ $akun->id }}" {{ request('akun_id') == $akun->id ? 'selected' : '' }}>{{ $akun->nama_akun }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}" onchange="this.form.submit()">
        </div>

        <div class="col-md-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari nama anggota..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
            </div>
        </div>
    </form>

    <!-- Bulk Action Form Wrapper -->
    <form action="{{ route('admin.validasi.bulk') }}" method="POST" id="bulkForm">
        @csrf

        <!-- Sticky Bulk Toolbar -->
        @if($laporans->count() > 0)
            <div class="bulk-action-bar mb-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="form-check mb-0">
                        <input class="form-check-input" type="checkbox" id="selectAllCheckboxes" onchange="toggleSelectAll(this)" style="cursor: pointer; transform: scale(1.2);">
                        <label class="form-check-label fw-bold text-white mb-0" for="selectAllCheckboxes" style="cursor: pointer;">
                            Pilih Semua (<span id="selectedCount">0</span>/{{ $laporans->count() }})
                        </label>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <input type="text" name="catatan_admin" class="form-control form-control-sm border-0 rounded-3" placeholder="Catatan masal (opsional)..." style="width: 240px;">
                    <button type="submit" name="status" value="valid" class="btn btn-success btn-sm rounded-4 fw-bold"><i class="bi bi-check-all me-1"></i> Validasi Masal</button>
                    <button type="submit" name="status" value="ditolak" class="btn btn-danger btn-sm rounded-4 fw-bold"><i class="bi bi-x-circle me-1"></i> Tolak Masal</button>
                </div>
            </div>
        @endif

        <div class="row g-4">
            @forelse($laporans as $laporan)
                <div class="col-lg-4">
                    <div class="border rounded-4 p-3 bg-light h-100 d-flex flex-column justify-content-between position-relative">
                        <div>
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <input type="checkbox" name="laporan_ids[]" value="{{ $laporan->id }}" class="form-check-input item-checkbox" onchange="updateSelectedCount()" style="transform: scale(1.1); cursor: pointer;">
                                    <div>
                                        <h6 class="fw-bold mb-0 text-dark">{{ $laporan->user->name ?? 'Anggota' }}</h6>
                                        <small class="text-muted" style="font-size: 0.78rem;">{{ $laporan->akunInstagram->nama_akun ?? '-' }} • {{ $laporan->tanggal_postingan->format('d M Y') }}</small>
                                    </div>
                                </div>

                                @if($laporan->status === 'valid')
                                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1"><i class="bi bi-check-circle-fill me-1"></i> Valid</span>
                                @elseif($laporan->status === 'ditolak')
                                    <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-1"><i class="bi bi-x-circle-fill me-1"></i> Ditolak</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-1"><i class="bi bi-clock-history me-1"></i> Menunggu</span>
                                @endif
                            </div>

                            <!-- Screenshots Display -->
                            <div class="row g-2 mb-3">
                                <div class="col-4">
                                    <div class="bg-white border rounded-4 p-2 text-center">
                                        <div class="text-primary fs-3"><i class="bi bi-hand-thumbs-up-fill"></i></div>
                                        <small class="fw-semibold d-block">Like</small>
                                        @if($laporan->bukti_like)
                                            <a href="{{ asset('storage/' . $laporan->bukti_like) }}" target="_blank" class="badge bg-primary text-decoration-none mt-1">Lihat Foto</a>
                                        @else
                                            <small class="text-muted fs-7">Tanpa foto</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="bg-white border rounded-4 p-2 text-center">
                                        <div class="text-primary fs-3"><i class="bi bi-chat-left-text-fill"></i></div>
                                        <small class="fw-semibold d-block">Komen</small>
                                        @if($laporan->bukti_komen)
                                            <a href="{{ asset('storage/' . $laporan->bukti_komen) }}" target="_blank" class="badge bg-primary text-decoration-none mt-1">Lihat Foto</a>
                                        @else
                                            <small class="text-muted fs-7">Tanpa foto</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="bg-white border rounded-4 p-2 text-center">
                                        <div class="text-primary fs-3"><i class="bi bi-share-fill"></i></div>
                                        <small class="fw-semibold d-block">Share</small>
                                        @if($laporan->bukti_share)
                                            <a href="{{ asset('storage/' . $laporan->bukti_share) }}" target="_blank" class="badge bg-primary text-decoration-none mt-1">Lihat Foto</a>
                                        @else
                                            <small class="text-muted fs-7">Tanpa foto</small>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if($laporan->judul_postingan)
                                <div class="p-2 mb-2 bg-light border rounded-3 small">
                                    <strong class="text-primary"><i class="bi bi-file-text me-1"></i> Topik Postingan:</strong> {{ $laporan->judul_postingan }}
                                </div>
                            @endif

                            @if($laporan->link_postingan)
                                <div class="p-2 mb-2 bg-primary-subtle border border-primary-subtle rounded-3 small d-flex justify-content-between align-items-center">
                                    <span><i class="bi bi-instagram text-primary me-1"></i> <strong>Link Post:</strong></span>
                                    <a href="{{ $laporan->link_postingan }}" target="_blank" class="text-primary text-decoration-underline fw-semibold">Buka Postingan IG <i class="bi bi-box-arrow-up-right fs-7"></i></a>
                                </div>
                            @endif

                            @if($laporan->keterangan)
                                <div class="p-2 mb-3 bg-white rounded-3 small border">
                                    <strong>Ket. Anggota:</strong> {{ $laporan->keterangan }}
                                </div>
                            @endif
                        </div>

                        <!-- Individual Validation Form -->
                        <div class="pt-2 border-top">
                            <!-- Quick Reject Presets Buttons -->
                            <div class="mb-2">
                                <small class="text-muted d-block fw-semibold mb-1" style="font-size: 0.7rem;">Preset Alasan Cepat:</small>
                                <div class="d-flex flex-wrap gap-1">
                                    <span class="preset-btn" onclick="applyPreset('{{ $laporan->id }}', 'Bukti Like tidak terlihat / tidak valid')">Like Tidak Valid</span>
                                    <span class="preset-btn" onclick="applyPreset('{{ $laporan->id }}', 'Bukti Komentar tidak ditemukan')">Komen Hilang</span>
                                    <span class="preset-btn" onclick="applyPreset('{{ $laporan->id }}', 'Gambar screenshot buram / tidak terbaca')">Gambar Buram</span>
                                    <span class="preset-btn" onclick="applyPreset('{{ $laporan->id }}', 'Akun / tanggal postingan tidak sesuai')">Akun Salah</span>
                                </div>
                            </div>

                            <textarea name="catatan_admin_single" id="catatan_{{ $laporan->id }}" class="form-control mb-3 small" rows="2" placeholder="Catatan admin jika ditolak/perlu perbaikan...">{{ $laporan->catatan_admin }}</textarea>

                            <div class="d-flex gap-2">
                                <button type="button" onclick="submitSingleValidasi('{{ $laporan->id }}', 'valid')" class="btn btn-success btn-sm rounded-4 w-100 fw-bold"><i class="bi bi-check-lg me-1"></i> Valid</button>
                                <button type="button" onclick="submitSingleValidasi('{{ $laporan->id }}', 'ditolak')" class="btn btn-danger btn-sm rounded-4 w-100 fw-bold"><i class="bi bi-x-lg me-1"></i> Tolak</button>
                            </div>

                            <a href="{{ route('admin.preview-laporan', ['user_id' => $laporan->user_id]) }}" class="btn btn-outline-primary btn-sm rounded-4 w-100 mt-2">
                                <i class="bi bi-eye-fill me-1"></i> Preview Detail Anggota
                            </a>

                            <button type="button" onclick="confirmDeleteLaporan('{{ route('admin.validasi.destroy', $laporan->id) }}')" class="btn btn-outline-danger btn-sm rounded-4 w-100 mt-1 fw-semibold">
                                <i class="bi bi-trash3-fill me-1"></i> Hapus Laporan & Foto
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5 text-muted">
                    <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                    Tidak ada laporan yang sesuai dengan filter pencarian.
                </div>
            @endforelse
        </div>
    </form>
</div>

<!-- Hidden Single Form for Individual Validation Submit -->
<form id="singleValidasiForm" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="status" id="singleStatus">
    <input type="hidden" name="catatan_admin" id="singleCatatanAdmin">
</form>

<!-- Hidden Form for Admin Report Deletion -->
<form id="deleteLaporanForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@section('scripts')
<script>
    function toggleSelectAll(masterCheckbox) {
        const checkboxes = document.querySelectorAll('.item-checkbox');
        checkboxes.forEach(cb => cb.checked = masterCheckbox.checked);
        updateSelectedCount();
    }

    function updateSelectedCount() {
        const checked = document.querySelectorAll('.item-checkbox:checked');
        const countSpan = document.getElementById('selectedCount');
        if (countSpan) countSpan.textContent = checked.length;
    }

    function applyPreset(laporanId, text) {
        const textarea = document.getElementById('catatan_' + laporanId);
        if (textarea) {
            textarea.value = text;
        }
    }

    function submitSingleValidasi(laporanId, status) {
        const form = document.getElementById('singleValidasiForm');
        const catatan = document.getElementById('catatan_' + laporanId).value;

        form.action = "{{ url('admin/validasi') }}/" + laporanId;
        document.getElementById('singleStatus').value = status;
        document.getElementById('singleCatatanAdmin').value = catatan;
        form.submit();
    }

    function confirmDeleteLaporan(actionUrl) {
        if (confirm('Apakah Anda yakin ingin menghapus postingan laporan ini? File foto bukti screenshot juga akan dihapus permanen dari server.')) {
            const form = document.getElementById('deleteLaporanForm');
            form.action = actionUrl;
            form.submit();
        }
    }
</script>
@endsection
@endsection