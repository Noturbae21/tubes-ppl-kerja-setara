<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AdminAuthController extends Controller
{
    /**
     * Display the dedicated Administrator login page.
     */
    public function showLogin()
    {
        // If already logged in as Admin, redirect straight to dashboard
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    /**
     * Handle an incoming admin authentication request with brute-force protection.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $throttleKey = $this->throttleKey($request);

        // 1. Rate Limiting Check (Max 5 attempts per 60 seconds)
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'email' => "Terlalu banyak percobaan masuk gagal. Demi keamanan portal administrator, silakan coba kembali dalam {$seconds} detik.",
            ]);
        }

        $remember = $request->boolean('remember');

        // 2. Attempt authentication
        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            // 3. Strict Role Verification
            if (! $user->isAdmin()) {
                ActivityLog::log(
                    'access_denied',
                    "Percobaan masuk admin ditolak untuk email: {$credentials['email']}; peran bukan administrator.",
                    ['level' => 'warning', 'result' => 'Ditolak', 'user' => $user]
                );

                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                RateLimiter::hit($throttleKey, 60);

                throw ValidationException::withMessages([
                    'email' => 'Akses ditolak. Akun ini tidak terdaftar memiliki kewenangan sebagai Administrator KerjaSetara.',
                ]);
            }

            // 4. Check if Admin is blocked
            if ($user->isBlocked()) {
                ActivityLog::log(
                    'access_denied',
                    "Percobaan masuk admin ditolak: akun {$user->name} ({$user->identifier}) diblokir.",
                    ['level' => 'warning', 'result' => 'Ditolak', 'user' => $user]
                );

                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                RateLimiter::hit($throttleKey, 60);

                throw ValidationException::withMessages([
                    'email' => 'Akun Administrator ini telah dinonaktifkan atau diblokir.',
                ]);
            }

            // Success: clear rate limiter attempts and regenerate session
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            ActivityLog::log(
                'login',
                "Administrator {$user->name} ({$user->identifier}) berhasil masuk ke portal administrator.",
                ['level' => 'info', 'result' => 'Berhasil', 'user' => $user]
            );

            if ($user->must_change_password) {
                return redirect()->route('password.first_time')
                    ->with('info', 'Anda menggunakan kata sandi sementara. Silakan tentukan kata sandi baru Anda.');
            }

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Selamat datang di Panel Manajemen KerjaSetara, '.$user->name.'!');
        }

        // Increment failed attempt counter
        RateLimiter::hit($throttleKey, 60);

        ActivityLog::log(
            'login_failed',
            "Percobaan masuk portal administrator gagal untuk email: {$credentials['email']}.",
            ['level' => 'warning', 'result' => 'Gagal']
        );

        throw ValidationException::withMessages([
            'email' => 'Kredensial administrator tidak valid. Periksa kembali email dan kata sandi Anda.',
        ]);
    }

    /**
     * Log the administrator out of the application.
     */
    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            ActivityLog::log(
                'logout',
                "Administrator {$user->name} ({$user->identifier}) keluar dari sesi administrator.",
                ['level' => 'info', 'result' => 'Berhasil', 'user' => $user]
            );
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('info', 'Anda telah berhasil keluar dari sesi Administrator.');
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    protected function throttleKey(Request $request): string
    {
        return Str::transliterate(Str::lower($request->input('email')).'|'.$request->ip());
    }
}
