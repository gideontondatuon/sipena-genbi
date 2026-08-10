@extends('layouts.app')

@section('title', 'Target Harian')
@section('subtitle', 'Atur jumlah postingan wajib berdasarkan akun dan tanggal')

@section('content')
<div class="row g-4">
    <div class="col-lg-5">
        <div class="content-card">
            <h5 class="fw-bold mb-1">Input Target Postingan</h5>
            <p class="text-muted mb-4">Admin dapat mengatur target harian untuk wajib dipenuhi anggota.</p>

            <form action="{{ route('admin.target-harian.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Akun Instagram</label>
                    <select name="akun_instagram_id" class="form-select" required>
                        <option value="">Pilih akun Instagram</option>
                        @foreach($akunList as $akun)
                            <option value="{{ $akun->id }}">{{ $akun->nama_akun }} ({{ $akun->username }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Tanggal Postingan</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Jumlah Target Postingan</label>
                    <input type="number" name="jumlah_target" class="form-control" placeholder="Contoh: 5" min="1" value="1" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Deadline Upload</label>
                    <input type="datetime-local" name="deadline" class="form-control" value="{{ date('Y-m-d\TH:i', strtotime('+2 days')) }}" required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Keterangan / Instruksi (Opsional)</label>
                    <textarea name="keterangan" class="form-control" rows="2" placeholder="Catatan instruksi untuk anggota"></textarea>
                </div>

                <button type="submit" class="btn btn-bi w-100">Simpan Target</button>
            </form>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="content-card">
            <h5 class="fw-bold mb-3">Target Aktif</h5>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Akun</th>
                            <th>Tanggal</th>
                            <th>Target</th>
                            <th>Deadline</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($targetList as $target)
                            <tr>
                                <td class="fw-semibold">{{ $target->akunInstagram->nama_akun ?? '-' }}</td>
                                <td>{{ $target->tanggal->format('d M Y') }}</td>
                                <td><span class="badge bg-primary rounded-pill px-2 py-1">{{ $target->jumlah_target }}</span></td>
                                <td>{{ $target->deadline ? $target->deadline->format('d M Y H:i') : '-' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary rounded-4 me-1" data-bs-toggle="modal" data-bs-target="#modalEditTarget{{ $target->id }}">Edit</button>
                                    <form action="{{ route('admin.target-harian.destroy', $target->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus target harian ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-4">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal Edit Target -->
                            <div class="modal fade" id="modalEditTarget{{ $target->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content rounded-4 border-0">
                                        <form action="{{ route('admin.target-harian.update', $target->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header border-0 pb-0">
                                                <h5 class="modal-title fw-bold">Edit Target {{ $target->akunInstagram->nama_akun ?? '' }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Akun Instagram</label>
                                                    <select name="akun_instagram_id" class="form-select" required>
                                                        @foreach($akunList as $akun)
                                                            <option value="{{ $akun->id }}" {{ $target->akun_instagram_id == $akun->id ? 'selected' : '' }}>{{ $akun->nama_akun }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Tanggal Postingan</label>
                                                    <input type="date" name="tanggal" class="form-control" value="{{ $target->tanggal->format('Y-m-d') }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Jumlah Target</label>
                                                    <input type="number" name="jumlah_target" class="form-control" value="{{ $target->jumlah_target }}" min="1" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Deadline Upload</label>
                                                    <input type="datetime-local" name="deadline" class="form-control" value="{{ $target->deadline ? $target->deadline->format('Y-m-d\TH:i') : '' }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Keterangan</label>
                                                    <textarea name="keterangan" class="form-control" rows="2">{{ $target->keterangan }}</textarea>
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
                                <td colspan="5" class="text-center py-4 text-muted">Belum ada target harian.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection