<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class AdminAnggotaController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('username', 'like', '%' . $request->search . '%')
                  ->orWhere('nim', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status') && $request->status !== 'Semua Status') {
            $query->where('status', strtolower($request->status));
        }

        $anggotaList = $query->orderBy('name')->get();

        return view('admin.anggota', compact('anggotaList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|alpha_dash|max:50|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,anggota',
            'phone' => 'nullable|string',
            'nim' => 'nullable|string',
            'password' => 'required|string|min:6',
        ]);

        if (empty($validated['username'])) {
            $base = \Illuminate\Support\Str::slug(explode('@', $validated['email'])[0], '_');
            $username = $base;
            $counter = 1;
            while (User::where('username', $username)->exists()) {
                $username = $base . $counter;
                $counter++;
            }
            $validated['username'] = $username;
        }

        $validated['password'] = Hash::make($validated['password']);
        $validated['status'] = 'aktif';
        $validated['email_verified_at'] = Carbon::now(); // Auto-verifikasi akun yang dibuat admin

        User::create($validated);

        return redirect()->route('admin.anggota.index')->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|alpha_dash|max:50|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,anggota',
            'phone' => 'nullable|string',
            'nim' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route('admin.anggota.index')->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function toggleStatus(User $user)
    {
        $newStatus = $user->status === 'aktif' ? 'nonaktif' : 'aktif';
        $user->update(['status' => $newStatus]);

        return redirect()->route('admin.anggota.index')->with('success', 'Status anggota berhasil diubah.');
    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return redirect()->route('admin.anggota.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Delete user's laporans and notifications
        $user->laporans()->delete();
        \App\Models\Notifikasi::where('user_id', $user->id)->delete();

        $user->delete();

        return redirect()->route('admin.anggota.index')->with('success', 'Anggota berhasil dihapus.');
    }
}
