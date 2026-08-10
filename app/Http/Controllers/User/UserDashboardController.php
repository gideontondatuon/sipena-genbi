<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\TargetHarian;
use App\Models\Laporan;
use App\Models\AkunInstagram;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $totalTarget = TargetHarian::sum('jumlah_target');
        $userLaporans = Laporan::where('user_id', $userId)->get();

        $sudahUpload = $userLaporans->count();
        $laporanValid = $userLaporans->where('status', 'valid')->count();
        $kekurangan = max(0, $totalTarget - $sudahUpload);

        // Pre-fetch report counts grouped by account and date
        $reportMap = Laporan::where('user_id', $userId)
            ->selectRaw('akun_instagram_id, DATE(tanggal_postingan) as tgl, COUNT(*) as total')
            ->groupBy('akun_instagram_id', 'tgl')
            ->get()
            ->keyBy(fn($item) => $item->akun_instagram_id . '_' . $item->tgl);

        // Group status by target harian
        $targets = TargetHarian::with('akunInstagram')->latest()->get()->map(function($target) use ($reportMap) {
            $tglKey = $target->tanggal ? $target->tanggal->format('Y-m-d') : null;
            $mapKey = $target->akun_instagram_id . '_' . $tglKey;
            $uploadedCount = isset($reportMap[$mapKey]) ? (int) $reportMap[$mapKey]->total : 0;

            if ($uploadedCount >= $target->jumlah_target) {
                $status = 'Lengkap';
                $badgeClass = 'bg-success-subtle text-success';
            } elseif ($uploadedCount > 0) {
                $status = 'Kurang ' . ($target->jumlah_target - $uploadedCount);
                $badgeClass = 'bg-warning-subtle text-warning';
            } else {
                $status = 'Belum Upload';
                $badgeClass = 'bg-danger-subtle text-danger';
            }

            return (object) [
                'akun' => $target->akunInstagram->nama_akun ?? '-',
                'tanggal' => $target->tanggal->format('d F Y'),
                'target' => $target->jumlah_target,
                'upload' => $uploadedCount,
                'status' => $status,
                'badgeClass' => $badgeClass,
            ];
        });

        return view('user.dashboard', compact('totalTarget', 'sudahUpload', 'laporanValid', 'kekurangan', 'targets'));
    }
}
