<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                RateLimiter::hit($throttleKey, 60);

                throw ValidationException::withMessages([
                    'email' => 'Akses ditolak. Akun ini tidak terdaftar memiliki kewenangan sebagai Administrator KerjaSetara.',
                ]);
            }

            // Success: clear rate limiter attempts and regenerate session
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Selamat datang di Panel Manajemen KerjaSetara, '.$user->name.'!');
        }

        // Increment failed attempt counter
        RateLimiter::hit($throttleKey, 60);

        throw ValidationException::withMessages([
            'email' => 'Kredensial administrator tidak valid. Periksa kembali email dan kata sandi Anda.',
        ]);
    }

    /**
     * Log the administrator out of the application.
     */
    public function logout(Request $request)
    {
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
