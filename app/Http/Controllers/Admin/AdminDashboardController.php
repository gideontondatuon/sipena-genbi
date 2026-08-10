<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Laporan;
use App\Models\TargetHarian;
use App\Models\AkunInstagram;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalAnggota = User::where('role', 'anggota')->where('status', 'aktif')->count();
        $laporanMasuk = Laporan::count();
        $menungguValidasi = Laporan::where('status', 'menunggu')->count();
        $ditolak = Laporan::where('status', 'ditolak')->count();

        // Calculate completeness
        $anggotaList = User::where('role', 'anggota')->get();
        $totalTarget = TargetHarian::sum('jumlah_target');
        
        $uploadCounts = Laporan::selectRaw('user_id, count(*) as total')
            ->groupBy('user_id')
            ->pluck('total', 'user_id');

        $lengkapCount = 0;
        $belumLengkapCount = 0;
        $belumUploadCount = 0;
        $targetThreshold = $totalTarget > 0 ? $totalTarget : 1;

        foreach ($anggotaList as $anggota) {
            $userUpload = $uploadCounts[$anggota->id] ?? 0;
            if ($userUpload == 0) {
                $belumUploadCount++;
            } elseif ($userUpload >= $targetThreshold) {
                $lengkapCount++;
            } else {
                $belumLengkapCount++;
            }
        }

        $targetsToday = TargetHarian::with('akunInstagram')
            ->whereDate('tanggal', '>=', now()->format('Y-m-d'))
            ->get();

        return view('admin.dashboard', compact(
            'totalAnggota',
            'laporanMasuk',
            'menungguValidasi',
            'ditolak',
            'lengkapCount',
            'belumLengkapCount',
            'belumUploadCount',
            'targetsToday'
        ));
    }
}
