<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KandidatController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Kandidat Routes (Accessible for browsing & details, interactive with auth)
Route::prefix('kandidat')->name('kandidat.')->group(function () {
    Route::get('/', fn () => redirect()->route('kandidat.home'));
    Route::get('/home', [KandidatController::class, 'index'])->name('home');
    Route::get('/lowongan/{id}', [KandidatController::class, 'show'])->name('lowongan.show');
    Route::post('/lowongan/{id}/lamar', [KandidatController::class, 'apply'])->name('lowongan.apply');
    Route::post('/lowongan/{id}/bookmark', [KandidatController::class, 'toggleBookmark'])->name('lowongan.bookmark');
    
    // Riwayat Lamaran Saya
    Route::get('/lamaran', [KandidatController::class, 'applications'])->name('lamaran');
    Route::get('/riwayat-lamaran', fn () => redirect()->route('kandidat.lamaran'))->name('riwayat');

    // Profil & Preferensi Aksesibilitas Kandidat (Dikeluarkan dari middleware auth agar aman dari infinite redirect)
    Route::get('/profil', [KandidatController::class, 'profil'])->name('profil');
    Route::post('/profil', [KandidatController::class, 'updateProfil'])->name('profil.update');

    // Aksi Interaktif Lamaran (Batalkan, Terima Tawaran, Tolak Tawaran)
    Route::post('/lamaran/{id}/batal', [KandidatController::class, 'cancelApplication'])->name('lamaran.cancel');
    Route::post('/lamaran/{id}/terima-tawaran', [KandidatController::class, 'acceptOffer'])->name('lamaran.acceptOffer');
    Route::post('/lamaran/{id}/tolak-tawaran', [KandidatController::class, 'rejectOffer'])->name('lamaran.rejectOffer');
});

// Auth Routes (guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();

        return view('dashboard', compact('user'));
    })->name('dashboard');
});