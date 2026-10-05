<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles Daftar role yang diizinkan (dipisahkan koma)
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        // Cek apakah pengguna sudah login
        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated.'
            ], Response::HTTP_UNAUTHORIZED);
        }

        // Cek apakah role user ada di dalam daftar role yang diperbolehkan
        // Catatan: Mengasumsikan kolom 'role' atau relasi $user->role ada di model User
        if (!in_array($user->role, $roles)) {
            return response()->json([
                'message' => 'Akses ditolak. Anda tidak memiliki izin untuk mengakses resource ini.',
                'required_roles' => $roles,
                'user_role'      => $user->role
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
