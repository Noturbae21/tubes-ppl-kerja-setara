<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserAccessibilityPreference;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin account (pre-seeded if not already created by AdminSeeder)
        User::firstOrCreate(
            ['email' => 'admin@kerjasetara.id'],
            [
                'name' => 'Admin KerjaSetara',
                'full_name' => 'Administrator KerjaSetara',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Candidate Nadia (Active session mockup profile)
        $nadia = User::firstOrCreate(
            ['email' => 'nadia@kerjasetara.id'],
            [
                'name' => 'Nadia',
                'full_name' => 'Nadia Saraswati',
                'headline' => 'UI/UX Designer & Accessibility Advocate',
                'password' => Hash::make('password'),
                'role' => 'kandidat',
                'phone' => '+62 812 3456 7890',
                'city' => 'Jakarta, Indonesia',
                'bio' => 'UI/UX Designer yang berfokus pada desain inklusif dan aksesibilitas digital untuk semua kalangan talenta.',
                'portfolio_url' => 'https://behance.net/nadiasaras',
                'linkedin_url' => 'https://linkedin.com/in/nadiasaraswati',
                'resume_file' => 'CV_Nadia_Saraswati_Inklusif_2026.pdf',
            ]
        );

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
                'notes' => 'Membutuhkan akses kursi roda dan fasilitas penerjemah bahasa isyarat.',
                'additional_preferences' => ['kursi_roda', 'bisindo'],
            ]
        );

        // Demo HRD account (hrd@kerjasetara.id)
        $hrdKerjaSetara = User::firstOrCreate(
            ['email' => 'hrd@kerjasetara.id'],
            [
                'name' => 'Budi HRD',
                'full_name' => 'Budi Pratama (HRD)',
                'headline' => 'Lead People & Culture PT Solusi Inklusif Indonesia',
                'password' => Hash::make('password'),
                'role' => 'hrd',
                'phone' => '+62 812 9876 5432',
                'city' => 'Jakarta Selatan',
            ]
        );

        $companyInklusif = \App\Models\CompanyProfile::firstOrCreate(
            ['user_id' => $hrdKerjaSetara->id],
            [
                'company_name' => 'PT Solusi Inklusif Indonesia',
                'description' => 'Perusahaan teknologi yang berkomitmen penuh pada kesetaraan kerja dan inklusi disabilitas.',
                'industry' => 'Teknologi Informasi & Konsultan',
                'location' => 'Jakarta Selatan',
                'accessibility_features' => ['ramp', 'toilet_khusus', 'screen_reader'],
                'logo_path' => null,
                'website' => 'https://solusi-inklusif.test',
                'phone' => '+62 21 555 1234',
                'is_inclusive_certified' => true,
                'employee_count' => 150,
            ]
        );

        \App\Models\JobVacancy::firstOrCreate(
            [
                'company_id' => $companyInklusif->id,
                'title' => 'Digital Accessibility Specialist',
            ],
            [
                'description' => 'Mengevaluasi dan memastikan aplikasi web dan mobile memenuhi standar WCAG 2.1 Level AA.',
                'location' => 'Jakarta Selatan',
                'job_type' => 'hybrid',
                'category' => 'digital',
                'accessibility_tags' => ['screen_reader', 'keyboard_navigation', 'ruang_tenang'],
                'salary_min' => 10000000,
                'salary_max' => 18000000,
                'is_active' => true,
                'deadline' => now()->addDays(30),
            ]
        );

        \App\Models\JobVacancy::firstOrCreate(
            [
                'company_id' => $companyInklusif->id,
                'title' => 'Frontend Developer (Inclusive Web)',
            ],
            [
                'description' => 'Mengembangkan antarmuka web modern dengan fokus pada aksesibilitas dan performa tinggi.',
                'location' => 'Jakarta Selatan',
                'job_type' => 'remote',
                'category' => 'digital',
                'accessibility_tags' => ['wfh_fleksibel', 'screen_reader', 'kursi_roda'],
                'salary_min' => 9000000,
                'salary_max' => 16000000,
                'is_active' => true,
                'deadline' => now()->addDays(45),
            ]
        );

        // Existing legacy HRD accounts (preserved)
        $hrdUser = User::firstOrCreate(
            ['email' => 'hrd@example.com'],
            [
                'name' => 'Ujang HRD',
                'full_name' => 'Ujang HRD',
                'headline' => 'Talent Acquisition PT Perusahaan Inklusif',
                'password' => Hash::make('password'),
                'role' => 'hrd',
                'phone' => '+62 813 1234 5678',
                'city' => 'Jakarta Selatan',
            ]
        );

        \App\Models\CompanyProfile::firstOrCreate(
            ['user_id' => $hrdUser->id],
            [
                'company_name' => 'PT Perusahaan Inklusif',
                'description' => 'Profil perusahaan contoh untuk HRD.',
                'industry' => 'Teknologi',
                'location' => 'Jakarta Selatan',
                'accessibility_features' => ['ramp', 'toilet_khusus', 'screen_reader'],
                'logo_path' => null,
                'website' => 'https://perusahaan-inklusif.test',
                'phone' => '+62 21 555 5678',
                'nib_file' => 'NIB_Perusahaan_Inklusif.pdf',
                'npwp_file' => 'NPWP_Perusahaan_Inklusif.pdf',
                'is_inclusive_certified' => true,
                'employee_count' => 50,
            ]
        );

        $thomas = User::firstOrCreate(
            ['email' => 'kandidat@example.com'],
            [
                'name' => 'Thomas Kandidat',
                'full_name' => 'Thomas Kandidat',
                'password' => Hash::make('password'),
                'role' => 'kandidat',
            ]
        );

        UserAccessibilityPreference::updateOrCreate(
            ['user_id' => $thomas->id],
            [
                'category' => 'fisik',
                'wheelchair_access' => true,
                'accessible_restroom' => true,
                'adjustable_desk' => true,
                'accessible_parking' => true,
            ]
        );

        $mario = User::firstOrCreate(
            ['email' => 'mario@example.com'],
            [
                'name' => 'Mario Runtuh',
                'full_name' => 'Mario Runtuh',
                'password' => Hash::make('password'),
                'role' => 'kandidat',
            ]
        );

        UserAccessibilityPreference::updateOrCreate(
            ['user_id' => $mario->id],
            [
                'category' => 'digital',
                'screen_reader' => true,
                'high_contrast' => true,
                'flexible_hours' => true,
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin Lama',
                'full_name' => 'Admin Lama',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );
    }
}
