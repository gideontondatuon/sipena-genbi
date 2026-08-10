<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $notifikasis = Notifikasi::where(function($q) use ($userId) {
                $q->whereNull('user_id')->orWhere('user_id', $userId);
            })
            ->latest()
            ->get();

        return view('notifikasi', compact('notifikasis'));
    }

    public function markReadAndOpen($id)
    {
        $userId = auth()->id();

        $notif = Notifikasi::where(function($q) use ($userId) {
                $q->whereNull('user_id')->orWhere('user_id', $userId);
            })
            ->where('id', $id)
            ->first();

        if ($notif) {
            $notif->update(['is_read' => true]);
        }

        return redirect()->route('notifikasi.index')->with('success', 'Notifikasi telah dibaca.');
    }

    public function markAllRead()
    {
        $userId = auth()->id();

        Notifikasi::where(function($q) use ($userId) {
                $q->whereNull('user_id')->orWhere('user_id', $userId);
            })
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return redirect()->back()->with('success', 'Semua notifikasi ditandai sebagai dibaca.');
    }

    public function destroy($id)
    {
        $userId = auth()->id();

        Notifikasi::where('user_id', $userId)
            ->where('id', $id)
            ->delete();

        return redirect()->back()->with('success', 'Notifikasi berhasil dihapus.');
    }

    public function clearAll()
    {
        $userId = auth()->id();

        Notifikasi::where('user_id', $userId)->delete();

        return redirect()->back()->with('success', 'Seluruh notifikasi pribadi berhasil dibersihkan.');
    }
}
