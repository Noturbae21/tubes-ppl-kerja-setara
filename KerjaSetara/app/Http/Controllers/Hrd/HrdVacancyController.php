<?php

namespace App\Http\Controllers\Hrd;

use App\Http\Controllers\Controller;
use App\Models\JobVacancy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HrdVacancyController extends Controller
{
    /**
     * Display a listing of the HR company's vacancies.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $company = $user->companyProfile;

        if (! $company) {
            return redirect()->route('hrd.pengaturan.index')
                ->with('error', 'Profil perusahaan Anda belum lengkap.');
        }

        $tab = $request->query('tab', 'semua');
        $search = $request->query('search');

        $baseQuery = JobVacancy::where('company_id', $company->id);

        $counts = [
            'semua' => (clone $baseQuery)->count(),
            'aktif' => (clone $baseQuery)->where('is_active', true)->count(),
            'kedaluwarsa' => (clone $baseQuery)->where(function ($q) {
                $q->where('is_active', false)
                    ->orWhere(function ($sq) {
                        $sq->whereNotNull('deadline')->where('deadline', '<', now());
                    });
            })->count(),
        ];

        $vacanciesQuery = (clone $baseQuery)->withCount('applications');

        if ($search) {
            $vacanciesQuery->where('title', 'like', "%{$search}%");
        }

        if ($tab === 'aktif') {
            $vacanciesQuery->where('is_active', true);
        } elseif ($tab === 'kedaluwarsa') {
            $vacanciesQuery->where(function ($q) {
                $q->where('is_active', false)
                    ->orWhere(function ($sq) {
                        $sq->whereNotNull('deadline')->where('deadline', '<', now());
                    });
            });
        }

        $vacancies = $vacanciesQuery->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('hrd.vacancies.index', compact('company', 'vacancies', 'counts', 'tab', 'search'));
    }

    /**
     * Show the form for creating a new job vacancy.
     */
    public function create()
    {
        $user = Auth::user();
        $company = $user->companyProfile;

        return view('hrd.vacancies.create', compact('company'));
    }

    /**
     * Store a newly created job vacancy.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $company = $user->companyProfile;

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'job_type' => ['required', 'string', 'max:50'],
            'location' => ['required', 'string', 'max:255'],
            'salary_estimate' => ['nullable', 'string', 'max:100'],
            'salary_min' => ['nullable', 'numeric'],
            'salary_max' => ['nullable', 'numeric'],
            'description' => ['required', 'string'],
            'qualifications' => ['nullable', 'string'],
            'accessibility_tags' => ['nullable', 'array'],
            'accessibility_tags.*' => ['string'],
            'deadline' => ['nullable', 'date'],
            'is_active' => ['nullable', 'boolean'],
            'action' => ['nullable', 'string', 'in:draft,publish'],
        ]);

        $isActive = ($request->input('action') === 'draft') ? false : $request->boolean('is_active', true);

        // Parsing salary estimate jika diinput teks seperti "Rp 8-12 juta / bulan"
        $salaryMin = $validated['salary_min'] ?? null;
        $salaryMax = $validated['salary_max'] ?? null;
        if (! empty($validated['salary_estimate']) && ! $salaryMin && ! $salaryMax) {
            preg_match_all('/\d+/', str_replace('.', '', $validated['salary_estimate']), $matches);
            if (! empty($matches[0])) {
                $numbers = array_map('intval', $matches[0]);
                if (count($numbers) >= 2) {
                    $multiplier = str_contains(strtolower($validated['salary_estimate']), 'juta') ? 1000000 : 1;
                    $salaryMin = $numbers[0] * $multiplier;
                    $salaryMax = $numbers[1] * $multiplier;
                } elseif (count($numbers) === 1) {
                    $multiplier = str_contains(strtolower($validated['salary_estimate']), 'juta') ? 1000000 : 1;
                    $salaryMin = $numbers[0] * $multiplier;
                }
            }
        }

        // Gabungkan kualifikasi ke dalam deskripsi jika ada
        $fullDescription = $validated['description'];
        if (! empty($validated['qualifications'])) {
            $fullDescription .= "\n\nKualifikasi:\n".$validated['qualifications'];
        }

        $vacancy = JobVacancy::create([
            'company_id' => $company->id,
            'title' => $validated['title'],
            'description' => $fullDescription,
            'location' => $validated['location'],
            'job_type' => $validated['job_type'],
            'category' => 'digital', // Kategori default yang ramah teknologi inklusif
            'accessibility_tags' => $validated['accessibility_tags'] ?? ['kursi_roda'],
            'salary_min' => $salaryMin,
            'salary_max' => $salaryMax,
            'is_active' => $isActive,
            'deadline' => ! empty($validated['deadline']) ? Carbon::parse($validated['deadline']) : Carbon::now()->addDays(30),
        ]);

        $message = $isActive
            ? "Lowongan '{$vacancy->title}' berhasil dipublikasikan dan kini dapat dilamar oleh kandidat!"
            : "Draf lowongan '{$vacancy->title}' berhasil disimpan.";

        return redirect()->route('hrd.lowongan.index')->with('success', $message);
    }

    /**
     * Return vacancy data for edit modal (JSON or redirect).
     */
    public function edit(JobVacancy $vacancy)
    {
        $this->authorizeCompanyVacancy($vacancy);

        return response()->json([
            'id' => $vacancy->id,
            'title' => $vacancy->title,
            'job_type' => $vacancy->job_type,
            'salary_estimate' => $vacancy->formatted_salary,
            'salary_min' => $vacancy->salary_min,
            'salary_max' => $vacancy->salary_max,
            'accessibility_tags' => (array) ($vacancy->accessibility_tags ?? []),
            'is_active' => $vacancy->is_active,
            'deadline' => $vacancy->deadline ? $vacancy->deadline->format('Y-m-d') : null,
        ]);
    }

    /**
     * Update an existing job vacancy.
     */
    public function update(Request $request, JobVacancy $vacancy)
    {
        $this->authorizeCompanyVacancy($vacancy);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'job_type' => ['required', 'string', 'max:50'],
            'salary_estimate' => ['nullable', 'string', 'max:100'],
            'accessibility_tags' => ['nullable', 'array'],
            'accessibility_tags.*' => ['string'],
        ]);

        $updateData = [
            'title' => $validated['title'],
            'job_type' => $validated['job_type'],
        ];

        if (isset($validated['accessibility_tags'])) {
            $updateData['accessibility_tags'] = $validated['accessibility_tags'];
        }

        // Parsing salary estimate
        if (! empty($validated['salary_estimate'])) {
            preg_match_all('/\d+/', str_replace('.', '', $validated['salary_estimate']), $matches);
            if (! empty($matches[0])) {
                $numbers = array_map('intval', $matches[0]);
                if (count($numbers) >= 2) {
                    $multiplier = str_contains(strtolower($validated['salary_estimate']), 'juta') ? 1000000 : 1;
                    $updateData['salary_min'] = $numbers[0] * $multiplier;
                    $updateData['salary_max'] = $numbers[1] * $multiplier;
                }
            }
        }

        $vacancy->update($updateData);

        return redirect()->route('hrd.lowongan.index')
            ->with('success', "Perubahan lowongan '{$vacancy->title}' berhasil disimpan.");
    }

    /**
     * Toggle active / paused status of the vacancy.
     */
    public function toggleStatus(Request $request, JobVacancy $vacancy)
    {
        $this->authorizeCompanyVacancy($vacancy);

        $newStatus = ! $vacancy->is_active;
        $vacancy->update(['is_active' => $newStatus]);

        $statusLabel = $newStatus ? 'diaktifkan kembali' : 'ditutup sementara';

        return redirect()->route('hrd.lowongan.index')
            ->with('success', "Lowongan '{$vacancy->title}' berhasil {$statusLabel}.");
    }

    /**
     * Remove / archive the specified vacancy.
     */
    public function destroy(JobVacancy $vacancy)
    {
        $this->authorizeCompanyVacancy($vacancy);

        $title = $vacancy->title;
        $vacancy->delete();

        return redirect()->route('hrd.lowongan.index')
            ->with('success', "Lowongan '{$title}' dan arsip pelamarnya berhasil dihapus.");
    }

    /**
     * Ensure the authenticated HR user owns the vacancy.
     */
    protected function authorizeCompanyVacancy(JobVacancy $vacancy): void
    {
        $user = Auth::user();
        if ($vacancy->company_id !== $user->companyProfile?->id) {
            abort(403, 'Akses ditolak. Anda tidak memiliki wewenang atas lowongan perusahaan lain.');
        }
    }
}
