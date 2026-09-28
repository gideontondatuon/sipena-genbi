<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            abort(403, 'Silakan login terlebih dahulu.');
        }

        if (auth()->user()->status !== 'aktif') {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Akun Administrator Anda dinonaktifkan.');
        }

        if (!auth()->user()->isAdmin()) {
            abort(403, 'Akses khusus Admin.');
        }

        return $next($request);
    }
}
