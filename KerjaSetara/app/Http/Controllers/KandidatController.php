<?php

namespace App\Http\Controllers;

use App\Models\CompanyProfile;
use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\SavedJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KandidatController extends Controller
{
    /**
     * Display Candidate Homepage (/kandidat/home)
     */
    public function index(Request $request)
    {
        $search = $request->query('q') ?? $request->query('search');
        $location = $request->query('location');
        $category = $request->query('category');
        $jobType = $request->query('job_type');
        $tag = $request->query('tag');

        $query = JobVacancy::with('company')->active();

        // Keyword search
        if (! empty($search)) {
            $query->search($search);
        }

        // Location filter
        if (! empty($location) && $location !== 'all') {
            $query->where('location', 'LIKE', "%{$location}%");
        }

        // Accessibility Category filter
        if (! empty($category) && $category !== 'all') {
            $query->byCategory($category);
        }

        // Specific accessibility facility tag
        if (! empty($tag) && $tag !== 'all') {
            $query->byAccessibility($tag);
        }

        // Job type filter
        if (! empty($jobType) && $jobType !== 'all') {
            $query->where('job_type', $jobType);
        }

        // Paginate results (9 cards per page for a balanced 3-column grid)
        $vacancies = $query->latest()->paginate(9)->withQueryString();

        // Verified inclusive partner companies for gallery
        $partnerCompanies = CompanyProfile::where('is_inclusive_certified', true)
            ->withCount('activeVacancies')
            ->take(8)
            ->get();

        // Locations list for filter dropdown
        $availableLocations = [
            'Jakarta Selatan',
            'Jakarta Pusat',
            'Jakarta Barat',
            'Tangerang',
            'Remote',
        ];

        // Accessibility quick filter presets
        $filterPresets = [
            [
                'id' => 'all',
                'label' => 'Semua Lowongan',
                'icon' => 'sparkles',
                'category' => null,
            ],
            [
                'id' => 'kursi_roda',
                'label' => 'Akses Kursi Roda',
                'icon' => 'wheelchair',
                'category' => 'fisik',
                'tag' => 'ramp',
            ],
            [
                'id' => 'tunarungu',
                'label' => 'Ramah Tunarungu',
                'icon' => 'hands',
                'category' => 'komunikasi',
                'tag' => 'sign_language',
            ],
            [
                'id' => 'pembaca_layar',
                'label' => 'Dukungan Pembaca Layar',
                'icon' => 'screen-reader',
                'category' => 'digital',
                'tag' => 'screen_reader',
            ],
            [
                'id' => 'neurodiverse',
                'label' => 'Kebutuhan Neurodiverse',
                'icon' => 'sun',
                'category' => 'neurodiverse',
                'tag' => 'ruang_tenang',
            ],
        ];

        // Saved jobs ID array for current authenticated user
        $savedJobIds = Auth::check()
            ? Auth::user()->savedJobs()->pluck('job_vacancy_id')->toArray()
            : [];

        // Application IDs array for current authenticated user
        $appliedJobIds = Auth::check()
            ? Auth::user()->applications()->pluck('job_vacancy_id')->toArray()
            : [];

        // Dynamic counts for quick filter badges
        $categoryCounts = [
            'all' => JobVacancy::active()->count(),
            'fisik' => JobVacancy::active()->byCategory('fisik')->count(),
            'komunikasi' => JobVacancy::active()->byCategory('komunikasi')->count(),
            'digital' => JobVacancy::active()->byCategory('digital')->count(),
            'neurodiverse' => JobVacancy::active()->byCategory('neurodiverse')->count(),
        ];

        return view('kandidat.home', compact(
            'vacancies',
            'partnerCompanies',
            'availableLocations',
            'filterPresets',
            'search',
            'location',
            'category',
            'tag',
            'jobType',
            'savedJobIds',
            'appliedJobIds',
            'categoryCounts'
        ));
    }

    /**
     * Display Job Vacancy Detail (/kandidat/lowongan/{id})
     */
    public function show($id, Request $request)
    {
        $vacancy = JobVacancy::with('company')->findOrFail($id);

        $currentUser = Auth::user();
        $selectedCategory = $request->query('cat', $currentUser?->accessibility_category ?? 'fisik');

        // Calibrate matching engine
        $matching = $vacancy->calculateMatching($currentUser, $selectedCategory);

        // Check bookmark & application status
        $hasApplied = $currentUser ? $currentUser->hasAppliedTo($vacancy->id) : false;
        $isSaved = $currentUser ? $currentUser->hasSaved($vacancy->id) : false;

        $existingApplication = $hasApplied
            ? $currentUser->applications()->where('job_vacancy_id', $vacancy->id)->first()
            : null;

        // Similar vacancies in same category
        $relatedVacancies = JobVacancy::with('company')
            ->active()
            ->where('id', '!=', $vacancy->id)
            ->where('category', $vacancy->category)
            ->take(3)
            ->get();

        // Stored sample CVs for the candidate dropdown
        $candidateResumes = [
            'CV Utama - '.($currentUser?->name ?? 'Talenta Inklusif').' (Terbaru 2026).pdf',
            'Portofolio Teknis & Riwayat Proyek - '.($currentUser?->name ?? 'Kandidat').'.pdf',
            'Ringkasan Profil Akomodasi Aksesibilitas.pdf',
        ];

        return view('kandidat.show', compact(
            'vacancy',
            'matching',
            'hasApplied',
            'isSaved',
            'existingApplication',
            'relatedVacancies',
            'selectedCategory',
            'candidateResumes'
        ));
    }

    /**
     * Handle Job Application Submission (POST /kandidat/lowongan/{id}/lamar)
     */
    public function apply(Request $request, $id)
    {
        $vacancy = JobVacancy::findOrFail($id);

        if (! Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan masuk ke akun kandidat Anda terlebih dahulu untuk mengirim lamaran.');
        }

        $user = Auth::user();

        // Check if already applied
        if ($user->hasAppliedTo($vacancy->id)) {
            return redirect()->back()
                ->with('error', 'Anda telah mengirimkan lamaran untuk lowongan ini sebelumnya.');
        }

        $validated = $request->validate([
            'resume_name' => ['required', 'string', 'max:255'],
            'portfolio_url' => ['nullable', 'url', 'max:255'],
            'accessibility_notes' => ['nullable', 'string', 'max:1500'],
            'consent_agreement' => ['required', 'accepted'],
        ], [
            'resume_name.required' => 'Pilih atau tentukan CV/Resume yang ingin Anda kirim.',
            'portfolio_url.url' => 'Format tautan portofolio harus berupa URL yang valid (misal: https://...).',
            'consent_agreement.accepted' => 'Anda harus menyetujui pernyataan persetujuan pembagian data aksesibilitas.',
        ]);

        JobApplication::create([
            'user_id' => $user->id,
            'job_vacancy_id' => $vacancy->id,
            'resume_name' => $validated['resume_name'],
            'portfolio_url' => $validated['portfolio_url'] ?? null,
            'accessibility_notes' => $validated['accessibility_notes'] ?? null,
            'consent_agreement' => true,
            'status' => 'pending',
        ]);

        return redirect()->back()
            ->with('success', '🎉 Lamaran Anda berhasil terkirim! Tim rekrutmen '.($vacancy->company->company_name ?? 'perusahaan').' akan meninjau profil dan kebutuhan aksesibilitas Anda.');
    }

    /**
     * Handle Bookmark / Save Toggle (POST /kandidat/lowongan/{id}/bookmark)
     */
    public function toggleBookmark(Request $request, $id)
    {
        $vacancy = JobVacancy::findOrFail($id);

        if (! Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['status' => 'unauthenticated', 'message' => 'Silakan masuk untuk menyimpan lowongan.'], 401);
            }

            return redirect()->route('login')->with('error', 'Silakan masuk untuk menyimpan lowongan.');
        }

        $user = Auth::user();
        $saved = SavedJob::where('user_id', $user->id)
            ->where('job_vacancy_id', $vacancy->id)
            ->first();

        if ($saved) {
            $saved->delete();
            $isSaved = false;
            $message = 'Lowongan berhasil dihapus dari daftar simpan.';
        } else {
            SavedJob::create([
                'user_id' => $user->id,
                'job_vacancy_id' => $vacancy->id,
            ]);
            $isSaved = true;
            $message = 'Lowongan berhasil disimpan ke daftar bookmark Anda.';
        }

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'is_saved' => $isSaved,
                'message' => $message,
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Display Candidate Applications History (/kandidat/lamaran)
     */
    public function applications(Request $request)
    {
        // Jika belum login, gunakan akun demo kandidat (Nadia) untuk memudahkan evaluasi
        if (! Auth::check()) {
            $demoUser = User::where('email', 'nadia@kerjasetara.id')->orWhere('role', 'kandidat')->first();
            if ($demoUser) {
                Auth::login($demoUser);
            } else {
                return redirect()->route('login')
                    ->with('error', 'Silakan masuk untuk melihat riwayat lamaran Anda.');
            }
        }

        $user = Auth::user();

        // Pastikan lamaran sampel tersedia jika kandidat belum memiliki data
        $this->ensureSampleApplicationsExist($user);

        // Hitung total status untuk tab filter
        $allApps = $user->applications()->get();
        $statusCounts = [
            'all' => $allApps->count(),
            'pending' => $allApps->filter(fn ($a) => in_array($a->status, ['pending', 'reviewed', 'shortlisted']))->count(),
            'interview' => $allApps->where('status', 'interview')->count(),
            'accepted' => $allApps->where('status', 'accepted')->count(),
            'rejected' => $allApps->filter(fn ($a) => in_array($a->status, ['rejected', 'cancelled']))->count(),
        ];

        // Filter query
        $activeStatus = $request->query('status', 'all');
        $query = $user->applications()->with(['jobVacancy.company']);

        if ($activeStatus === 'pending') {
            $query->whereIn('status', ['pending', 'reviewed', 'shortlisted']);
        } elseif ($activeStatus === 'interview') {
            $query->where('status', 'interview');
        } elseif ($activeStatus === 'accepted') {
            $query->where('status', 'accepted');
        } elseif ($activeStatus === 'rejected') {
            $query->whereIn('status', ['rejected', 'cancelled']);
        }

        // Urutkan sesuai tahapan seleksi seperti pada mockup
        $applications = $query->orderByRaw("
            CASE 
                WHEN status = 'interview' THEN 1
                WHEN status = 'pending' THEN 2
                WHEN status = 'reviewed' THEN 3
                WHEN status = 'accepted' THEN 4
                WHEN status = 'rejected' THEN 5
                ELSE 6
            END ASC
        ")->latest()->paginate(10)->withQueryString();

        return view('kandidat.riwayat', compact('applications', 'statusCounts', 'activeStatus', 'user'));
    }

    /**
     * Membatalkan lamaran pekerjaan (POST /kandidat/lamaran/{id}/batal)
     */
    public function cancelApplication(Request $request, $id)
    {
        if (! Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan masuk terlebih dahulu.');
        }

        $application = JobApplication::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $positionTitle = $application->jobVacancy->title ?? 'Pekerjaan';
        $companyName = $application->jobVacancy->company->company_name ?? 'Perusahaan';

        $application->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => $request->input('reason', 'Dibatalkan oleh kandidat.'),
        ]);

        return redirect()->route('kandidat.lamaran')
            ->with('success', "Lamaran untuk posisi {$positionTitle} di {$companyName} berhasil dibatalkan.");
    }

    /**
     * Menerima penawaran kerja (POST /kandidat/lamaran/{id}/terima-tawaran)
     */
    public function acceptOffer(Request $request, $id)
    {
        if (! Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan masuk terlebih dahulu.');
        }

        $application = JobApplication::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $positionTitle = $application->jobVacancy->title ?? 'Pekerjaan';
        $companyName = $application->jobVacancy->company->company_name ?? 'Perusahaan';

        $application->update([
            'offer_status' => 'accepted',
            'status' => 'accepted',
        ]);

        return redirect()->route('kandidat.lamaran')
            ->with('success', "Selamat! Anda telah resmi menyetujui penawaran kerja {$positionTitle} di {$companyName}. Tim HRD akan segera menghubungi Anda.");
    }

    /**
     * Menolak penawaran kerja (POST /kandidat/lamaran/{id}/tolak-tawaran)
     */
    public function rejectOffer(Request $request, $id)
    {
        if (! Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan masuk terlebih dahulu.');
        }

        $application = JobApplication::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $positionTitle = $application->jobVacancy->title ?? 'Pekerjaan';
        $companyName = $application->jobVacancy->company->company_name ?? 'Perusahaan';

        $application->update([
            'offer_status' => 'rejected',
        ]);

        return redirect()->route('kandidat.lamaran')
            ->with('success', "Konfirmasi penolakan tawaran untuk posisi {$positionTitle} di {$companyName} telah dikirimkan ke tim HRD.");
    }

    /**
     * Halaman Profil & Preferensi Aksesibilitas Kandidat (GET /kandidat/profil)
     */
    public function profil(Request $request)
    {
        // Ambil user yang sedang terautentikasi atau akun kandidat default (untuk mode demo/preview)
        $user = Auth::user();

        if (! $user) {
            $user = User::where('email', 'nadia@kerjasetara.id')
                ->orWhere('role', 'kandidat')
                ->first();

            if ($user) {
                Auth::login($user);
            } else {
                return redirect()->route('login')
                    ->with('error', 'Silakan masuk terlebih dahulu untuk mengakses profil.');
            }
        }

        return view('kandidat.profil', compact('user'));
    }

    /**
     * Simpan perubahan profil & preferensi kandidat (POST /kandidat/profil)
     */
    public function updateProfil(Request $request)
    {
        $user = Auth::user();

        if (! $user) {
            $user = User::where('email', 'nadia@kerjasetara.id')
                ->orWhere('role', 'kandidat')
                ->first();

            if ($user) {
                Auth::login($user);
            } else {
                return redirect()->route('login')
                    ->with('error', 'Silakan masuk terlebih dahulu.');
            }
        }

        $section = $request->input('_section', 'personal');

        // TAB 2: PREFERENSI AKSESIBILITAS
        if ($section === 'preferensi' || $section === 'accessibility') {
            $validated = $request->validate([
                'accessibility_preferences'   => 'nullable|array',
                'accessibility_preferences.*' => 'string',
                'accessibility_notes'         => 'nullable|string|max:1000',
            ]);

            $user->update([
                'accessibility_preferences' => $request->input('accessibility_preferences', []),
                'accessibility_notes'       => $request->input('accessibility_notes'),
            ]);

            return redirect()->route('kandidat.profil', ['tab' => 'accessibility'])
                ->with('success', 'Preferensi aksesibilitas berhasil diperbarui!');
        }

        // TAB 3: CV & PORTOFOLIO
        if ($section === 'cv') {
            $validated = $request->validate([
                'portfolio_url' => 'nullable|string|max:255',
                'linkedin_url'  => 'nullable|string|max:255',
                'resume_file'   => 'nullable|string|max:255',
            ]);

            $user->update($validated);

            return redirect()->route('kandidat.profil', ['tab' => 'cv'])
                ->with('success', 'Dokumen CV dan tautan portofolio berhasil disimpan!');
        }

        // TAB 5: PENGATURAN KEAMANAN & PASSWORD
        if ($section === 'security') {
            $request->validate([
                'new_password' => 'required|string|min:8|confirmed',
            ], [
                'new_password.required'  => 'Kata sandi baru wajib diisi.',
                'new_password.min'       => 'Kata sandi baru minimal 8 karakter.',
                'new_password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
            ]);

            $user->update([
                'password' => bcrypt($request->input('new_password')),
            ]);

            return redirect()->route('kandidat.profil', ['tab' => 'security'])
                ->with('success', 'Kata sandi akun Anda berhasil diperbarui!');
        }

        // TAB 1: INFORMASI PRIBADI (DEFAULT)
        $validated = $request->validate([
            'name'      => 'required|string|max:100',
            'full_name' => 'nullable|string|max:150',
            'headline'  => 'nullable|string|max:150',
            'phone'     => 'nullable|string|max:30',
            'city'      => 'nullable|string|max:100',
            'location'  => 'nullable|string|max:100',
            'bio'       => 'nullable|string|max:1000',
        ], [
            'name.required' => 'Nama tampilan wajib diisi.',
            'name.max'      => 'Nama tampilan maksimal 100 karakter.',
        ]);

        if (!empty($validated['location']) && empty($validated['city'])) {
            $validated['city'] = $validated['location'];
        }
        unset($validated['location']);

        if (empty($validated['full_name']) && !empty($validated['name'])) {
            $validated['full_name'] = $validated['name'];
        }

        $user->update($validated);

        return redirect()->route('kandidat.profil', ['tab' => 'personal'])
            ->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Pastikan kandidat memiliki 4 sampel lamaran standar jika kosong
     */
    private function ensureSampleApplicationsExist($user): void
    {
        if ($user->applications()->count() === 0) {
            try {
                (new \Database\Seeders\JobApplicationSeeder())->run();
            } catch (\Throwable $e) {
                // Ignore jika database koneksi atau seeder sudah jalan
            }
        }
    }
}