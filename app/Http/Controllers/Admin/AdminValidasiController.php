<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\AkunInstagram;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class AdminValidasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Laporan::with(['user', 'akunInstagram']);

        if ($request->filled('status')) {
            $statusMap = [
                'Menunggu Validasi' => 'menunggu',
                'Valid' => 'valid',
                'Ditolak' => 'ditolak',
                'Perlu Perbaikan' => 'perlu_perbaikan',
            ];
            if (isset($statusMap[$request->status])) {
                $query->where('status', $statusMap[$request->status]);
            }
        } else {
            // Default show pending validation first
            $query->where('status', 'menunggu');
        }

        if ($request->filled('akun_id') && $request->akun_id !== 'Semua Akun') {
            $query->where('akun_instagram_id', $request->akun_id);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_postingan', $request->tanggal);
        }

        if ($request->filled('search')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $laporans = $query->latest()->get();
        $akunList = AkunInstagram::all();

        return view('admin.validasi', compact('laporans', 'akunList'));
    }

    public function validasi(Request $request, Laporan $laporan)
    {
        $request->validate([
            'status' => 'required|in:valid,ditolak,perlu_perbaikan',
            'catatan_admin' => 'nullable|string',
        ]);

        $laporan->update([
            'status' => $request->status,
            'catatan_admin' => $request->catatan_admin,
        ]);

        // Send notification to user
        $judul = $request->status === 'valid' ? 'Laporan divalidasi' : 'Laporan ditolak / butuh perbaikan';
        $pesan = $request->status === 'valid' 
            ? 'Laporan ' . $laporan->akunInstagram->nama_akun . ' tanggal ' . date('d F Y', strtotime($laporan->tanggal_postingan)) . ' telah divalidasi oleh admin.'
            : 'Laporan ' . $laporan->akunInstagram->nama_akun . ' tanggal ' . date('d F Y', strtotime($laporan->tanggal_postingan)) . ' ditolak: ' . ($request->catatan_admin ?? 'Cek detail.');

        Notifikasi::create([
            'user_id' => $laporan->user_id,
            'judul' => $judul,
            'pesan' => $pesan,
            'tipe' => $request->status === 'valid' ? 'valid' : 'ditolak',
        ]);

        return redirect()->back()->with('success', 'Status laporan berhasil diperbarui.');
    }

    public function bulkValidasi(Request $request)
    {
        $request->validate([
            'laporan_ids' => 'required|array|min:1',
            'laporan_ids.*' => 'exists:laporans,id',
            'status' => 'required|in:valid,ditolak',
            'catatan_admin' => 'nullable|string',
        ]);

        $laporans = Laporan::with('akunInstagram')->whereIn('id', $request->laporan_ids)->get();
        $count = 0;

        foreach ($laporans as $laporan) {
            $laporan->update([
                'status' => $request->status,
                'catatan_admin' => $request->catatan_admin,
            ]);

            $judul = $request->status === 'valid' ? 'Laporan divalidasi masal' : 'Laporan ditolak masal';
            $pesan = $request->status === 'valid'
                ? 'Laporan ' . $laporan->akunInstagram->nama_akun . ' tanggal ' . date('d F Y', strtotime($laporan->tanggal_postingan)) . ' telah divalidasi oleh admin.'
                : 'Laporan ' . $laporan->akunInstagram->nama_akun . ' tanggal ' . date('d F Y', strtotime($laporan->tanggal_postingan)) . ' ditolak: ' . ($request->catatan_admin ?? 'Tidak memenuhi kriteria.');

            Notifikasi::create([
                'user_id' => $laporan->user_id,
                'judul' => $judul,
                'pesan' => $pesan,
                'tipe' => $request->status === 'valid' ? 'valid' : 'ditolak',
            ]);

            $count++;
        }

        $statusText = $request->status === 'valid' ? 'disetujui' : 'ditolak';
        return redirect()->back()->with('success', "Berhasil: {$count} laporan terpilih telah {$statusText} secara masal.");
    }

    public function destroy(Laporan $laporan)
    {
        // Delete physical image files from storage disk
        if ($laporan->bukti_like) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($laporan->bukti_like);
        }
        if ($laporan->bukti_komen) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($laporan->bukti_komen);
        }
        if ($laporan->bukti_share) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($laporan->bukti_share);
        }

        $laporan->delete();

        return redirect()->back()->with('success', 'Laporan anggota berhasil dihapus permanently dari sistem dan storage.');
    }
}
