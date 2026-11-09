<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request and ensure the user has one of the required roles.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan masuk terlebih dahulu untuk mengakses halaman ini.');
        }

        $user = Auth::user();
        $userRole = strtolower($user->role ?? '');

        // Normalize roles ('user' and 'kandidat' are considered equivalent)
        $normalizedRoles = [];
        foreach ($roles as $role) {
            $r = strtolower(trim($role));
            $normalizedRoles[] = $r;
            if ($r === 'user') {
                $normalizedRoles[] = 'kandidat';
            } elseif ($r === 'kandidat') {
                $normalizedRoles[] = 'user';
            }
        }

        if (! in_array($userRole, $normalizedRoles)) {
            // Redirect unauthorized access to their respective dashboard
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard')
                    ->with('error', 'Akses ditolak. Halaman tersebut bukan untuk peran Administrator.');
            }

            if ($user->isHrd()) {
                return redirect()->route('hrd.dashboard')
                    ->with('error', 'Akses ditolak. Halaman tersebut bukan untuk peran Perusahaan/HRD.');
            }

            return redirect()->route('dashboard')
                ->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman tersebut.');
        }

        return $next($request);
    }
}
