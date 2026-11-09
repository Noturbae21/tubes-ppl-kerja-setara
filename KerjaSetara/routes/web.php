<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Hrd\HrdAuthController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\KandidatController;
use Illuminate\Support\Facades\Route;

// ─── Public Landing Page ──────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');

// Job Detail Direct Routes
Route::get('/jobs/{id}', [JobController::class, 'show'])->name('jobs.show');
Route::get('/job/{id}', [JobController::class, 'show'])->name('job.show');
Route::get('/lowongan/{id}', [JobController::class, 'show'])->name('lowongan.show');

// ─── Kandidat / Job Seeker Portal ─────────────────────────────────────────────
Route::prefix('kandidat')->name('kandidat.')->group(function () {
    Route::get('/', fn () => redirect()->route('kandidat.home'));
    Route::get('/home', [KandidatController::class, 'index'])->name('home');
    Route::get('/lowongan/{id}', [JobController::class, 'show'])->name('lowongan.show');
    Route::post('/lowongan/{id}/lamar', [KandidatController::class, 'apply'])->name('lowongan.apply');
    Route::post('/lowongan/{id}/bookmark', [KandidatController::class, 'toggleBookmark'])->name('lowongan.bookmark');

    // Riwayat Lamaran Saya
    Route::get('/lamaran', [KandidatController::class, 'applications'])->name('lamaran');
    Route::get('/riwayat-lamaran', fn () => redirect()->route('kandidat.lamaran'))->name('riwayat');

    // Profil & Preferensi Aksesibilitas Kandidat
    Route::get('/profil', [KandidatController::class, 'profil'])->name('profil');
    Route::post('/profil', [KandidatController::class, 'updateProfil'])->name('profil.update');

    // Aksi Interaktif Lamaran (Batalkan, Terima Tawaran, Tolak Tawaran)
    Route::post('/lamaran/{id}/batal', [KandidatController::class, 'cancelApplication'])->name('lamaran.cancel');
    Route::post('/lamaran/{id}/terima-tawaran', [KandidatController::class, 'acceptOffer'])->name('lamaran.acceptOffer');
    Route::post('/lamaran/{id}/tolak-tawaran', [KandidatController::class, 'rejectOffer'])->name('lamaran.rejectOffer');
});

// ─── General User / Candidate Auth (Guest) ────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// General Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Candidate / General Dashboard
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return view('dashboard', compact('user'));
    })->name('dashboard');
});

// ─── ADMIN PORTAL (Multi-Role Authentication) ─────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {
    // Dedicated Admin Login Routes
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Protected Admin Panel Routes (Restricted via IsAdmin middleware)
    Route::middleware(['is_admin'])->group(function () {
        Route::get('/', fn () => redirect()->route('admin.dashboard'));
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // User Management
        Route::get('/users', [AdminDashboardController::class, 'users'])->name('users');
        Route::delete('/users/{user}', [AdminDashboardController::class, 'destroyUser'])->name('users.destroy');

        // Job Vacancies Moderation
        Route::get('/lowongan', [AdminDashboardController::class, 'vacancies'])->name('lowongan');
        Route::post('/lowongan/{vacancy}/toggle-status', [AdminDashboardController::class, 'toggleVacancyStatus'])->name('lowongan.toggle');
        Route::post('/companies/{company}/toggle-certified', [AdminDashboardController::class, 'toggleCompanyCertification'])->name('companies.toggle-certified');

        // Settings & Accessibility Taxonomies
        Route::get('/settings', [AdminDashboardController::class, 'settings'])->name('settings');
    });
});

// ─── HRD / COMPANY PORTAL (Placeholder & Structure for Next Phase) ────────────
Route::prefix('hrd')->name('hrd.')->group(function () {
    Route::get('/login', [HrdAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [HrdAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [HrdAuthController::class, 'logout'])->name('logout');

    Route::middleware(['is_hrd'])->group(function () {
        Route::get('/', fn () => redirect()->route('hrd.dashboard'));
        Route::get('/dashboard', function () {
            $user = auth()->user();

            return view('hrd.dashboard', compact('user'));
        })->name('dashboard');
    });
});
