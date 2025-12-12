<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class VerifyJwtFromExpress
{
    public function handle(Request $request, Closure $next)
    {
        try {
            $token = $request->bearerToken();

            if (!$token) {
                return response()->json(['message' => 'Token tidak ada'], 401);
            }

            // Pastikan secret sama dengan Express!
            $decoded = JWT::decode($token, new Key(env('EXPRESS_JWT_SECRET'), 'HS256'));

            // Attach user ke request (mirip auth()->user())
            $request->merge(['auth_user' => (array) $decoded]);

            return $next($request);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Token tidak valid atau kadaluarsa'], 401);
        }
    }
}