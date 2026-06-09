<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Memberitahu VS Code dan Laravel secara eksplisit bahwa $user adalah model User kita
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        // Cek apakah user login dan jalankan fungsi hasRole
        if (!$user || !$user->hasRole($role)) {
            abort(403, 'Akses Ditolak. Anda tidak memiliki izin untuk halaman ini.');
        }

        return $next($request);
    }
}