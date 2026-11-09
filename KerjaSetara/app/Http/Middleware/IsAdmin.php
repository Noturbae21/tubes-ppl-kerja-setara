<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Ensure user is logged in
        if (! Auth::check()) {
            return redirect()->route('admin.login')
                ->with('error', 'Sesi Anda telah berakhir atau belum masuk. Silakan login sebagai Administrator.');
        }

        // 2. Ensure user has 'admin' role
        if (! Auth::user()->isAdmin()) {
            // Optional: Log unauthorized attempt for audit/security
            // If logged in as another role, redirect to their home or abort with 403
            return redirect()->route('home')
                ->with('error', 'Akses terlarang. Akun Anda tidak memiliki hak akses Administrator.');
        }

        return $next($request);
    }
}
