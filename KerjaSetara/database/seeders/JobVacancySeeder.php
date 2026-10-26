<?php

namespace Database\Seeders;

use App\Models\CompanyProfile;
use App\Models\JobVacancy;
use Illuminate\Database\Seeder;

class JobVacancySeeder extends Seeder
{
    public function run(): void
    {
        $companies = CompanyProfile::all();

        $vacancies = [
            // Tokopedia
            ['company_name' => 'PT Tokopedia (GoTo Group)', 'title' => 'UI/UX Designer', 'location' => 'Jakarta Selatan', 'job_type' => 'hybrid', 'category' => 'digital', 'tags' => ['screen_reader', 'keyboard_navigation', 'high_contrast'], 'salary_min' => 8000000, 'salary_max' => 15000000, 'desc' => 'Kami mencari UI/UX Designer berbakat yang passionate dengan desain aksesibel. Kantor kami dilengkapi fasilitas screen reader dan high-contrast mode.'],
            ['company_name' => 'PT Tokopedia (GoTo Group)', 'title' => 'Backend Engineer (Python)', 'location' => 'Jakarta Selatan', 'job_type' => 'remote', 'category' => 'digital', 'tags' => ['screen_reader', 'voice_command', 'wfh_fleksibel'], 'salary_min' => 12000000, 'salary_max' => 25000000, 'desc' => 'Bergabunglah dengan tim engineering Tokopedia. Remote-friendly dan mendukung kebutuhan aksesibilitas digital.'],
            // Gojek
            ['company_name' => 'PT Aplikasi Karya Anak Bangsa (Gojek)', 'title' => 'Data Analyst', 'location' => 'Jakarta Pusat', 'job_type' => 'hybrid', 'category' => 'digital', 'tags' => ['screen_reader', 'wfh_fleksibel', 'keyboard_navigation'], 'salary_min' => 9000000, 'salary_max' => 18000000, 'desc' => 'Bantu kami menganalisis data jutaan pengguna Gojek. Tim kami inklusif dan mendukung berbagai kebutuhan aksesibilitas.'],
            ['company_name' => 'PT Aplikasi Karya Anak Bangsa (Gojek)', 'title' => 'Product Manager', 'location' => 'Jakarta Pusat', 'job_type' => 'hybrid', 'category' => 'neurodiverse', 'tags' => ['ruang_tenang', 'wfh_fleksibel', 'manajemen_beban_adaptif'], 'salary_min' => 15000000, 'salary_max' => 30000000, 'desc' => 'Kami membutuhkan Product Manager dengan kemampuan analitik kuat. Kantor kami memiliki ruang tenang dan fleksibilitas kerja.'],
            // BCA
            ['company_name' => 'PT Bank Central Asia Tbk', 'title' => 'Customer Service (Bahasa Isyarat)', 'location' => 'Jakarta Pusat', 'job_type' => 'full-time', 'category' => 'komunikasi', 'tags' => ['sign_language', 'braille', 'toilet_khusus'], 'salary_min' => 5000000, 'salary_max' => 8000000, 'desc' => 'BCA membuka lowongan CS khusus untuk komunikasi dengan nasabah tuli/bisu. Fasih Bahasa Isyarat Indonesia (BISINDO) diprioritaskan.'],
            ['company_name' => 'PT Bank Central Asia Tbk', 'title' => 'IT Security Analyst', 'location' => 'Jakarta Pusat', 'job_type' => 'full-time', 'category' => 'digital', 'tags' => ['screen_reader', 'keyboard_navigation', 'high_contrast'], 'salary_min' => 10000000, 'salary_max' => 20000000, 'desc' => 'Tim keamanan digital BCA membutuhkan analis berpengalaman. Kami menyediakan workstation aksesibel penuh.'],
            // Telkomsel
            ['company_name' => 'PT Telekomunikasi Selular (Telkomsel)', 'title' => 'Network Engineer', 'location' => 'Jakarta Selatan', 'job_type' => 'full-time', 'category' => 'fisik', 'tags' => ['ramp', 'lift', 'parkir_disabilitas', 'toilet_khusus'], 'salary_min' => 9000000, 'salary_max' => 16000000, 'desc' => 'Bergabung dengan tim jaringan Telkomsel. Fasilitas gedung kami sepenuhnya aksesibel untuk pengguna kursi roda.'],
            ['company_name' => 'PT Telekomunikasi Selular (Telkomsel)', 'title' => 'Content Writer (Remote)', 'location' => 'Remote', 'job_type' => 'remote', 'category' => 'neurodiverse', 'tags' => ['wfh_fleksibel', 'manajemen_beban_adaptif', 'ruang_tenang'], 'salary_min' => 4000000, 'salary_max' => 7000000, 'desc' => 'Kami mencari penulis kreatif yang bisa kerja dari mana saja. Beban kerja fleksibel dan deadlines yang adaptif.'],
            // Unilever
            ['company_name' => 'PT Unilever Indonesia Tbk', 'title' => 'Marketing Executive', 'location' => 'Tangerang', 'job_type' => 'full-time', 'category' => 'fisik', 'tags' => ['ramp', 'lift', 'toilet_khusus'], 'salary_min' => 7000000, 'salary_max' => 12000000, 'desc' => 'Unilever mencari marketing exec berbakat. Kantor Unilever Indonesia dilengkapi ramp, lift, dan toilet khusus disabilitas.'],
            ['company_name' => 'PT Unilever Indonesia Tbk', 'title' => 'Supply Chain Analyst', 'location' => 'Tangerang', 'job_type' => 'hybrid', 'category' => 'komunikasi', 'tags' => ['sign_language', 'manajemen_beban_adaptif'], 'salary_min' => 8000000, 'salary_max' => 14000000, 'desc' => 'Bergabung dengan tim supply chain global. Kami mendukung komunikasi inklusif dengan interpreter bahasa isyarat.'],
            // Bukalapak
            ['company_name' => 'PT Bukalapak.com Tbk', 'title' => 'Frontend Developer (React)', 'location' => 'Remote', 'job_type' => 'remote', 'category' => 'digital', 'tags' => ['screen_reader', 'keyboard_navigation', 'wfh_fleksibel'], 'salary_min' => 8000000, 'salary_max' => 15000000, 'desc' => 'Bukalapak mencari frontend developer handal. Full remote, tooling aksesibel, dan tim yang supportif.'],
            ['company_name' => 'PT Bukalapak.com Tbk', 'title' => 'Quality Assurance Engineer', 'location' => 'Remote', 'job_type' => 'remote', 'category' => 'digital', 'tags' => ['screen_reader', 'high_contrast', 'wfh_fleksibel'], 'salary_min' => 7000000, 'salary_max' => 12000000, 'desc' => 'Pastikan kualitas produk Bukalapak bersama kami. Bekerja remote dengan jadwal fleksibel.'],
            // Shopee
            ['company_name' => 'PT Shopee International Indonesia', 'title' => 'Graphic Designer', 'location' => 'Jakarta Pusat', 'job_type' => 'hybrid', 'category' => 'neurodiverse', 'tags' => ['ruang_tenang', 'manajemen_beban_adaptif', 'wfh_fleksibel'], 'salary_min' => 6000000, 'salary_max' => 11000000, 'desc' => 'Tim kreatif Shopee membutuhkan desainer grafis. Ada ruang tenang khusus untuk fokus bekerja.'],
            ['company_name' => 'PT Shopee International Indonesia', 'title' => 'Logistics Coordinator', 'location' => 'Jakarta Pusat', 'job_type' => 'full-time', 'category' => 'fisik', 'tags' => ['ramp', 'lift', 'toilet_khusus', 'parkir_disabilitas'], 'salary_min' => 5500000, 'salary_max' => 9000000, 'desc' => 'Koordinasikan operasional logistik Shopee. Fasilitas gedung sepenuhnya aksesibel.'],
            // Bank Mandiri
            ['company_name' => 'PT Bank Mandiri (Persero) Tbk', 'title' => 'Teller Inklusif', 'location' => 'Jakarta & Seluruh Indonesia', 'job_type' => 'full-time', 'category' => 'komunikasi', 'tags' => ['sign_language', 'braille', 'ramp', 'lift', 'toilet_khusus'], 'salary_min' => 4500000, 'salary_max' => 7000000, 'desc' => 'Bank Mandiri membuka rekrutmen teller inklusif di seluruh Indonesia. Pelatihan bahasa isyarat disediakan.'],
            ['company_name' => 'PT Bank Mandiri (Persero) Tbk', 'title' => 'Software Developer (Java)', 'location' => 'Jakarta Pusat', 'job_type' => 'hybrid', 'category' => 'digital', 'tags' => ['screen_reader', 'keyboard_navigation', 'high_contrast'], 'salary_min' => 11000000, 'salary_max' => 22000000, 'desc' => 'Kembangkan core banking system Mandiri bersama kami. Lingkungan kerja aksesibel dan inklusif.'],
            // Microsoft
            ['company_name' => 'PT Microsoft Indonesia', 'title' => 'Accessibility Engineer', 'location' => 'Jakarta Selatan', 'job_type' => 'hybrid', 'category' => 'digital', 'tags' => ['screen_reader', 'voice_command', 'keyboard_navigation', 'high_contrast', 'wfh_fleksibel'], 'salary_min' => 18000000, 'salary_max' => 35000000, 'desc' => 'Bantu Microsoft membangun teknologi yang lebih aksesibel untuk semua orang. Pelopor inklusif di industri tech.'],
            ['company_name' => 'PT Microsoft Indonesia', 'title' => 'Cloud Solutions Architect', 'location' => 'Remote / Jakarta', 'job_type' => 'remote', 'category' => 'neurodiverse', 'tags' => ['wfh_fleksibel', 'ruang_tenang', 'manajemen_beban_adaptif', 'screen_reader'], 'salary_min' => 25000000, 'salary_max' => 50000000, 'desc' => 'Rancang solusi cloud Azure untuk klien enterprise. Remote-friendly, schedule adaptif, dan benefit premium.'],
            // Astra
            ['company_name' => 'PT Astra International Tbk', 'title' => 'HR Specialist (Inklusif)', 'location' => 'Jakarta Barat', 'job_type' => 'full-time', 'category' => 'fisik', 'tags' => ['ramp', 'lift', 'toilet_khusus', 'parkir_disabilitas', 'jalur_pemandu'], 'salary_min' => 7000000, 'salary_max' => 12000000, 'desc' => 'Kelola program rekrutmen inklusif Astra Group. Gedung kantor kami dilengkapi semua fasilitas aksesibilitas fisik.'],
            ['company_name' => 'PT Astra International Tbk', 'title' => 'Finance Analyst', 'location' => 'Jakarta Barat', 'job_type' => 'full-time', 'category' => 'fisik', 'tags' => ['ramp', 'lift', 'parkir_disabilitas'], 'salary_min' => 8000000, 'salary_max' => 15000000, 'desc' => 'Bergabung dengan tim keuangan Astra International. Fasilitas lengkap untuk pengguna kursi roda dan tongkat.'],
        ];

        foreach ($vacancies as $data) {
            $company = $companies->firstWhere('company_name', $data['company_name']);
            if (! $company) {
                continue;
            }

            JobVacancy::create([
                'company_id' => $company->id,
                'title' => $data['title'],
                'description' => $data['desc'],
                'location' => $data['location'],
                'job_type' => $data['job_type'],
                'category' => $data['category'],
                'accessibility_tags' => $data['tags'],
                'salary_min' => $data['salary_min'],
                'salary_max' => $data['salary_max'],
                'is_active' => true,
                'deadline' => now()->addDays(rand(14, 60)),
            ]);
        }
    }
}
