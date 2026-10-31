<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin account
        User::create([
            'name' => 'Admin KerjaSetara',
            'full_name' => 'Administrator KerjaSetara',
            'email' => 'admin@kerjasetara.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Candidate Nadia (Active session mockup profile)
        User::create([
            'name' => 'Nadia',
            'full_name' => 'Nadia Saraswati',
            'headline' => 'UI/UX Designer & Accessibility Advocate',
            'email' => 'nadia@kerjasetara.id',
            'password' => Hash::make('password'),
            'role' => 'kandidat',
            'accessibility_category' => 'komunikasi',
            'phone' => '+62 812 3456 7890',
            'city' => 'Jakarta, Indonesia',
            'bio' => 'UI/UX Designer yang berfokus pada desain inklusif dan aksesibilitas digital untuk semua kalangan talenta.',
            'accessibility_preferences' => [
                'kursi_roda',
                'toilet_disabilitas',
                'bisindo',
                'screen_reader',
                'quiet_room',
                'jam_fleksibel',
            ],
            'accessibility_notes' => 'Membutuhkan pencahayaan sedang dan software pembaca layar aktif.',
            'portfolio_url' => 'https://behance.net/nadiasaras',
            'linkedin_url' => 'https://linkedin.com/in/nadiasaraswati',
            'resume_file' => 'CV_Nadia_Saraswati_Inklusif_2026.pdf',
        ]);

        // Existing legacy accounts (preserved)
        User::create([
            'name' => 'Ujang HRD',
            'full_name' => 'Ujang HRD',
            'email' => 'hrd@example.com',
            'password' => bcrypt('password'),
            'role' => 'hrd',
        ]);

        User::create([
            'name' => 'Thomas Kandidat',
            'full_name' => 'Thomas Kandidat',
            'email' => 'kandidat@example.com',
            'password' => bcrypt('password'),
            'role' => 'kandidat',
            'accessibility_category' => 'fisik',
        ]);

        User::create([
            'name' => 'Mario Runtuh',
            'full_name' => 'Mario Runtuh',
            'email' => 'mario@example.com',
            'password' => bcrypt('password'),
            'role' => 'kandidat',
            'accessibility_category' => 'digital',
        ]);

        User::create([
            'name' => 'Admin Lama',
            'full_name' => 'Admin Lama',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);
    }
}
