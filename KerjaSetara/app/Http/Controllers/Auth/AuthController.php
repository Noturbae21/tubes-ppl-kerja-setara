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

        return redirect()->route('kandidat.home')
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

    // ─── First Time Password Change ──────────────────────────────────────────

    public function showFirstTimePassword()
    {
        $user = Auth::user();
        if (! $user->must_change_password) {
            return app(LoginController::class)->redirectBasedOnRole($user);
        }

        return view('auth.first_time_password', compact('user'));
    }

    public function updateFirstTimePassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi minimal terdiri dari 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
            'must_change_password' => false,
        ]);

        \App\Models\ActivityLog::log(
            'update',
            "Pengguna {$user->name} memperbarui kata sandi pertama kali (alur kata sandi sementara)",
            ['target_type' => 'user', 'target_id' => $user->id]
        );

        return app(LoginController::class)->redirectBasedOnRole($user)
            ->with('success', 'Kata sandi baru Anda berhasil disimpan. Selamat datang di KerjaSetara!');
    }

    // ─── Logout ───────────────────────────────────────────────────────────────

    public function logout(Request $request)
    {
        return app(LoginController::class)->logout($request);
    }
}
