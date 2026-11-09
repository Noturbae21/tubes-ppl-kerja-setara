<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Show the unified multi-role login page.
     */
    public function showLoginForm(Request $request): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        $defaultRole = $request->query('role', 'user');
        if (! in_array($defaultRole, ['user', 'hrd', 'admin'])) {
            $defaultRole = 'user';
        }

        return view('auth.login', compact('defaultRole'));
    }

    /**
     * Handle multi-role authentication request with role validation.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'role' => ['nullable', 'string', 'in:user,kandidat,hrd,admin'],
        ]);

        $remember = $request->boolean('remember');
        $requestedRole = $request->input('role');

        if (! Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']], $remember)) {
            throw ValidationException::withMessages([
                'email' => 'Email atau kata sandi tidak sesuai dengan data kami.',
            ]);
        }

        $request->session()->regenerate();
        $user = Auth::user();

        // Validate role alignment if role was explicitly selected in the UI
        if ($requestedRole) {
            $normalizedRole = $requestedRole === 'kandidat' ? 'user' : $requestedRole;

            if ($normalizedRole === 'admin' && ! $user->isAdmin()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                throw ValidationException::withMessages([
                    'email' => 'Akun ini bukan akun Administrator. Silakan beralih ke tab peran yang sesuai.',
                ]);
            }

            if ($normalizedRole === 'hrd' && ! $user->isHrd()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                throw ValidationException::withMessages([
                    'email' => 'Akun ini bukan akun HRD / Perusahaan. Silakan beralih ke tab peran yang sesuai.',
                ]);
            }

            if ($normalizedRole === 'user' && ! $user->isUser()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                throw ValidationException::withMessages([
                    'email' => 'Akun ini bukan akun Talenta / Pencari Kerja. Silakan beralih ke tab peran yang sesuai.',
                ]);
            }
        }

        return $this->redirectBasedOnRole($user);
    }

    /**
     * Log out authenticated user.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah berhasil keluar dari akun KerjaSetara.');
    }

    /**
     * Redirect authenticated user according to their verified role.
     */
    public function redirectBasedOnRole($user): RedirectResponse
    {
        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Selamat datang di Panel Administrator KerjaSetara, '.$user->name.'.');
        }

        if ($user->isHrd()) {
            return redirect()->intended(route('hrd.dashboard'))
                ->with('success', 'Selamat datang di Portal Perusahaan / HRD, '.$user->name.'.');
        }

        return redirect()->intended(route('dashboard'))
            ->with('success', 'Selamat datang kembali di KerjaSetara, '.$user->name.'!');
    }
}
