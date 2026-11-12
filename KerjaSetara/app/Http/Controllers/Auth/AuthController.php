<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserAccessibilityPreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ─── Register ────────────────────────────────────────────────────────────

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:kandidat,user,hrd,company'],
            'accessibility_category' => ['nullable', 'string', 'in:fisik,komunikasi,digital,neurodiverse'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'full_name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        if (! empty($validated['accessibility_category'])) {
            UserAccessibilityPreference::create([
                'user_id' => $user->id,
                'category' => $validated['accessibility_category'],
            ]);
        }

        Auth::login($user);

        if ($user->isHrd()) {
            $user->getOrCreateCompanyProfile();

            return redirect()->route('hrd.dashboard')
                ->with('success', 'Akun perusahaan berhasil dibuat! Selamat datang di Portal HRD KerjaSetara, '.$user->name.'.');
        }

        return redirect()->route('dashboard')
            ->with('success', 'Akun berhasil dibuat! Selamat datang di KerjaSetara, '.$user->name.'.');
    }

    // ─── Login ────────────────────────────────────────────────────────────────

    public function showLogin(Request $request)
    {
        return app(LoginController::class)->showLoginForm($request);
    }

    public function login(Request $request)
    {
        return app(LoginController::class)->login($request);
    }

    // ─── Logout ───────────────────────────────────────────────────────────────

    public function logout(Request $request)
    {
        return app(LoginController::class)->logout($request);
    }
}
