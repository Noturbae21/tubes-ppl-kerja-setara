<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,           // Users first (no dependencies)
            CompanyProfileSeeder::class, // Creates HRD users + company profiles
            JobVacancySeeder::class,     // Needs company_profiles
            TestimonialSeeder::class,    // Independent
        ]);
    }
}
