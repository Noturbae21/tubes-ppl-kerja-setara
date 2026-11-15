<?php

namespace App\Http\Controllers;

use App\Models\JobVacancy;
use App\Models\User;
use App\Models\UserAccessibilityPreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class JobController extends Controller
{
    /**
     * Display the specified job vacancy with dynamic accessibility matching.
     */
    public function show(int|string $id, Request $request): View
    {
        $vacancy = JobVacancy::with('company')->findOrFail($id);

        /** @var User|null $user */
        $user = Auth::user();

        // 1. Fetch user's accessibility preferences using the defined Eloquent relation
        /** @var UserAccessibilityPreference|null $preference */
        $preference = $user?->accessibilityPreference;

        // If not logged in, or preference not yet created, allow category override or default
        $categoryOverride = $request->query('cat');

        if ($preference) {
            $userPreferences = $preference->getActivePreferencesWithMetadata();
        } else {
            // Fallback for guest or newly registered candidate
            $category = $categoryOverride ?: ($vacancy->category ?: 'fisik');
            $dummyPref = new UserAccessibilityPreference(['category' => $category]);
            $userPreferences = $dummyPref->getActivePreferencesWithMetadata();
        }

        // 2. Programmatically compare against the job vacancy's available facilities
        $jobFacilities = array_map('strtolower', (array) ($vacancy->accessibility_tags ?? []));

        if ($vacancy->relationLoaded('facilities') || $vacancy->facilities()->exists()) {
            $attachedDbFacilities = $vacancy->facilities->pluck('name')->map(fn ($n) => strtolower($n))->all();
            $jobFacilities = array_unique(array_merge($jobFacilities, $attachedDbFacilities));
        }

        if ($vacancy->company && is_array($vacancy->company->accessibility_features)) {
            $jobFacilities = array_unique(array_merge(
                $jobFacilities,
                array_map('strtolower', $vacancy->company->accessibility_features)
            ));
        }

        $breakdown = [];
        $matchedFacilities = 0;
        $totalUserPreferences = count($userPreferences);

        foreach ($userPreferences as $key => $pref) {
            $tags = $pref['tags'] ?? [$key];
            $isAvailable = false;

            foreach ($tags as $tag) {
                if (in_array(strtolower($tag), $jobFacilities, true)) {
                    $isAvailable = true;
                    break;
                }
            }

            if ($isAvailable) {
                $matchedFacilities++;
            }

            $breakdown[] = [
                'key' => $key,
                'feature' => $pref['label'] ?? ucwords(str_replace('_', ' ', $key)),
                'status' => $isAvailable ? 'Tersedia' : 'Tidak Disebutkan',
                'is_available' => $isAvailable,
                'icon' => $pref['icon'] ?? 'check-circle',
            ];
        }

        // 3. Dynamic percentage formula: (Matched Facilities / Total User Preferences) * 100
        if ($totalUserPreferences > 0) {
            $matchPercentage = (int) round(($matchedFacilities / $totalUserPreferences) * 100);
        } else {
            $matchPercentage = 85;
        }

        // Determine status badges, theme ring color and text
        if ($matchPercentage >= 90) {
            $matchStatus = 'Sangat Cocok';
            $badgeColor = 'text-emerald-700 bg-emerald-50 border-emerald-200';
            $ringColor = '#10B981';
        } elseif ($matchPercentage >= 70) {
            $matchStatus = 'Cocok';
            $badgeColor = 'text-blue-700 bg-blue-50 border-blue-200';
            $ringColor = '#2563EB';
        } else {
            $matchStatus = 'Cukup Cocok';
            $badgeColor = 'text-amber-700 bg-amber-50 border-amber-200';
            $ringColor = '#F59E0B';
        }

        // Backward compatibility structures for existing components
        $matching = [
            'percentage' => $matchPercentage,
            'badge' => $matchStatus,
            'badge_color' => $badgeColor,
            'ring_color' => $ringColor,
            'breakdown' => $breakdown,
            'system_breakdown' => $breakdown,
            'candidate_category' => $preference?->category ?? ($categoryOverride ?: 'fisik'),
        ];

        // Check bookmark & application status
        $hasApplied = $user ? $user->hasAppliedTo($vacancy->id) : false;
        $isSaved = $user ? $user->hasSaved($vacancy->id) : false;

        $existingApplication = $hasApplied
            ? $user->applications()->where('job_vacancy_id', $vacancy->id)->first()
            : null;

        // Similar vacancies in same category
        $relatedVacancies = JobVacancy::with('company')
            ->active()
            ->where('id', '!=', $vacancy->id)
            ->where('category', $vacancy->category)
            ->take(3)
            ->get();

        $candidateResumes = [
            'CV Utama - '.($user?->name ?? 'Talenta Inklusif').' (Terbaru 2026).pdf',
            'Portofolio Teknis & Riwayat Proyek - '.($user?->name ?? 'Kandidat').'.pdf',
            'Ringkasan Profil Akomodasi Aksesibilitas.pdf',
        ];

        return view('kandidat.show', compact(
            'vacancy',
            'matchPercentage',
            'breakdown',
            'matchStatus',
            'badgeColor',
            'ringColor',
            'matching',
            'hasApplied',
            'isSaved',
            'existingApplication',
            'relatedVacancies',
            'candidateResumes'
        ));
    }
}
