<?php

namespace Database\Seeders;

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
        // 1. Data 4 Perusahaan Mitra sesuai Mockup
        $companiesData = [
            [
                'email' => 'hrd@teknologiinovatif.test',
                'name' => 'Dina Pratama',
                'company_name' => 'PT Teknologi Inovatif',
                'location' => 'Jl. Kantor Inklusif No. 10, Jakarta Selatan',
                'phone' => '021 0000 2026',
                'website' => 'https://teknologiinovatif.example',
                'nib_file' => 'NIB_PT_Teknologi_Inovatif.pdf',
                'npwp_file' => 'NPWP_PT_Teknologi_Inovatif.pdf',
                'industry' => 'Teknologi Informasi & Perangkat Lunak',
                'description' => 'Perusahaan pengembang solusi digital inklusif dan ramah disabilitas.',
                'features' => ['kursi_roda', 'toilet_disabilitas', 'bisindo'],
            ],
            [
                'email' => 'hrd@nusadigital.test',
                'name' => 'HR Nusa Digital',
                'company_name' => 'Nusa Digital',
                'location' => 'Bandung',
                'phone' => null,
                'website' => 'https://example.com',
                'nib_file' => null,
                'npwp_file' => null,
                'industry' => 'Teknologi & Konsultan Web',
                'description' => 'Agensi digital terkemuka di Bandung dengan lingkungan kerja kolaboratif dan adaptif.',
                'features' => ['ramp', 'lift', 'wfh_fleksibel'],
            ],
            [
                'email' => 'hrd@solusibersama.test',
                'name' => 'HR PT Solusi Bersama',
                'company_name' => 'PT Solusi Bersama',
                'location' => 'Jakarta',
                'phone' => null,
                'website' => 'https://example.com',
                'nib_file' => null,
                'npwp_file' => null,
                'industry' => 'Layanan Pelanggan & Alih Daya',
                'description' => 'Penyedia layanan operasional ramah talenta inklusif nasional.',
                'features' => ['ramp', 'lift', 'sign_language', 'toilet_khusus'],
            ],
            [
                'email' => 'hrd@arunikafinance.test',
                'name' => 'HR Arunika Finance',
                'company_name' => 'Arunika Finance',
                'location' => 'Surabaya',
                'phone' => null,
                'website' => 'https://example.com',
                'nib_file' => null,
                'npwp_file' => null,
                'industry' => 'Teknologi Finansial & Investasi',
                'description' => 'Lembaga keuangan modern yang membuka akses luas bagi talenta disabilitas analitis.',
                'features' => ['screen_reader', 'keyboard_navigation', 'ruang_tenang'],
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
                    'is_inclusive_certified' => true,
                ]
            );

            $companies[$c['company_name']] = $profile;
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
    }
}
