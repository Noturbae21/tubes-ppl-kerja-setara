<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,           // Dedicated Admin credentials
            UserSeeder::class,            // Users (Nadia, HRD, candidates)
            CompanyProfileSeeder::class,  // Creates HRD users + company profiles
            JobVacancySeeder::class,      // Needs company_profiles
            JobApplicationSeeder::class,  // Seeds sample applications (Nadia & candidates)
            FacilitySeeder::class,        // Master Data Fasilitas Aksesibilitas
            AccessibilitySeeder::class,   // Calibrates accessibility preferences & job matching
            TestimonialSeeder::class,     // Independent
        ]);
    }
}
