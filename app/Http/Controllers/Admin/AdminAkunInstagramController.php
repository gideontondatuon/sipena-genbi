<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AkunInstagram;
use Illuminate\Http\Request;

class AdminAkunInstagramController extends Controller
{
    public function index()
    {
        $akunList = AkunInstagram::withCount('targetHarians')->get();
        return view('admin.akun-instagram', compact('akunList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_akun' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $validated['status'] = 'aktif';

        AkunInstagram::create($validated);

        return redirect()->route('admin.akun-instagram.index')->with('success', 'Akun Instagram berhasil disimpan.');
    }

    public function update(Request $request, AkunInstagram $akunInstagram)
    {
        $validated = $request->validate([
            'nama_akun' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $akunInstagram->update($validated);

        return redirect()->route('admin.akun-instagram.index')->with('success', 'Akun Instagram berhasil diperbarui.');
    }

    public function destroy(AkunInstagram $akunInstagram)
    {
        $akunInstagram->delete();

        return redirect()->route('admin.akun-instagram.index')->with('success', 'Akun Instagram berhasil dihapus.');
    }
}
