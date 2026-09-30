@extends('layouts.app')

@section('title', 'Data Anggota')
@section('subtitle', 'Kelola data anggota GenBI Komisariat Polimdo')

@section('content')
<div class="content-card">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h5 class="fw-bold mb-1">Daftar Anggota</h5>
            <small class="text-muted">Data pengguna yang dapat mengunggah laporan</small>
        </div>
        <button class="btn btn-bi w-100 w-sm-auto" data-bs-toggle="modal" data-bs-target="#modalTambahAnggota">+ Tambah Anggota</button>
    </div>

    <form method="GET" action="{{ route('admin.anggota.index') }}" class="row g-2 g-md-3 mb-4">
        <div class="col-12 col-md-5">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama, username, email, NIM..." value="{{ request('search') }}">
        </div>
        <div class="col-7 col-md-4">
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="Semua Status">Semua Status</option>
                <option value="Aktif" {{ request('status') === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="Nonaktif" {{ request('status') === 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>
        <div class="col-5 col-md-3">
            <button type="submit" class="btn btn-outline-secondary btn-sm w-100"><i class="bi bi-search me-1"></i> Cari</button>
        </div>
    </form>

    <div class="d-md-none text-muted small mb-2">
        <i class="bi bi-arrow-left-right me-1 text-primary"></i>Geser ke samping untuk melihat kelola data anggota
    </div>

    <div class="table-responsive">
        <table class="table align-middle" style="min-width: 640px;">
            <thead>
                <tr>
                    <th>Nama & Username</th>
                    <th>Email / NIM</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Total Laporan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($anggotaList as $user)
                    <tr>
                        <td class="fw-semibold">
                            <div>{{ $user->name }}</div>
                            @if($user->username)
                                <small class="text-primary fw-medium"><i class="bi bi-at"></i>{{ $user->username }}</small>
                            @endif
                        </td>
                        <td>
                            <div>{{ $user->email }}</div>
                            @if($user->nim)<small class="text-muted">NIM: {{ $user->nim }}</small>@endif
                        </td>
                        <td>
                            <span class="badge {{ $user->role === 'admin' ? 'bg-primary' : 'bg-secondary' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td>
                            @if($user->status === 'aktif')
                                <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">Aktif</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2">Nonaktif</span>
                            @endif
                        </td>
                        <td>{{ $user->laporans()->count() }} Laporan</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary rounded-4 me-1" data-bs-toggle="modal" data-bs-target="#modalEditAnggota{{ $user->id }}">
                                Edit
                            </button>
                            <form action="{{ route('admin.anggota.toggle', $user->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $user->status === 'aktif' ? 'btn-outline-warning' : 'btn-outline-success' }} rounded-4 me-1">
                                    {{ $user->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                            @if(auth()->id() !== $user->id)
                                <form action="{{ route('admin.anggota.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus anggota {{ $user->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-4">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>

                    <!-- Modal Edit Anggota -->
                    <div class="modal fade" id="modalEditAnggota{{ $user->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content rounded-4 border-0">
                                <form action="{{ route('admin.anggota.update', $user->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header border-0 pb-0">
                                        <h5 class="modal-title fw-bold">Edit Data {{ $user->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Nama Lengkap</label>
                                            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Username</label>
                                            <input type="text" name="username" class="form-control" value="{{ $user->username }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Email</label>
                                            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">NIM (Opsional)</label>
                                            <input type="text" name="nim" class="form-control" value="{{ $user->nim }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Nomor HP</label>
                                            <input type="text" name="phone" class="form-control" value="{{ $user->phone }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Role</label>
                                            <select name="role" class="form-select">
                                                <option value="anggota" {{ $user->role === 'anggota' ? 'selected' : '' }}>Anggota</option>
                                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Status</label>
                                            <select name="status" class="form-select">
                                                <option value="aktif" {{ $user->status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                                <option value="nonaktif" {{ $user->status === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Password Baru (Kosongkan jika tidak diubah)</label>
                                            <input type="password" name="password" class="form-control">
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
                        <td colspan="6" class="text-center py-4 text-muted">Tidak ada data anggota.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Anggota -->
<div class="modal fade" id="modalTambahAnggota" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 border-0">
            <form action="{{ route('admin.anggota.store') }}" method="POST">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Tambah Anggota Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" placeholder="Nama anggota" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username <small class="text-muted fw-normal">(Opsional, otomatis dari email jika kosong)</small></label>
                        <input type="text" name="username" class="form-control" placeholder="contoh: anggota123">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="email@domain.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">NIM (Opsional)</label>
                        <input type="text" name="nim" class="form-control" placeholder="2102xxxx">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nomor HP</label>
                        <input type="text" name="phone" class="form-control" placeholder="08xxxxxxxxxx">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Role</label>
                        <select name="role" class="form-select">
                            <option value="anggota">Anggota</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary rounded-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-bi">Simpan Anggota</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection