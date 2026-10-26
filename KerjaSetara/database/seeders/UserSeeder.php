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
