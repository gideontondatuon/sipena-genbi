<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AnggotaMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || (!auth()->user()->isAnggota() && !auth()->user()->isAdmin())) {
            abort(403, 'Akses hanya untuk Anggota GenBI.');
        }

        return $next($request);
    }
}
