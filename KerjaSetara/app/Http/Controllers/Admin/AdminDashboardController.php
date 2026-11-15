<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyProfile;
use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Display the Admin Overview Dashboard.
     */
    public function index()
    {
        // 1. High-level KPI Statistics
        $stats = [
            'total_talenta' => User::whereIn('role', ['kandidat', 'user'])->count(),
            'total_perusahaan' => User::where('role', 'hrd')->count(),
            'lowongan_aktif' => JobVacancy::where('is_active', true)->count(),
            'lowongan_pending' => JobVacancy::where('is_active', false)->count(),
            'total_lamaran' => JobApplication::count(),
            'mitra_inklusif' => CompanyProfile::where('is_inclusive_certified', true)->count(),
            'total_users' => User::count(),
        ];

        // 2. Recent Job Vacancies requiring moderation or active review
        $recentVacancies = JobVacancy::with('company')
            ->latest()
            ->take(6)
            ->get();

        // 3. Recent Registered Users (Talents & HRDs)
        $recentUsers = User::latest()
            ->take(6)
            ->get();

        // 4. Breakdown of Talents by Primary Accessibility Category
        $categoryBreakdown = [
            'fisik' => User::where('accessibility_category', 'fisik')->count(),
            'komunikasi' => User::where('accessibility_category', 'komunikasi')->count(),
            'digital' => User::where('accessibility_category', 'digital')->count(),
            'neurodiverse' => User::where('accessibility_category', 'neurodiverse')->count(),
        ];

        return view('admin.dashboard', compact(
            'stats',
            'recentVacancies',
            'recentUsers',
            'categoryBreakdown'
        ));
    }

    /**
     * Display User Management Panel (Forwarded to dedicated AdminUserController).
     */
    public function users(Request $request)
    {
        return app(AdminUserController::class)->index($request);
    }

    /**
     * Delete a user from the platform (with safety checks and relational cleanup).
     */
    public function destroyUser(User $user)
    {
        return app(AdminUserController::class)->destroy($user);
    }

    /**
     * Display Job Vacancies Moderation Panel.
     */
    public function vacancies(Request $request)
    {
        $statusFilter = $request->query('status', 'all');
        $categoryFilter = $request->query('category', 'all');
        $search = $request->query('search', '');

        $query = JobVacancy::with('company')->latest();

        if ($statusFilter === 'active') {
            $query->where('is_active', true);
        } elseif ($statusFilter === 'pending') {
            $query->where('is_active', false);
        }

        if ($categoryFilter !== 'all') {
            $query->where('category', $categoryFilter);
        }

        if (! empty($search)) {
            $query->search($search);
        }

        $vacancies = $query->paginate(8)->withQueryString();

        $counts = [
            'all' => JobVacancy::count(),
            'active' => JobVacancy::where('is_active', true)->count(),
            'pending' => JobVacancy::where('is_active', false)->count(),
        ];

        return view('admin.vacancies.index', compact('vacancies', 'statusFilter', 'categoryFilter', 'search', 'counts'));
    }

    /**
     * Toggle Job Vacancy Active / Approved Status.
     */
    public function toggleVacancyStatus(JobVacancy $vacancy)
    {
        $vacancy->is_active = ! $vacancy->is_active;
        $vacancy->save();

        $statusLabel = $vacancy->is_active ? 'disetujui & dipublikasikan' : 'dinonaktifkan / ditangguhkan';

        return back()->with('success', "Status lowongan '{$vacancy->title}' berhasil {$statusLabel}.");
    }

    /**
     * Toggle Company Inclusive Certification Status.
     */
    public function toggleCompanyCertification(CompanyProfile $company)
    {
        $company->is_inclusive_certified = ! $company->is_inclusive_certified;
        $company->save();

        $statusLabel = $company->is_inclusive_certified ? 'Tersertifikasi Ramah Disabilitas' : 'Dicabut Sertifikasinya';

        return back()->with('success', "Status perusahaan '{$company->company_name}' berhasil diperbarui menjadi {$statusLabel}.");
    }

    /**
     * Display System Settings & Accessibility Categories Configuration.
     */
    public function settings()
    {
        $categories = [
            'fisik' => [
                'name' => 'Mobilitas Fisik & Lingkungan',
                'icon' => '♿',
                'badge' => 'Akses Kursi Roda',
                'color' => 'blue',
                'description' => 'Mencakup fasilitas fisik seperti ram, lift aksesibel, pintu geser otomatis, toilet khusus, dan jalur taktil pemandu.',
                'tags' => ['ramp', 'lift', 'toilet_khusus', 'parkir_disabilitas', 'jalur_pemandu'],
                'total_jobs' => JobVacancy::where('category', 'fisik')->count(),
            ],
            'komunikasi' => [
                'name' => 'Komunikasi Inklusif & Bahasa Isyarat',
                'icon' => '🗣️',
                'badge' => 'Ramah Tunarungu',
                'color' => 'emerald',
                'description' => 'Dukungan juru bicara bahasa isyarat (BISINDO), materi orientasi braille, live auto-captioning, dan komunikasi tertulis terstruktur.',
                'tags' => ['sign_language', 'braille'],
                'total_jobs' => JobVacancy::where('category', 'komunikasi')->count(),
            ],
            'digital' => [
                'name' => 'Dukungan Digital & Teknologi Asistif',
                'icon' => '💻',
                'badge' => 'Screen Reader Friendly',
                'color' => 'purple',
                'description' => 'Software kompatibel screen reader (NVDA, JAWS), keyboard-only navigation, monitor high contrast, dan speech recognition.',
                'tags' => ['screen_reader', 'keyboard_navigation', 'high_contrast', 'voice_command'],
                'total_jobs' => JobVacancy::where('category', 'digital')->count(),
            ],
            'neurodiverse' => [
                'name' => 'Lingkungan Neurodiverse & Sensori',
                'icon' => '🧠',
                'badge' => 'Ruang Tenang & Fleksibel',
                'color' => 'amber',
                'description' => 'Ruang sensori hening (Quiet Room), penyesuaian intensitas cahaya, jam kerja adaptif, opsi remote/WFH, dan panduan tugas modular.',
                'tags' => ['ruang_tenang', 'wfh_fleksibel', 'manajemen_beban_adaptif'],
                'total_jobs' => JobVacancy::where('category', 'neurodiverse')->count(),
            ],
        ];

        $systemInfo = [
            'laravel_version' => app()->version(),
            'php_version' => PHP_VERSION,
            'database_driver' => config('database.default'),
            'app_environment' => app()->environment(),
            'app_debug' => config('app.debug') ? 'Enabled (Development)' : 'Disabled (Production)',
            'timezone' => config('app.timezone'),
        ];

        return view('admin.settings.index', compact('categories', 'systemInfo'));
    }
}
