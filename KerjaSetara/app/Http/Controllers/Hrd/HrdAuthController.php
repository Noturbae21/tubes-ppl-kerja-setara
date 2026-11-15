<?php

namespace App\Http\Controllers\Hrd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\CompanyProfile;

class HrdAuthController extends Controller
{
    /**
     * Display the dedicated HRD / Company login page.
     */
    public function showLogin()
    {
        if (Auth::check() && Auth::user()->isHrd()) {
            return redirect()->route('hrd.dashboard');
        }

        return view('hrd.auth.login');
    }

    /**
     * Handle an incoming HRD authentication request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if (! $user->isHrd()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                throw ValidationException::withMessages([
                    'email' => 'Akses khusus perwakilan perusahaan. Akun Anda tidak memiliki peran HRD.',
                ]);
            }

            if ($user->isBlocked()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                throw ValidationException::withMessages([
                    'email' => 'Akun perusahaan Anda telah dinonaktifkan atau diblokir oleh Administrator.',
                ]);
            }

            // Ensure HRD user has a company profile
            if (! $user->companyProfile) {
                \App\Models\CompanyProfile::create([
                    'user_id' => $user->id,
                    'company_name' => 'Perusahaan Inklusif',
                    'description' => 'Profil perusahaan contoh untuk HRD.',
                    'industry' => 'Teknologi',
                    'location' => 'Jakarta Selatan',
                    'accessibility_features' => [],
                ]);
            }

            if ($user->must_change_password) {
                return redirect()->route('password.first_time')
                    ->with('info', 'Anda menggunakan kata sandi sementara. Silakan tentukan kata sandi baru Anda.');
            }

            return redirect()->route('hrd.dashboard')
                ->with('success', 'Selamat datang di Portal Perusahaan Inklusif, '.$user->name.'!');
        }

        throw ValidationException::withMessages([
            'email' => 'Email atau kata sandi akun perusahaan tidak sesuai.',
        ]);
    }

    /**
     * Log the HRD out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('hrd.login')
            ->with('info', 'Anda telah keluar dari Portal Perusahaan.');
    }
}
