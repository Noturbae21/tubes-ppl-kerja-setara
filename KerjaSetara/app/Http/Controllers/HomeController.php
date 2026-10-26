<?php

namespace App\Http\Controllers;

use App\Models\CompanyProfile;
use App\Models\JobVacancy;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Statistics
        $stats = [
            'companies' => CompanyProfile::where('is_inclusive_certified', true)->count(),
            'candidates' => User::where('role', 'kandidat')->count(),
            'vacancies' => JobVacancy::where('is_active', true)->count(),
        ];

        // Featured vacancies for landing page
        $featuredVacancies = JobVacancy::with('company')
            ->active()
            ->latest()
            ->take(6)
            ->get();

        // Testimonials carousel
        $testimonials = Testimonial::active()->get();

        // Accessibility filter data
        $filterCategory = $request->get('category', 'fisik');
        $searchQuery = $request->get('search', '');

        $filteredVacancies = JobVacancy::with('company')
            ->active()
            ->when($filterCategory, fn ($q) => $q->byCategory($filterCategory))
            ->when($searchQuery, fn ($q) => $q->search($searchQuery))
            ->take(6)
            ->get();

        return view('pages.index', compact(
            'stats',
            'featuredVacancies',
            'testimonials',
            'filterCategory',
            'searchQuery',
            'filteredVacancies'
        ));
    }
}
