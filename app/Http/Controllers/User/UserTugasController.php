<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\TargetHarian;
use App\Models\Laporan;
use Illuminate\Http\Request;

class UserTugasController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $reportMap = Laporan::where('user_id', $userId)
            ->selectRaw('akun_instagram_id, DATE(tanggal_postingan) as tgl, COUNT(*) as total')
            ->groupBy('akun_instagram_id', 'tgl')
            ->get()
            ->keyBy(fn($item) => $item->akun_instagram_id . '_' . $item->tgl);

        $tugasList = TargetHarian::with('akunInstagram')->latest()->get()->map(function($target) use ($reportMap) {
            $tglKey = $target->tanggal ? $target->tanggal->format('Y-m-d') : null;
            $mapKey = $target->akun_instagram_id . '_' . $tglKey;
            $uploadedCount = isset($reportMap[$mapKey]) ? (int) $reportMap[$mapKey]->total : 0;

            $kekurangan = max(0, $target->jumlah_target - $uploadedCount);

            if ($uploadedCount >= $target->jumlah_target) {
                $status = 'Lengkap';
                $badgeClass = 'bg-success-subtle text-success';
            } elseif ($uploadedCount > 0) {
                $status = 'Belum Lengkap';
                $badgeClass = 'bg-warning-subtle text-warning';
            } else {
                $status = 'Belum Upload';
                $badgeClass = 'bg-danger-subtle text-danger';
            }

            return (object) [
                'id' => $target->id,
                'akun' => $target->akunInstagram->nama_akun ?? '-',
                'akun_id' => $target->akun_instagram_id,
                'tanggal' => $target->tanggal->format('d F Y'),
                'tanggal_raw' => $target->tanggal->format('Y-m-d'),
                'target' => $target->jumlah_target,
                'upload' => $uploadedCount,
                'kekurangan' => $kekurangan,
                'deadline' => $target->deadline ? $target->deadline->format('d F Y H:i') : '-',
                'status' => $status,
                'badgeClass' => $badgeClass,
            ];
        });

        return view('user.tugas', compact('tugasList'));
    }
}
