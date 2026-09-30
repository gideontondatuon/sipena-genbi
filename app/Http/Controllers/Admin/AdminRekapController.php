<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Periode;
use App\Models\Laporan;
use App\Models\TargetHarian;
use Illuminate\Http\Request;

class AdminRekapController extends Controller
{
    public function index(Request $request)
    {
        $tanggalMulai   = $request->get('tanggal_mulai', date('Y-m-01'));
        $tanggalSelesai = $request->get('tanggal_selesai', date('Y-m-t'));
        $filterAktif    = $request->get('filter_aktif', 'aktif'); // aktif|nonaktif|semua

        $query = User::where('role', 'anggota');
        if ($filterAktif !== 'semua') {
            $query->where('status', $filterAktif);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $query->with(['laporans' => function($q) use ($tanggalMulai, $tanggalSelesai) {
            $q->whereBetween('tanggal_postingan', [$tanggalMulai, $tanggalSelesai]);
        }]);

        $totalTarget = (int) TargetHarian::whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])->sum('jumlah_target');

        $members = $query->get()->map(function($user) use ($totalTarget) {
            $userLaporans = $user->laporans;
            $uploaded = $userLaporans->where('status', '!=', 'ditolak')->count();
            $valid = $userLaporans->where('status', 'valid')->count();
            $ditolak = $userLaporans->where('status', 'ditolak')->count();
            $kurang = max(0, $totalTarget - $uploaded);

            if ($totalTarget == 0) {
                $status = $uploaded > 0 ? 'Lengkap' : 'Belum Upload';
                $badgeClass = $uploaded > 0 ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary';
            } elseif ($uploaded == 0) {
                $status = 'Belum Upload';
                $badgeClass = 'bg-danger-subtle text-danger';
            } elseif ($uploaded < $totalTarget) {
                $status = 'Belum Lengkap';
                $badgeClass = 'bg-warning-subtle text-warning';
            } else {
                $status = 'Lengkap';
                $badgeClass = 'bg-success-subtle text-success';
            }

            return (object) [
                'id' => $user->id,
                'name' => $user->name,
                'target' => $totalTarget,
                'upload' => $uploaded,
                'kurang' => $kurang,
                'valid' => $valid,
                'ditolak' => $ditolak,
                'status' => $status,
                'badgeClass' => $badgeClass,
            ];
        });

        if ($request->filled('status') && $request->status !== 'Semua Status') {
            $filterStatus = $request->status;
            $members = $members->filter(fn($m) => strtolower($m->status) === strtolower($filterStatus));
        }

        return view('admin.rekap', compact('tanggalMulai', 'tanggalSelesai', 'members', 'filterAktif'));
    }
}
