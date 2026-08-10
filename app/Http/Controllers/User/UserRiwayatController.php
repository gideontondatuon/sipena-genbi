<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use Illuminate\Http\Request;

class UserRiwayatController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $riwayatList = Laporan::with('akunInstagram')
            ->where('user_id', $userId)
            ->latest()
            ->get();

        return view('user.riwayat', compact('riwayatList'));
    }
}
