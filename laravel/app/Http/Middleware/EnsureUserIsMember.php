<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsMember
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || $user->role !== 'user' || !$user->profile) {
            abort(403, 'Akses ini hanya tersedia untuk anggota terdaftar.');
        }

        return $next($request);
    }
}
