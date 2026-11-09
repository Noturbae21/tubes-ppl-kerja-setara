<?php

namespace Database\Seeders;

use App\Models\JobVacancy;
use App\Models\User;
use App\Models\UserAccessibilityPreference;
use Illuminate\Database\Seeder;

class AccessibilitySeeder extends Seeder
{
    /**
     * Run the database seeds for accessibility taxonomies and profiles.
     */
    public function run(): void
    {
        // 1. Calibrate Nadia Saraswati profile for exact Figma benchmark
        $nadia = User::where('email', 'nadia@kerjasetara.id')->first();
        if ($nadia) {
            UserAccessibilityPreference::updateOrCreate(
                ['user_id' => $nadia->id],
                [
                    'category' => 'komunikasi',
                    'wheelchair_access' => true,
                    'sign_language' => true,
                    'screen_reader' => false,
                    'quiet_room' => false,
                    'accessible_restroom' => false,
                    'flexible_hours' => false,
                    'notes' => 'Membutuhkan akses kursi roda (ramp/lift) dan fasilitas penerjemah bahasa isyarat (BISINDO) pada sesi onboarding dan rapat formal.',
                    'additional_preferences' => ['kursi_roda', 'bisindo'],
                ]
            );
        }

        // 2. Calibrate test candidate Thomas (Physical)
        $thomas = User::where('email', 'kandidat@example.com')->first();
        if ($thomas) {
            UserAccessibilityPreference::updateOrCreate(
                ['user_id' => $thomas->id],
                [
                    'category' => 'fisik',
                    'wheelchair_access' => true,
                    'accessible_restroom' => true,
                    'adjustable_desk' => true,
                    'accessible_parking' => true,
                    'notes' => 'Pengguna kursi roda aktif, memerlukan ramp landai, pintu lebar, dan toilet khusus disabilitas.',
                ]
            );
        }

        // 3. Calibrate test candidate Mario (Digital / Vision)
        $mario = User::where('email', 'mario@example.com')->first();
        if ($mario) {
            UserAccessibilityPreference::updateOrCreate(
                ['user_id' => $mario->id],
                [
                    'category' => 'digital',
                    'screen_reader' => true,
                    'high_contrast' => true,
                    'flexible_hours' => true,
                    'notes' => 'Tunanetra / low-vision, bekerja mandiri menggunakan screen reader (NVDA/VoiceOver) dan keyboard navigasi.',
                ]
            );
        }

        // 4. Calibrate Key Job Vacancies with Accessibility Facilities
        // UI/UX Designer at PT Teknologi Inovatif (matches Figma mockup 95% benchmark)
        $uiuxJob = JobVacancy::whereHas('company', fn ($q) => $q->where('company_name', 'like', '%Teknologi Inovatif%'))->first()
            ?? JobVacancy::where('title', 'like', '%UI/UX Designer%')->first();
        if ($uiuxJob) {
            $uiuxJob->update([
                'accessibility_tags' => [
                    'ramp',
                    'lift',
                    'ramped_access',
                    'sign_language',
                    'bisindo',
                    'jam_fleksibel',
                    'toilet_khusus',
                ],
            ]);
        }

        // Frontend Developer at Nusa Digital
        $feJob = JobVacancy::whereHas('company', fn ($q) => $q->where('company_name', 'like', '%Nusa Digital%'))->first()
            ?? JobVacancy::where('title', 'like', '%Frontend Developer%')->first();
        if ($feJob) {
            $feJob->update([
                'accessibility_tags' => [
                    'ramp',
                    'lift',
                    'screen_reader',
                    'keyboard_navigation',
                    'wfh_fleksibel',
                ],
            ]);
        }

        // Customer Support at PT Solusi Bersama
        $csJob = JobVacancy::whereHas('company', fn ($q) => $q->where('company_name', 'like', '%Solusi Bersama%'))->first()
            ?? JobVacancy::where('title', 'like', '%Customer Support%')->first();
        if ($csJob) {
            $csJob->update([
                'accessibility_tags' => [
                    'ramp',
                    'lift',
                    'toilet_khusus',
                    'sign_language',
                    'komunikasi_teks',
                ],
            ]);
        }

        // Data Analyst at Arunika Finance
        $daJob = JobVacancy::whereHas('company', fn ($q) => $q->where('company_name', 'like', '%Arunika%'))->first()
            ?? JobVacancy::where('title', 'like', '%Data Analyst%')->first();
        if ($daJob) {
            $daJob->update([
                'accessibility_tags' => [
                    'screen_reader',
                    'keyboard_navigation',
                    'ruang_tenang',
                    'wfh_fleksibel',
                ],
            ]);
        }
    }
}
