<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BranchAccessMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Admin dibebaskan dari pembatasan cabang
        if ($user && $user->hasRole('Admin')) {
            return $next($request);
        }

        // Jika Staff mencoba mengakses parameter cabang lain di URL
        $requestedBranchId = $request->route('branch') ?? $request->input('branch_id');

        if ($requestedBranchId && (int)$user->branch_id !== (int)$requestedBranchId) {
            abort(403, 'Anda tidak memiliki otorisasi untuk mengakses data cabang ini.');
        }

        return $next($request);
    }
}
