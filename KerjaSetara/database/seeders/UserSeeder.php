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

        // Existing legacy accounts (preserved)
        User::firstOrCreate(
            ['email' => 'hrd@example.com'],
            [
                'name' => 'Ujang HRD',
                'full_name' => 'Ujang HRD',
                'password' => Hash::make('password'),
                'role' => 'hrd',
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
