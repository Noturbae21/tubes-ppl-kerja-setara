<?php

namespace App\Http\Controllers\Hrd;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\JobVacancy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HrdDashboardController extends Controller
{
    /**
     * Display the HRD Ringkasan / Overview dashboard.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $company = $user->companyProfile ?? $user->getOrCreateCompanyProfile();

        // 1. Metric: Lowongan Aktif
        $activeVacanciesQuery = JobVacancy::where('company_id', $company->id)->where('is_active', true);
        $activeVacanciesCount = (clone $activeVacanciesQuery)->count();
        $expiringSoonCount = (clone $activeVacanciesQuery)
            ->whereNotNull('deadline')
            ->where('deadline', '<=', Carbon::now()->addDays(7))
            ->where('deadline', '>=', Carbon::now())
            ->count();

        // 2. Metric: Total Pelamar
        $totalApplicationsQuery = JobApplication::whereHas('jobVacancy', function ($q) use ($company) {
            $q->where('company_id', $company->id);
        });
        $totalApplicants = (clone $totalApplicationsQuery)->count();
        $recentWeekApplicants = (clone $totalApplicationsQuery)
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->count();

        // 3. Metric: Menunggu Ditinjau
        $pendingReviewCount = (clone $totalApplicationsQuery)
            ->whereIn('status', ['pending', 'reviewed'])
            ->count();

        // 4. Metric: Fasilitas Terisi
        $facilities = (array) ($company->accessibility_features ?? []);
        $facilitiesCount = count($facilities);
        $totalMasterFacilities = 3; // Mengacu pada 3 fasilitas terdaftar utama

        // Lowongan Aktif (Top 3)
        $topActiveVacancies = JobVacancy::where('company_id', $company->id)
            ->where('is_active', true)
            ->withCount('applications')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // Aktivitas Pelamar Terbaru
        $recentActivities = JobApplication::whereHas('jobVacancy', function ($q) use ($company) {
            $q->where('company_id', $company->id);
        })
            ->with(['user', 'jobVacancy'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('hrd.dashboard', compact(
            'user',
            'company',
            'activeVacanciesCount',
            'expiringSoonCount',
            'totalApplicants',
            'recentWeekApplicants',
            'pendingReviewCount',
            'facilitiesCount',
            'totalMasterFacilities',
            'topActiveVacancies',
            'recentActivities'
        ));
    }
}
