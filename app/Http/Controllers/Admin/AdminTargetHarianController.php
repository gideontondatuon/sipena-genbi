<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TargetHarian;
use App\Models\AkunInstagram;
use App\Models\Periode;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class AdminTargetHarianController extends Controller
{
    public function index()
    {
        $akunList = AkunInstagram::where('status', 'aktif')->get();
        $targetList = TargetHarian::with('akunInstagram')->latest()->get();
        return view('admin.target-harian', compact('akunList', 'targetList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'akun_instagram_id' => 'required|exists:akun_instagrams,id',
            'tanggal' => 'required|date',
            'jumlah_target' => 'required|integer|min:1',
            'deadline' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $activePeriode = Periode::where('status', 'aktif')->first();
        if ($activePeriode) {
            $validated['periode_id'] = $activePeriode->id;
        }

        $target = TargetHarian::create($validated);
        $akun = AkunInstagram::find($validated['akun_instagram_id']);

        // Create global notification
        Notifikasi::create([
            'user_id' => null,
            'judul' => 'Target baru ditambahkan',
            'pesan' => 'Admin menambahkan target untuk akun ' . ($akun ? $akun->nama_akun : '') . ' tanggal ' . date('d F Y', strtotime($validated['tanggal'])) . ' sebanyak ' . $validated['jumlah_target'] . ' postingan.',
            'tipe' => 'target',
        ]);

        return redirect()->route('admin.target-harian.index')->with('success', 'Target harian berhasil ditambahkan.');
    }

    public function update(Request $request, TargetHarian $targetHarian)
    {
        $validated = $request->validate([
            'akun_instagram_id' => 'required|exists:akun_instagrams,id',
            'tanggal' => 'required|date',
            'jumlah_target' => 'required|integer|min:1',
            'deadline' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $targetHarian->update($validated);

        return redirect()->route('admin.target-harian.index')->with('success', 'Target harian berhasil diperbarui.');
    }

    public function destroy(TargetHarian $targetHarian)
    {
        $targetHarian->laporans()->delete();
        $targetHarian->delete();

        return redirect()->route('admin.target-harian.index')->with('success', 'Target harian berhasil dihapus.');
    }
}
