<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasBranchAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Jika Super Admin atau Akuntan, izinkan akses ke seluruh cabang
        if ($user->hasAnyRole(['Super Admin', 'Staf Keuangan', 'Auditor'])) {
            return $next($request);
        }

        // Ambil branch_id dari input request (misal dari form/filter)
        $requestBranchId = $request->input('branch_id') ?? $request->route('branch_id');

        // Jika mencoba mengakses data cabang lain
        if ($requestBranchId && (int)$requestBranchId !== (int)$user->branch_id) {
            abort(403, 'Anda tidak memiliki hak akses untuk data cabang ini.');
        }

        return $next($request);
    }
}
