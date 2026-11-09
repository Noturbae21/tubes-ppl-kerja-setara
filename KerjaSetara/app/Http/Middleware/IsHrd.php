<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsHrd
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('hrd.login')
                ->with('error', 'Silakan masuk ke Portal Perusahaan / HRD terlebih dahulu.');
        }

        if (! Auth::user()->isHrd()) {
            return redirect()->route('home')
                ->with('error', 'Akses terlarang. Akun Anda tidak terdaftar sebagai Perusahaan/HRD.');
        }

        return $next($request);
    }
}
