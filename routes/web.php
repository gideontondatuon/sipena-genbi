<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminAnggotaController;
use App\Http\Controllers\Admin\AdminAkunInstagramController;
use App\Http\Controllers\Admin\AdminPeriodeController;
use App\Http\Controllers\Admin\AdminTargetHarianController;
use App\Http\Controllers\Admin\AdminValidasiController;
use App\Http\Controllers\Admin\AdminRekapController;
use App\Http\Controllers\Admin\AdminExportController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\User\UserTugasController;
use App\Http\Controllers\User\UserLaporanController;
use App\Http\Controllers\User\UserRiwayatController;
use Illuminate\Support\Facades\Route;

// Public Landing Page
Route::get('/', function () {
    return view('welcome');
});

// Auth Routes (Breeze)
require __DIR__.'/auth.php';

// Dashboard Dispatcher based on Role
Route::get('/dashboard', function () {
    if (auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('user.dashboard');
})->middleware(['auth'])->name('dashboard');

// Notifikasi Routes
Route::middleware('auth')->group(function () {
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::get('/notifikasi/{id}/open', [NotifikasiController::class, 'markReadAndOpen'])->name('notifikasi.open');
    Route::post('/notifikasi/read-all', [NotifikasiController::class, 'markAllRead'])->name('notifikasi.read-all');
    Route::post('/notifikasi/clear-all', [NotifikasiController::class, 'clearAll'])->name('notifikasi.clear-all');
    Route::delete('/notifikasi/{id}', [NotifikasiController::class, 'destroy'])->name('notifikasi.destroy');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Anggota
    Route::get('/anggota', [AdminAnggotaController::class, 'index'])->name('anggota.index');
    Route::post('/anggota', [AdminAnggotaController::class, 'store'])->name('anggota.store');
    Route::put('/anggota/{user}', [AdminAnggotaController::class, 'update'])->name('anggota.update');
    Route::post('/anggota/{user}/toggle', [AdminAnggotaController::class, 'toggleStatus'])->name('anggota.toggle');
    Route::delete('/anggota/{user}', [AdminAnggotaController::class, 'destroy'])->name('anggota.destroy');

    // Akun Instagram
    Route::get('/akun-instagram', [AdminAkunInstagramController::class, 'index'])->name('akun-instagram.index');
    Route::post('/akun-instagram', [AdminAkunInstagramController::class, 'store'])->name('akun-instagram.store');
    Route::put('/akun-instagram/{akunInstagram}', [AdminAkunInstagramController::class, 'update'])->name('akun-instagram.update');
    Route::delete('/akun-instagram/{akunInstagram}', [AdminAkunInstagramController::class, 'destroy'])->name('akun-instagram.destroy');

    // Periode
    Route::get('/periode', [AdminPeriodeController::class, 'index'])->name('periode.index');
    Route::post('/periode', [AdminPeriodeController::class, 'store'])->name('periode.store');
    Route::put('/periode/{periode}', [AdminPeriodeController::class, 'update'])->name('periode.update');

    // Target Harian
    Route::get('/target-harian', [AdminTargetHarianController::class, 'index'])->name('target-harian.index');
    Route::post('/target-harian', [AdminTargetHarianController::class, 'store'])->name('target-harian.store');
    Route::put('/target-harian/{targetHarian}', [AdminTargetHarianController::class, 'update'])->name('target-harian.update');
    Route::delete('/target-harian/{targetHarian}', [AdminTargetHarianController::class, 'destroy'])->name('target-harian.destroy');

    // Validasi Laporan
    Route::get('/validasi', [AdminValidasiController::class, 'index'])->name('validasi.index');
    Route::post('/validasi/bulk', [AdminValidasiController::class, 'bulkValidasi'])->name('validasi.bulk');
    Route::post('/validasi/{laporan}', [AdminValidasiController::class, 'validasi'])->name('validasi.proses');
    Route::delete('/validasi/{laporan}', [AdminValidasiController::class, 'destroy'])->name('validasi.destroy');

    // Rekap
    Route::get('/rekap', [AdminRekapController::class, 'index'])->name('rekap.index');

    // Export & Preview
    Route::get('/export', [AdminExportController::class, 'index'])->name('export.index');
    Route::get('/export/csv', [AdminExportController::class, 'exportExcelXlsx'])->name('export.csv');
    Route::get('/export/xlsx', [AdminExportController::class, 'exportExcelXlsx'])->name('export.xlsx');
    Route::get('/preview-laporan', [AdminExportController::class, 'previewIndividual'])->name('preview-laporan');
    Route::get('/preview-rekap', [AdminExportController::class, 'previewRekap'])->name('preview-rekap');
});

// User (Anggota) Routes
Route::middleware(['auth', 'anggota'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/tugas', [UserTugasController::class, 'index'])->name('tugas.index');
    Route::get('/tambah-laporan', [UserLaporanController::class, 'create'])->name('laporan.create');
    Route::post('/tambah-laporan', [UserLaporanController::class, 'store'])->name('laporan.store');
    Route::post('/fetch-ig-info', [UserLaporanController::class, 'fetchInstagramInfo'])->name('laporan.fetch-ig-info');
    Route::get('/riwayat', [UserRiwayatController::class, 'index'])->name('riwayat.index');
    Route::get('/preview-laporan', [UserLaporanController::class, 'previewIndividual'])->name('preview-laporan');
    Route::delete('/laporan/{laporan}', [UserLaporanController::class, 'destroy'])->name('laporan.destroy');
});
