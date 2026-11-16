<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\CompanyProfile;
use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\User;
use App\Models\UserAccessibilityPreference;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class JobApplicationSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Data 6 Perusahaan Mitra sesuai Desain Figma (Verifikasi Legalitas Perusahaan)
        $companiesData = [
            [
                'email' => 'hrd@teknologiinovatif.test',
                'name' => 'Dina Pratama',
                'company_name' => 'PT Teknologi Inovatif',
                'location' => 'Jl. Contoh Digital No. 12, Jakarta Selatan',
                'phone' => '021 555 2026',
                'website' => 'https://teknologiinovatif.example',
                'nib_file' => 'nib_teknologi_inovatif_contoh.pdf',
                'npwp_file' => 'npwp_teknologi_inovatif_contoh.pdf',
                'industry' => 'Teknologi',
                'description' => 'Perusahaan pengembang solusi digital inklusif dan ramah disabilitas.',
                'features' => ['kursi_roda', 'toilet_disabilitas', 'bisindo'],
                'verification_status' => 'pending',
                'created_at' => Carbon::create(2026, 10, 5, 9, 20, 0),
            ],
            [
                'email' => 'hrd@nusadigital.test',
                'name' => 'HR Nusa Digital',
                'company_name' => 'PT Nusa Digital',
                'location' => 'Jl. Ilustrasi No. 8, Bandung',
                'phone' => '022 456 7890',
                'website' => 'https://nusadigital.example',
                'nib_file' => 'nib_nusa_digital_contoh.pdf',
                'npwp_file' => 'npwp_nusa_digital_contoh.pdf',
                'industry' => 'Teknologi',
                'description' => 'Agensi digital terkemuka di Bandung dengan lingkungan kerja kolaboratif dan adaptif.',
                'features' => ['ramp', 'lift', 'wfh_fleksibel'],
                'verification_status' => 'pending',
                'created_at' => Carbon::create(2026, 10, 4, 14, 35, 0),
            ],
            [
                'email' => 'hrd@solusibersama.test',
                'name' => 'HR PT Solusi Bersama',
                'company_name' => 'PT Solusi Bersama',
                'location' => 'Jl. Contoh Setara No. 5, Jakarta Pusat',
                'phone' => '021 789 0123',
                'website' => 'https://solusibersama.example',
                'nib_file' => 'nib_solusi_bersama_contoh.pdf',
                'npwp_file' => 'npwp_solusi_bersama_contoh.pdf',
                'industry' => 'Jasa',
                'description' => 'Penyedia layanan operasional ramah talenta inklusif nasional.',
                'features' => ['ramp', 'lift', 'sign_language', 'toilet_khusus'],
                'verification_status' => 'pending',
                'created_at' => Carbon::create(2026, 10, 4, 11, 10, 0),
            ],
            [
                'email' => 'hrd@arunikafinance.test',
                'name' => 'HR Arunika Finance',
                'company_name' => 'PT Arunika Finance',
                'location' => 'Jl. Contoh Usaha No. 21, Surabaya',
                'phone' => '031 345 6789',
                'website' => 'https://arunikafinance.example',
                'nib_file' => 'nib_arunika_finance_contoh.pdf',
                'npwp_file' => 'npwp_arunika_finance_contoh.pdf',
                'industry' => 'Keuangan',
                'description' => 'Lembaga keuangan modern yang membuka akses luas bagi talenta disabilitas analitis.',
                'features' => ['screen_reader', 'keyboard_navigation', 'ruang_tenang'],
                'verification_status' => 'verified',
                'verified_at' => Carbon::create(2026, 10, 4, 10, 0, 0),
                'created_at' => Carbon::create(2026, 10, 3, 16, 0, 0),
            ],
            [
                'email' => 'hrd@ceritastudio.test',
                'name' => 'HR Cerita Studio',
                'company_name' => 'PT Cerita Studio',
                'location' => 'Jl. Ilustrasi Kreatif No. 3, Yogyakarta',
                'phone' => '0274 567 890',
                'website' => 'https://ceritastudio.example',
                'nib_file' => 'nib_cerita_studio_contoh.pdf',
                'npwp_file' => 'npwp_cerita_studio_contoh.pdf',
                'industry' => 'Kreatif',
                'description' => 'Studio kreatif dan produksi konten digital ramah talenta neurodiverse.',
                'features' => ['ruang_tenang', 'wfh_fleksibel'],
                'verification_status' => 'verified',
                'verified_at' => Carbon::create(2026, 10, 3, 11, 30, 0),
                'created_at' => Carbon::create(2026, 10, 2, 10, 45, 0),
            ],
            [
                'email' => 'hrd@ruangtumbuh.test',
                'name' => 'HR Ruang Tumbuh',
                'company_name' => 'PT Ruang Tumbuh',
                'location' => 'Jl. Contoh Tumbuh No. 7, Jakarta Barat',
                'phone' => '021 890 1234',
                'website' => 'https://ruangtumbuh.example',
                'nib_file' => 'nib_ruang_tumbuh_contoh.pdf',
                'npwp_file' => 'npwp_ruang_tumbuh_contoh.pdf',
                'industry' => 'Konsultasi',
                'description' => 'Lembaga konsultasi pengembangan organisasi dan talenta inklusif.',
                'features' => ['lift', 'sign_language'],
                'verification_status' => 'rejected',
                'rejection_reason' => 'Izin tidak sesuai',
                'verified_at' => Carbon::create(2026, 10, 1, 12, 0, 0),
                'created_at' => Carbon::create(2026, 10, 1, 8, 15, 0),
            ],
        ];

        $companies = [];
        foreach ($companiesData as $c) {
            $user = User::updateOrCreate(
                ['email' => $c['email']],
                [
                    'name' => $c['name'],
                    'full_name' => $c['name'],
                    'password' => Hash::make('password'),
                    'role' => 'hrd',
                ]
            );

            $profile = CompanyProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'company_name' => $c['company_name'],
                    'industry' => $c['industry'],
                    'location' => $c['location'],
                    'phone' => $c['phone'] ?? null,
                    'website' => $c['website'] ?? 'https://example.com',
                    'nib_file' => $c['nib_file'] ?? null,
                    'npwp_file' => $c['npwp_file'] ?? null,
                    'description' => $c['description'],
                    'employee_count' => 250,
                    'accessibility_features' => $c['features'],
                    'is_inclusive_certified' => ($c['verification_status'] ?? 'pending') === 'verified',
                    'verification_status' => $c['verification_status'] ?? 'pending',
                    'verified_at' => $c['verified_at'] ?? null,
                    'rejection_reason' => $c['rejection_reason'] ?? null,
                    'created_at' => $c['created_at'] ?? now(),
                ]
            );

            $companies[$c['company_name']] = $profile;
            $companies[preg_replace('/^PT\s+/i', '', $c['company_name'])] = $profile;
        }

        // 2. Data Lowongan Kerja Perusahaan Mitra & Mockup PT Teknologi Inovatif
        $vacanciesData = [
            'UI/UX Designer' => [
                'company' => 'PT Teknologi Inovatif',
                'location' => 'Jakarta, Hybrid',
                'category' => 'digital',
                'type' => 'Full-Time',
                'salary_min' => 8000000,
                'salary_max' => 12000000,
                'desc' => 'Merancang alur pengguna, wireframe, dan prototipe UI/UX inklusif dengan dukungan asistif komprehensif.',
                'tags' => ['kursi_roda', 'bisindo'],
                'created_at' => Carbon::create(2026, 10, 1),
                'deadline' => Carbon::create(2026, 10, 31),
                'is_active' => true,
            ],
            'Frontend Developer (PT TI)' => [
                'company' => 'PT Teknologi Inovatif',
                'title' => 'Frontend Developer',
                'location' => 'Jakarta, Hybrid',
                'category' => 'digital',
                'type' => 'Full-Time',
                'salary_min' => 9000000,
                'salary_max' => 15000000,
                'desc' => 'Membangun antarmuka web modern dengan aksesibilitas ramah pembaca layar (screen reader).',
                'tags' => ['kursi_roda', 'screen_reader'],
                'created_at' => Carbon::create(2026, 10, 1),
                'deadline' => Carbon::create(2026, 10, 25),
                'is_active' => true,
            ],
            'Customer Support (PT TI)' => [
                'company' => 'PT Teknologi Inovatif',
                'title' => 'Customer Support',
                'location' => 'Jakarta, On-site',
                'category' => 'komunikasi',
                'type' => 'Full-Time',
                'salary_min' => 5000000,
                'salary_max' => 8000000,
                'desc' => 'Melayani pelanggan dengan standar komunikasi inklusif dan ramah fasilitas kantor.',
                'tags' => ['kursi_roda', 'toilet_disabilitas'],
                'created_at' => Carbon::create(2026, 10, 2),
                'deadline' => Carbon::create(2026, 10, 30),
                'is_active' => true,
            ],
            'Data Analyst (PT TI)' => [
                'company' => 'PT Teknologi Inovatif',
                'title' => 'Data Analyst',
                'location' => 'Jakarta, Hybrid',
                'category' => 'digital',
                'type' => 'Full-Time',
                'salary_min' => 8000000,
                'salary_max' => 14000000,
                'desc' => 'Mengolah dan memvisualisasikan metrik data produk inklusif dengan ruang tenang terdedikasi.',
                'tags' => ['kursi_roda', 'ruang_tenang'],
                'created_at' => Carbon::create(2026, 10, 1),
                'deadline' => Carbon::create(2026, 10, 12),
                'is_active' => true,
            ],
            'Digital Marketing (PT TI)' => [
                'company' => 'PT Teknologi Inovatif',
                'title' => 'Digital Marketing',
                'location' => 'Jakarta, Hybrid',
                'category' => 'digital',
                'type' => 'Full-Time',
                'salary_min' => 7000000,
                'salary_max' => 11000000,
                'desc' => 'Mengelola kampanye pemasaran digital dengan jam kerja fleksibel.',
                'tags' => ['jam_fleksibel'],
                'created_at' => Carbon::create(2026, 9, 1),
                'deadline' => Carbon::create(2026, 9, 30),
                'is_active' => false,
            ],
            'Admin Operasional (PT TI)' => [
                'company' => 'PT Teknologi Inovatif',
                'title' => 'Admin Operasional',
                'location' => 'Jakarta, On-site',
                'category' => 'fisik',
                'type' => 'Full-Time',
                'salary_min' => 5000000,
                'salary_max' => 7500000,
                'desc' => 'Mendukung operasional harian kantor dengan akses mobilitas mudah.',
                'tags' => ['kursi_roda'],
                'created_at' => Carbon::create(2026, 9, 5),
                'deadline' => Carbon::create(2026, 10, 2),
                'is_active' => false,
            ],
            'Frontend Developer' => [
                'company' => 'Nusa Digital',
                'location' => 'Bandung',
                'category' => 'digital',
                'type' => 'remote',
                'salary_min' => 8500000,
                'salary_max' => 15000000,
                'desc' => 'Mengembangkan antarmuka web modern dengan standar aksesibilitas WCAG 2.1 Level AA.',
                'tags' => ['ramp', 'lift', 'screen_reader', 'keyboard_navigation', 'wfh_fleksibel'],
            ],
            'Customer Support' => [
                'company' => 'PT Solusi Bersama',
                'location' => 'Jakarta',
                'category' => 'komunikasi',
                'type' => 'full-time',
                'salary_min' => 5500000,
                'salary_max' => 8000000,
                'desc' => 'Melayani komunikasi pelanggan dengan dukungan komunikasi teks dan bahasa isyarat.',
                'tags' => ['ramp', 'lift', 'toilet_khusus', 'sign_language', 'komunikasi_teks'],
            ],
            'Data Analyst' => [
                'company' => 'Arunika Finance',
                'location' => 'Surabaya',
                'category' => 'digital',
                'type' => 'hybrid',
                'salary_min' => 8000000,
                'salary_max' => 14000000,
                'desc' => 'Mengolah metrik keuangan inklusif dan memvisualisasikan insight bisnis strategis.',
                'tags' => ['screen_reader', 'keyboard_navigation', 'ruang_tenang', 'wfh_fleksibel'],
            ],
        ];

        $vacancies = [];
        foreach ($vacanciesData as $key => $v) {
            $company = $companies[$v['company']];
            $title = $v['title'] ?? $key;
            $vacancies[$key] = JobVacancy::updateOrCreate(
                [
                    'company_id' => $company->id,
                    'title' => $title,
                ],
                [
                    'description' => $v['desc'],
                    'location' => $v['location'],
                    'job_type' => $v['type'],
                    'category' => $v['category'],
                    'accessibility_tags' => $v['tags'] ?? ['screen_reader', 'keyboard_navigation'],
                    'salary_min' => $v['salary_min'],
                    'salary_max' => $v['salary_max'],
                    'is_active' => $v['is_active'] ?? true,
                    'created_at' => $v['created_at'] ?? now(),
                    'deadline' => $v['deadline'] ?? Carbon::create(2026, 12, 31),
                ]
            );
        }

        // 3. Pasang 4 Lamaran pada Akun Kandidat (Nadia & kandidat@example.com)
        $candidateEmails = ['nadia@kerjasetara.id', 'kandidat@example.com'];
        $candidates = User::whereIn('email', $candidateEmails)->get();

        foreach ($candidates as $cand) {
            // Row 1: UI/UX Designer di PT Teknologi Inovatif (Tahap Wawancara)
            JobApplication::updateOrCreate(
                [
                    'user_id' => $cand->id,
                    'job_vacancy_id' => $vacancies['UI/UX Designer']->id,
                ],
                [
                    'resume_name' => 'CV_Nadia_UIUX_Inklusif_2026.pdf',
                    'portfolio_url' => 'https://behance.net/nadiamaharani',
                    'accessibility_notes' => 'Membutuhkan live caption/subtitle dan dukungan penerjemah isyarat BISINDO.',
                    'consent_agreement' => true,
                    'status' => 'interview',
                    'created_at' => Carbon::create(2026, 10, 5, 9, 30, 0),
                    'interview_at' => Carbon::create(2026, 10, 28, 10, 0, 0),
                    'interview_method' => 'Online via Zoom Meeting (Tautan otomatis aktif 10 menit sebelum jadwal)',
                    'interview_link' => 'https://zoom.us/j/9876543210',
                    'interview_assistive_facilities' => 'Auto-Caption / Subtitle aktif & Penerjemah Isyarat (BISINDO).',
                ]
            );

            // Row 2: Frontend Developer di Nusa Digital (Menunggu Review HR)
            JobApplication::updateOrCreate(
                [
                    'user_id' => $cand->id,
                    'job_vacancy_id' => $vacancies['Frontend Developer']->id,
                ],
                [
                    'resume_name' => 'CV_Frontend_Developer_Nadia.pdf',
                    'portfolio_url' => 'https://github.com/nadia-dev',
                    'accessibility_notes' => 'Perangkat lunak pembaca layar (screen reader NVDA).',
                    'consent_agreement' => true,
                    'status' => 'pending',
                    'created_at' => Carbon::create(2026, 10, 10, 14, 15, 0),
                ]
            );

            // Row 3: Customer Support di PT Solusi Bersama (Tawaran Kerja Diterima)
            JobApplication::updateOrCreate(
                [
                    'user_id' => $cand->id,
                    'job_vacancy_id' => $vacancies['Customer Support']->id,
                ],
                [
                    'resume_name' => 'CV_Customer_Support_Inklusif.pdf',
                    'portfolio_url' => null,
                    'accessibility_notes' => 'Fasilitas akses kursi roda dan meja fleksibel.',
                    'consent_agreement' => true,
                    'status' => 'accepted',
                    'created_at' => Carbon::create(2026, 10, 1, 11, 20, 0),
                    'offer_salary' => 'Rp 6.500.000 / bulan + Tunjangan Makan & Transport',
                    'offer_benefits' => 'Asuransi Kesehatan BPJS Kesehatan & Ketenagakerjaan Lengkap, Tunjangan Hari Raya (THR)',
                    'offer_start_date' => Carbon::create(2026, 11, 2),
                    'offer_facilities' => 'Jalur landai/ramp kursi roda, lift aksesibel, meja kerja ergonomis (adjustable desk), toilet disabilitas, serta opsi kerja kombinasi hybrid (2 hari WFH).',
                    'offer_notes' => 'Selamat bergabung! Paket penawaran resmi telah dikirim ke email kandidat Anda.',
                    'offer_status' => 'offered',
                    'offer_letter_url' => '#',
                ]
            );

            // Row 4: Data Analyst di Arunika Finance (Tidak Dilanjutkan)
            JobApplication::updateOrCreate(
                [
                    'user_id' => $cand->id,
                    'job_vacancy_id' => $vacancies['Data Analyst']->id,
                ],
                [
                    'resume_name' => 'CV_Data_Analyst_Nadia_2026.pdf',
                    'portfolio_url' => 'https://github.com/nadia-data',
                    'accessibility_notes' => 'Tampilan high contrast mode.',
                    'consent_agreement' => true,
                    'status' => 'rejected',
                    'created_at' => Carbon::create(2026, 9, 15, 16, 40, 0),
                    'rejection_date' => Carbon::create(2026, 9, 18),
                    'rejection_feedback' => 'Terima kasih atas dedikasi dan waktu Anda dalam mengikuti proses seleksi di Arunika Finance. Tim seleksi kami sangat mengapresiasi keahlian analisis data dan portofolio dashboard yang Anda sertakan. Namun, saat ini kami memilih kandidat dengan pengalaman mendalam dalam pemodelan prediktif lanjutan (Time Series Forecasting). Portofolio Anda sangat berpotensi, dan profil Anda telah kami simpan di Talent Pool Inklusif kami untuk lowongan spesialis data berikutnya yang relevan.',
                    'rejection_recommendation' => 'Saran pengembangan: Perbanyak studi kasus implementasi Time Series lanjutan, automated pipeline ETL data, dan machine learning terapan.',
                ]
            );
        }

        // 4. Data Pelamar Tambahan untuk PT Teknologi Inovatif sesuai Mockup Kelola Pelamar
        $mockApplicants = [
            [
                'email' => 'rizky.ramadhan@example.test',
                'name' => 'Rizky Ramadhan',
                'headline' => 'UI/UX Designer',
                'role' => 'kandidat',
                'status' => 'interview',
                'created_at' => Carbon::create(2026, 10, 4, 14, 20),
                'pref' => ['category' => 'fisik', 'wheelchair_access' => true, 'notes' => 'Akses kursi roda dan jalur ramp.'],
            ],
            [
                'email' => 'ayu.lestari@example.test',
                'name' => 'Ayu Lestari',
                'headline' => 'UX Researcher',
                'role' => 'kandidat',
                'status' => 'accepted',
                'created_at' => Carbon::create(2026, 10, 3, 11, 15),
                'pref' => ['category' => 'komunikasi', 'sign_language' => true, 'notes' => 'Penerjemah bahasa isyarat untuk diskusi riset.'],
            ],
            [
                'email' => 'bima.wicaksana@example.test',
                'name' => 'Bima Wicaksana',
                'headline' => 'Visual Designer',
                'role' => 'kandidat',
                'status' => 'rejected',
                'created_at' => Carbon::create(2026, 10, 2, 9, 45),
                'pref' => ['category' => 'digital', 'screen_reader' => true, 'notes' => 'Dukungan screen reader friendly.'],
            ],
        ];

        foreach ($mockApplicants as $appData) {
            $applicantUser = User::firstOrCreate(
                ['email' => $appData['email']],
                [
                    'name' => $appData['name'],
                    'full_name' => $appData['name'],
                    'headline' => $appData['headline'],
                    'password' => Hash::make('password'),
                    'role' => 'kandidat',
                ]
            );

            UserAccessibilityPreference::updateOrCreate(
                ['user_id' => $applicantUser->id],
                $appData['pref']
            );

            JobApplication::updateOrCreate(
                [
                    'user_id' => $applicantUser->id,
                    'job_vacancy_id' => $vacancies['UI/UX Designer']->id,
                ],
                [
                    'resume_name' => 'CV_'.str_replace(' ', '_', $appData['name']).'.pdf',
                    'portfolio_url' => 'https://example.com/portfolio/'.strtolower(explode(' ', $appData['name'])[0]),
                    'accessibility_notes' => $appData['pref']['notes'] ?? null,
                    'consent_agreement' => true,
                    'status' => $appData['status'],
                    'created_at' => $appData['created_at'],
                ]
            );
        }

        // 4. Seed Log Aktivitas Sistem & Keamanan (Sesuai Referensi Desain Figma)
        if (ActivityLog::count() === 0) {
            $adminUser = User::where('role', 'admin')->first();
            $candidateUser = User::where('role', 'kandidat')->first();

            ActivityLog::create([
                'user_id' => $candidateUser?->id,
                'user_name' => 'Nadia Saraswati',
                'user_role' => 'kandidat',
                'action_type' => 'create',
                'description' => 'Melamar posisi UI/UX Designer di PT Teknologi Inovatif.',
                'level' => 'info',
                'result' => 'Berhasil',
                'ip_address' => '192.0.2.24',
                'created_at' => Carbon::create(2026, 10, 26, 10, 42, 18),
            ]);

            ActivityLog::create([
                'user_id' => null,
                'user_name' => 'PT Nusa Digital',
                'user_role' => 'perusahaan',
                'action_type' => 'create',
                'description' => 'Mendaftarkan lowongan baru: Frontend Developer, penempatan Bandung.',
                'level' => 'info',
                'result' => 'Berhasil',
                'ip_address' => '198.51.100.18',
                'created_at' => Carbon::create(2026, 10, 26, 10, 30, 5),
            ]);

            ActivityLog::create([
                'user_id' => $adminUser?->id,
                'user_name' => $adminUser?->name ?? 'Admin Fakhri',
                'user_role' => 'admin',
                'action_type' => 'update',
                'description' => 'Memverifikasi legalitas PT Teknologi Inovatif; dokumen NIB disetujui.',
                'level' => 'info',
                'result' => 'Berhasil',
                'ip_address' => '203.0.113.10',
                'created_at' => Carbon::create(2026, 10, 26, 9, 55, 32),
            ]);

            ActivityLog::create([
                'user_id' => null,
                'user_name' => 'Budi Santoso',
                'user_role' => 'kandidat',
                'action_type' => 'login',
                'description' => 'Masuk ke akun kandidat melalui email dan kata sandi.',
                'level' => 'info',
                'result' => 'Berhasil',
                'ip_address' => '192.0.2.58',
                'created_at' => Carbon::create(2026, 10, 26, 9, 12, 44),
            ]);

            ActivityLog::create([
                'user_id' => null,
                'user_name' => 'Aktor tidak dikenal',
                'user_role' => 'tamu',
                'action_type' => 'login_failed',
                'description' => 'Percobaan masuk ditolak karena kata sandi tidak sesuai.',
                'level' => 'warning',
                'result' => 'Gagal',
                'ip_address' => '198.51.100.72',
                'created_at' => Carbon::create(2026, 10, 25, 21, 8, 12),
            ]);

            ActivityLog::create([
                'user_id' => null,
                'user_name' => 'Sistem',
                'user_role' => 'sistem',
                'action_type' => 'access_denied',
                'description' => 'Token sesi tidak valid; akses ke endpoint admin dibatalkan.',
                'level' => 'error',
                'result' => 'Ditolak',
                'ip_address' => '203.0.113.42',
                'created_at' => Carbon::create(2026, 10, 25, 20, 46, 9),
            ]);
        }
    }
}
