<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
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
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            return redirect()->route('admin.login')
                ->with('error', 'Sesi Anda telah berakhir atau belum masuk. Silakan login sebagai Administrator.');
        }

        $user = Auth::user();

        // 2. Blocked Admin check
        if ($user->isBlocked()) {
            ActivityLog::log(
                'access_denied',
                "Percobaan akses ke endpoint admin '{$request->path()}' dibatalkan: akun Administrator '{$user->name}' berstatus diblokir.",
                [
                    'level' => 'error',
                    'result' => 'Ditolak',
                    'user' => $user,
                ]
            );

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->wantsJson()) {
                return response()->json(['message' => 'Akun Administrator Anda telah dinonaktifkan atau diblokir.'], 403);
            }

            return redirect()->route('admin.login')
                ->with('error', 'Akun Administrator Anda telah dinonaktifkan atau diblokir.');
        }

        // 3. Ensure user has 'admin' role
        if (! $user->isAdmin()) {
            ActivityLog::log(
                'access_denied',
                "Percobaan akses ke endpoint admin '{$request->path()}' ditolak: akun '{$user->name}' ({$user->identifier}) bukan Administrator.",
                [
                    'level' => 'error',
                    'result' => 'Ditolak',
                    'user' => $user,
                ]
            );

            if ($request->wantsJson()) {
                return response()->json(['message' => 'Akses terlarang. Hak akses Administrator diperlukan.'], 403);
            }

            return redirect()->route('home')
                ->with('error', 'Akses terlarang. Akun Anda tidak memiliki hak akses Administrator.');
        }

        return $next($request);
    }
}
