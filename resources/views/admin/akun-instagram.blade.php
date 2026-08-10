@extends('layouts.app')

@section('title', 'Akun Instagram')
@section('subtitle', 'Kelola daftar akun Instagram yang menjadi target laporan')

@section('content')
<div class="row g-4">
    <div class="col-lg-5">
        <div class="content-card">
            <h5 class="fw-bold mb-1">Tambah Akun Instagram</h5>
            <p class="text-muted mb-4">Masukkan akun Instagram yang wajib dipantau oleh anggota.</p>

            <form action="{{ route('admin.akun-instagram.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Akun</label>
                    <input type="text" name="nama_akun" class="form-control" placeholder="Contoh: BI Sulut" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Username Instagram</label>
                    <input type="text" name="username" class="form-control" placeholder="Contoh: @bi_sulut" required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="3" placeholder="Keterangan akun"></textarea>
                </div>

                <button type="submit" class="btn btn-bi w-100">Simpan Akun</button>
            </form>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="content-card">
            <h5 class="fw-bold mb-3">Daftar Akun Instagram</h5>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Nama Akun</th>
                            <th>Username</th>
                            <th>Status</th>
                            <th>Total Target</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($akunList as $akun)
                            <tr>
                                <td class="fw-semibold">{{ $akun->nama_akun }}</td>
                                <td>{{ $akun->username }}</td>
                                <td>
                                    @if($akun->status === 'aktif')
                                        <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2">Nonaktif</span>
                                    @endif
                                </td>
                                <td>{{ $akun->target_harians_count }}</td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <button class="btn btn-sm btn-outline-primary rounded-4" data-bs-toggle="modal" data-bs-target="#modalEditAkun{{ $akun->id }}"><i class="bi bi-pencil-square me-1"></i> Edit</button>
                                        <form action="{{ route('admin.akun-instagram.destroy', $akun->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun Instagram {{ $akun->nama_akun }} ({{ $akun->username }})?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-4"><i class="bi bi-trash-fill me-1"></i> Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Edit Akun IG -->
                            <div class="modal fade" id="modalEditAkun{{ $akun->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content rounded-4 border-0">
                                        <form action="{{ route('admin.akun-instagram.update', $akun->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header border-0 pb-0">
                                                <h5 class="modal-title fw-bold">Edit Akun {{ $akun->nama_akun }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Nama Akun</label>
                                                    <input type="text" name="nama_akun" class="form-control" value="{{ $akun->nama_akun }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Username Instagram</label>
                                                    <input type="text" name="username" class="form-control" value="{{ $akun->username }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Status</label>
                                                    <select name="status" class="form-select">
                                                        <option value="aktif" {{ $akun->status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                                        <option value="nonaktif" {{ $akun->status === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Keterangan</label>
                                                    <textarea name="keterangan" class="form-control" rows="3">{{ $akun->keterangan }}</textarea>
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
                                <td colspan="5" class="text-center py-4 text-muted">Belum ada akun Instagram target.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection