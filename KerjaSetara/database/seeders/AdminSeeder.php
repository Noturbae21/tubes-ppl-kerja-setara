<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds for Administrator credentials.
     */
    public function run(): void
    {
        // Primary Administrator
        User::updateOrCreate(
            ['email' => 'admin@kerjasetara.id'],
            [
                'name' => 'Admin KerjaSetara',
                'full_name' => 'Super Administrator KerjaSetara',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '+62 811 0000 9999',
                'city' => 'Jakarta, Indonesia',
                'bio' => 'Administrator Utama Sistem KerjaSetara. Mengelola operasional, moderasi lowongan inklusif, dan kepatuhan standar aksesibilitas platform.',
            ]
        );

        // Backup/Legacy Administrator
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin Cadangan',
                'full_name' => 'Staff Administrator KerjaSetara',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '+62 812 0000 8888',
                'city' => 'Jakarta, Indonesia',
                'bio' => 'Administrator Cadangan untuk pengujian multi-role.',
            ]
        );
    }
}
