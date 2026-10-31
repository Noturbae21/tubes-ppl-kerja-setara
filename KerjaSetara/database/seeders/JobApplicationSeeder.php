<?php

namespace Database\Seeders;

use App\Models\CompanyProfile;
use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\User;
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
                'name' => 'HR PT Teknologi Inovatif',
                'company_name' => 'PT Teknologi Inovatif',
                'location' => 'Jakarta',
                'industry' => 'Teknologi Informasi & Perangkat Lunak',
                'description' => 'Perusahaan pengembang solusi digital inklusif dan ramah disabilitas.',
                'features' => ['screen_reader', 'sign_language', 'wfh_fleksibel'],
            ],
            [
                'email' => 'hrd@nusadigital.test',
                'name' => 'HR Nusa Digital',
                'company_name' => 'Nusa Digital',
                'location' => 'Bandung',
                'industry' => 'Teknologi & Konsultan Web',
                'description' => 'Agensi digital terkemuka di Bandung dengan lingkungan kerja kolaboratif dan adaptif.',
                'features' => ['ramp', 'lift', 'wfh_fleksibel'],
            ],
            [
                'email' => 'hrd@solusibersama.test',
                'name' => 'HR PT Solusi Bersama',
                'company_name' => 'PT Solusi Bersama',
                'location' => 'Jakarta',
                'industry' => 'Layanan Pelanggan & Alih Daya',
                'description' => 'Penyedia layanan operasional ramah talenta inklusif nasional.',
                'features' => ['ramp', 'lift', 'sign_language', 'toilet_khusus'],
            ],
            [
                'email' => 'hrd@arunikafinance.test',
                'name' => 'HR Arunika Finance',
                'company_name' => 'Arunika Finance',
                'location' => 'Surabaya',
                'industry' => 'Teknologi Finansial & Investasi',
                'description' => 'Lembaga keuangan modern yang membuka akses luas bagi talenta disabilitas analitis.',
                'features' => ['screen_reader', 'keyboard_navigation', 'ruang_tenang'],
            ],
        ];

        $companies = [];
        foreach ($companiesData as $c) {
            $user = User::firstOrCreate(
                ['email' => $c['email']],
                [
                    'name' => $c['name'],
                    'full_name' => $c['name'],
                    'password' => Hash::make('password'),
                    'role' => 'hrd',
                ]
            );

            $profile = CompanyProfile::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'company_name' => $c['company_name'],
                    'industry' => $c['industry'],
                    'location' => $c['location'],
                    'description' => $c['description'],
                    'website' => 'https://example.com',
                    'employee_count' => 250,
                    'accessibility_features' => $c['features'],
                    'is_inclusive_certified' => true,
                ]
            );

            $companies[$c['company_name']] = $profile;
        }

        // 2. Data 4 Lowongan Kerja sesuai Mockup
        $vacanciesData = [
            'UI/UX Designer' => [
                'company' => 'PT Teknologi Inovatif',
                'location' => 'Jakarta',
                'category' => 'digital',
                'type' => 'hybrid',
                'salary_min' => 9000000,
                'salary_max' => 16000000,
                'desc' => 'Merancang pengalaman digital inklusif yang ramah screen reader dan assistive touch.',
            ],
            'Frontend Developer' => [
                'company' => 'Nusa Digital',
                'location' => 'Bandung',
                'category' => 'digital',
                'type' => 'remote',
                'salary_min' => 8500000,
                'salary_max' => 15000000,
                'desc' => 'Mengembangkan antarmuka web modern dengan standar aksesibilitas WCAG 2.1 Level AA.',
            ],
            'Customer Support' => [
                'company' => 'PT Solusi Bersama',
                'location' => 'Jakarta',
                'category' => 'komunikasi',
                'type' => 'full-time',
                'salary_min' => 5500000,
                'salary_max' => 8000000,
                'desc' => 'Melayani komunikasi pelanggan dengan dukungan komunikasi teks dan bahasa isyarat.',
            ],
            'Data Analyst' => [
                'company' => 'Arunika Finance',
                'location' => 'Surabaya',
                'category' => 'digital',
                'type' => 'hybrid',
                'salary_min' => 8000000,
                'salary_max' => 14000000,
                'desc' => 'Mengolah metrik keuangan inklusif dan memvisualisasikan insight bisnis strategis.',
            ],
        ];

        $vacancies = [];
        foreach ($vacanciesData as $title => $v) {
            $company = $companies[$v['company']];
            $vacancies[$title] = JobVacancy::firstOrCreate(
                [
                    'company_id' => $company->id,
                    'title' => $title,
                ],
                [
                    'description' => $v['desc'],
                    'location' => $v['location'],
                    'job_type' => $v['type'],
                    'category' => $v['category'],
                    'accessibility_tags' => ['screen_reader', 'keyboard_navigation'],
                    'salary_min' => $v['salary_min'],
                    'salary_max' => $v['salary_max'],
                    'is_active' => true,
                    'deadline' => Carbon::create(2026, 12, 31),
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
    }
}

