<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Periode;
use App\Models\Laporan;
use Illuminate\Http\Request;

class AdminPeriodeController extends Controller
{
    public function index()
    {
        $periodeList = Periode::latest()->get();
        return view('admin.periode', compact('periodeList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_periode' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:aktif,arsip',
        ]);

        if ($validated['status'] === 'aktif') {
            // Set all other periodes to arsip if this one is active
            Periode::query()->update(['status' => 'arsip']);
        }

        Periode::create($validated);

        return redirect()->route('admin.periode.index')->with('success', 'Periode baru berhasil disimpan.');
    }

    public function update(Request $request, Periode $periode)
    {
        $validated = $request->validate([
            'nama_periode' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:aktif,arsip',
        ]);

        if ($validated['status'] === 'aktif') {
            Periode::where('id', '!=', $periode->id)->update(['status' => 'arsip']);
        }

        $periode->update($validated);

        return redirect()->route('admin.periode.index')->with('success', 'Data periode berhasil diperbarui.');
    }
}
