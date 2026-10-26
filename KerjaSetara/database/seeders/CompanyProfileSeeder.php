<?php

namespace Database\Seeders;

use App\Models\CompanyProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CompanyProfileSeeder extends Seeder
{
    public function run(): void
    {
        $companies = [
            [
                'name' => 'HRD Tokopedia',
                'full_name' => 'Budi Santoso',
                'email' => 'hrd@tokopedia.test',
                'company_name' => 'PT Tokopedia (GoTo Group)',
                'industry' => 'E-Commerce & Teknologi',
                'location' => 'Jakarta Selatan',
                'description' => 'Platform e-commerce terbesar di Indonesia yang berkomitmen menciptakan lingkungan kerja inklusif dan ramah disabilitas.',
                'website' => 'https://tokopedia.com',
                'employee_count' => 5000,
                'accessibility_features' => ['ramp', 'lift', 'toilet_khusus', 'parkir_disabilitas', 'screen_reader', 'keyboard_navigation'],
                'is_inclusive_certified' => true,
            ],
            [
                'name' => 'HRD Gojek',
                'full_name' => 'Sari Wulandari',
                'email' => 'hrd@gojek.test',
                'company_name' => 'PT Aplikasi Karya Anak Bangsa (Gojek)',
                'industry' => 'Teknologi & Transportasi',
                'location' => 'Jakarta Pusat',
                'description' => 'Super-app terdepan Asia Tenggara dengan program inklusi aktif untuk penyandang disabilitas.',
                'website' => 'https://gojek.com',
                'employee_count' => 3000,
                'accessibility_features' => ['ramp', 'lift', 'sign_language', 'screen_reader', 'high_contrast', 'voice_command'],
                'is_inclusive_certified' => true,
            ],
            [
                'name' => 'HRD BCA',
                'full_name' => 'Ahmad Fauzi',
                'email' => 'hrd@bca.test',
                'company_name' => 'PT Bank Central Asia Tbk',
                'industry' => 'Perbankan & Keuangan',
                'location' => 'Jakarta Pusat',
                'description' => 'Bank swasta terbesar di Indonesia yang aktif merekrut dan mengembangkan talenta penyandang disabilitas.',
                'website' => 'https://bca.co.id',
                'employee_count' => 25000,
                'accessibility_features' => ['ramp', 'lift', 'toilet_khusus', 'screen_reader', 'sign_language', 'braille'],
                'is_inclusive_certified' => true,
            ],
            [
                'name' => 'HRD Telkomsel',
                'full_name' => 'Dewi Rahayu',
                'email' => 'hrd@telkomsel.test',
                'company_name' => 'PT Telekomunikasi Selular (Telkomsel)',
                'industry' => 'Telekomunikasi',
                'location' => 'Jakarta Selatan',
                'description' => 'Operator telekomunikasi terbesar Indonesia, menyediakan lingkungan kerja adaptif dan teknologi bantu.',
                'website' => 'https://telkomsel.com',
                'employee_count' => 8000,
                'accessibility_features' => ['ramp', 'lift', 'screen_reader', 'keyboard_navigation', 'ruang_tenang', 'wfh_fleksibel'],
                'is_inclusive_certified' => true,
            ],
            [
                'name' => 'HRD Unilever',
                'full_name' => 'Rina Kusuma',
                'email' => 'hrd@unilever.test',
                'company_name' => 'PT Unilever Indonesia Tbk',
                'industry' => 'FMCG & Manufaktur',
                'location' => 'Tangerang',
                'description' => 'Perusahaan multinasional dengan program keberagaman dan inklusi yang komprehensif.',
                'website' => 'https://unilever.com',
                'employee_count' => 4000,
                'accessibility_features' => ['ramp', 'lift', 'toilet_khusus', 'sign_language', 'manajemen_beban_adaptif'],
                'is_inclusive_certified' => true,
            ],
            [
                'name' => 'HRD Bukalapak',
                'full_name' => 'Hendra Wijaya',
                'email' => 'hrd@bukalapak.test',
                'company_name' => 'PT Bukalapak.com Tbk',
                'industry' => 'E-Commerce',
                'location' => 'Jakarta Selatan',
                'description' => 'Platform marketplace dengan komitmen inklusi digital untuk seluruh kalangan masyarakat Indonesia.',
                'website' => 'https://bukalapak.com',
                'employee_count' => 2000,
                'accessibility_features' => ['screen_reader', 'high_contrast', 'keyboard_navigation', 'wfh_fleksibel'],
                'is_inclusive_certified' => true,
            ],
            [
                'name' => 'HRD Shopee',
                'full_name' => 'Maya Sari',
                'email' => 'hrd@shopee.test',
                'company_name' => 'PT Shopee International Indonesia',
                'industry' => 'E-Commerce',
                'location' => 'Jakarta Pusat',
                'description' => 'Platform e-commerce regional yang mendukung keberagaman di tempat kerja.',
                'website' => 'https://shopee.co.id',
                'employee_count' => 6000,
                'accessibility_features' => ['ramp', 'lift', 'screen_reader', 'sign_language', 'ruang_tenang'],
                'is_inclusive_certified' => true,
            ],
            [
                'name' => 'HRD Mandiri',
                'full_name' => 'Dian Pratama',
                'email' => 'hrd@mandiri.test',
                'company_name' => 'PT Bank Mandiri (Persero) Tbk',
                'industry' => 'Perbankan & BUMN',
                'location' => 'Jakarta Pusat',
                'description' => 'Bank BUMN terbesar yang aktif menjalankan program rekrutmen inklusif nasional.',
                'website' => 'https://bankmandiri.co.id',
                'employee_count' => 35000,
                'accessibility_features' => ['ramp', 'lift', 'toilet_khusus', 'parkir_disabilitas', 'braille', 'sign_language'],
                'is_inclusive_certified' => true,
            ],
            [
                'name' => 'HRD Microsoft Indonesia',
                'full_name' => 'Fitri Handayani',
                'email' => 'hrd@microsoft.test',
                'company_name' => 'PT Microsoft Indonesia',
                'industry' => 'Teknologi Informasi',
                'location' => 'Jakarta Selatan',
                'description' => 'Pelopor teknologi aksesibilitas dengan program Disability Inclusion yang komprehensif.',
                'website' => 'https://microsoft.com/id',
                'employee_count' => 500,
                'accessibility_features' => ['screen_reader', 'high_contrast', 'voice_command', 'keyboard_navigation', 'wfh_fleksibel', 'ruang_tenang', 'manajemen_beban_adaptif'],
                'is_inclusive_certified' => true,
            ],
            [
                'name' => 'HRD Astra International',
                'full_name' => 'Bambang Setyo',
                'email' => 'hrd@astra.test',
                'company_name' => 'PT Astra International Tbk',
                'industry' => 'Otomotif & Diversifikasi',
                'location' => 'Jakarta Barat',
                'description' => 'Konglomerat nasional terkemuka yang mengutamakan prinsip kesetaraan dalam rekrutmen.',
                'website' => 'https://astra.co.id',
                'employee_count' => 220000,
                'accessibility_features' => ['ramp', 'lift', 'toilet_khusus', 'parkir_disabilitas', 'jalur_pemandu'],
                'is_inclusive_certified' => true,
            ],
        ];

        foreach ($companies as $data) {
            $user = User::create([
                'name' => $data['name'],
                'full_name' => $data['full_name'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'role' => 'hrd',
            ]);

            CompanyProfile::create([
                'user_id' => $user->id,
                'company_name' => $data['company_name'],
                'industry' => $data['industry'],
                'location' => $data['location'],
                'description' => $data['description'],
                'website' => $data['website'],
                'employee_count' => $data['employee_count'],
                'accessibility_features' => $data['accessibility_features'],
                'is_inclusive_certified' => $data['is_inclusive_certified'],
            ]);
        }
    }
}
