@extends('layouts.app')

@section('title', 'Halaman Tidak Ditemukan')
@section('subtitle', 'Maaf, halaman yang Anda cari tidak tersedia')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="content-card text-center py-5">
            <div style="font-size: 72px;">🔎</div>
            <h1 class="fw-bold mt-3" style="color: var(--bi-blue);">404</h1>
            <h5 class="fw-bold">Halaman Tidak Ditemukan</h5>
            <p class="text-muted">
                Halaman yang Anda buka mungkin sudah dipindahkan atau belum tersedia.
            </p>

            <a href="/user/dashboard" class="btn btn-bi mt-3">
                Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>
@endsection