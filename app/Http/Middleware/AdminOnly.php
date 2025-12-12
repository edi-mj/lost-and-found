<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        // dd(session('user'));
        $user = session('user');

        if (!$user || ($user['role'] ?? null) !== 'admin') {
            return redirect('/dashboard')->with('error', 'Akses admin ditolak.');
        }

        return $next($request);
    }
}
