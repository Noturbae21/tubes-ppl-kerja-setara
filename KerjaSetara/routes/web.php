<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Hrd\HrdApplicantController;
use App\Http\Controllers\Hrd\HrdAuthController;
use App\Http\Controllers\Hrd\HrdDashboardController;
use App\Http\Controllers\Hrd\HrdSettingsController;
use App\Http\Controllers\Hrd\HrdVacancyController;
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

        if ($user->isHrd()) {
            return redirect()->route('hrd.dashboard');
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

// ─── HRD / COMPANY PORTAL ─────────────────────────────────────────────────────
Route::prefix('hrd')->name('hrd.')->group(function () {
    Route::get('/login', [HrdAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [HrdAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [HrdAuthController::class, 'logout'])->name('logout');

    Route::middleware(['is_hrd'])->group(function () {
        Route::get('/', fn () => redirect()->route('hrd.dashboard'));
        Route::get('/dashboard', [HrdDashboardController::class, 'index'])->name('dashboard');
        Route::get('/ringkasan', [HrdDashboardController::class, 'index'])->name('ringkasan');

        // Kelola Lowongan
        Route::get('/lowongan', [HrdVacancyController::class, 'index'])->name('lowongan.index');
        Route::get('/lowongan/buat', [HrdVacancyController::class, 'create'])->name('lowongan.create');
        Route::post('/lowongan', [HrdVacancyController::class, 'store'])->name('lowongan.store');
        Route::get('/lowongan/{vacancy}/edit', [HrdVacancyController::class, 'edit'])->name('lowongan.edit');
        Route::put('/lowongan/{vacancy}', [HrdVacancyController::class, 'update'])->name('lowongan.update');
        Route::post('/lowongan/{vacancy}/toggle-status', [HrdVacancyController::class, 'toggleStatus'])->name('lowongan.toggle-status');
        Route::delete('/lowongan/{vacancy}', [HrdVacancyController::class, 'destroy'])->name('lowongan.destroy');

        // Kelola Pelamar
        Route::get('/pelamar', [HrdApplicantController::class, 'index'])->name('pelamar.index');
        Route::get('/pelamar/{application}', [HrdApplicantController::class, 'show'])->name('pelamar.show');
        Route::post('/pelamar/{application}/status', [HrdApplicantController::class, 'updateStatus'])->name('pelamar.updateStatus');

        // Pengaturan Perusahaan
        Route::get('/pengaturan', [HrdSettingsController::class, 'index'])->name('pengaturan.index');
        Route::post('/pengaturan/profil', [HrdSettingsController::class, 'updateProfile'])->name('pengaturan.updateProfile');
        Route::post('/pengaturan/fasilitas', [HrdSettingsController::class, 'updateFacilities'])->name('pengaturan.updateFacilities');
        Route::post('/pengaturan/dokumen', [HrdSettingsController::class, 'updateDocument'])->name('pengaturan.updateDocument');
        Route::post('/pengaturan/keamanan', [HrdSettingsController::class, 'updatePassword'])->name('pengaturan.updatePassword');
    });
});

// ─── ALIAS PERUSAHAAN -> HRD (Mencegah Error 404 Pada Rute Perusahaan) ────────
Route::prefix('perusahaan')->name('perusahaan.')->group(function () {
    Route::get('/', fn () => redirect()->route('hrd.dashboard'));
    Route::get('/dashboard', fn () => redirect()->route('hrd.dashboard'))->name('dashboard');
    Route::get('/login', fn () => redirect()->route('hrd.login'))->name('login');
    Route::get('/lowongan', fn () => redirect()->route('hrd.lowongan.index'))->name('lowongan');
    Route::get('/pelamar', fn () => redirect()->route('hrd.pelamar.index'))->name('pelamar');
    Route::get('/pengaturan', fn () => redirect()->route('hrd.pengaturan.index'))->name('pengaturan');
});
