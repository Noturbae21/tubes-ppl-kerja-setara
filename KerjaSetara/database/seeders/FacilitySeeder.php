<?php

namespace Database\Seeders;

use App\Models\Facility;
use App\Models\JobVacancy;
use App\Services\SvgSanitizerService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FacilitySeeder extends Seeder
{
    /**
     * Run database seeds for Accessibility Master Facilities.
     */
    public function run(): void
    {
        $sanitizer = app(SvgSanitizerService::class);

        $facilitiesData = [
            [
                'name' => 'Akses Kursi Roda',
                'description' => 'Ramp, lift, dan jalur yang dapat diakses kursi roda.',
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="4" r="2"/><path d="M18 19l-4-4v-4l4-2"/><path d="M10 9a4 4 0 1 0 4 4"/><path d="M6 13h4"/></svg>',
                'is_active' => true,
                'slug' => 'akses-kursi-roda',
            ],
            [
                'name' => 'Lift Prioritas Aksesibel',
                'description' => 'Lift penumpang luas dengan tombol rendah, huruf braille, dan panduan suara di setiap lantai.',
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M7 10l3-3 3 3"/><path d="M7 14l3 3 3-3"/><line x1="17" y1="7" x2="17" y2="17"/></svg>',
                'is_active' => true,
                'slug' => 'lift-aksesibel',
            ],
            [
                'name' => 'Toilet Ramah Disabilitas',
                'description' => 'Toilet berstandar internasional dengan pegangan tangan (grab rails), tombol darurat, dan pintu lebar.',
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 12h10M7 8h10M7 16h10"/><circle cx="12" cy="4" r="2"/><rect x="5" y="6" width="14" height="14" rx="2"/></svg>',
                'is_active' => true,
                'slug' => 'toilet-aksesibel',
            ],
            [
                'name' => 'Parkir Khusus Disabilitas',
                'description' => 'Slot parkir khusus berdimensi ekstra dekat dengan lift dan pintu masuk utama gedung.',
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="3"/><path d="M9 17V7h4a3 3 0 0 1 0 6H9"/></svg>',
                'is_active' => true,
                'slug' => 'parkir-disabilitas',
            ],
            [
                'name' => 'Jalur Pemandu (Guiding Block)',
                'description' => 'Ubin taktil pengarah bertekstur di sepanjang koridor utama untuk memudahkan navigasi pengguna tongkat.',
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="4" x2="4" y2="20"/><line x1="9" y1="4" x2="9" y2="20"/><line x1="14" y1="4" x2="14" y2="20"/><line x1="19" y1="4" x2="19" y2="20"/></svg>',
                'is_active' => true,
                'slug' => 'jalur-pemandu',
            ],
            [
                'name' => 'Layanan Penerjemah Isyarat',
                'description' => 'Dukungan juru bahasa isyarat (BISINDO) bersertifikasi untuk sesi wawancara, onboarding, dan rapat berkala.',
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 11V6a2 2 0 0 0-2-2v0a2 2 0 0 0-2 2v0"/><path d="M14 10V4a2 2 0 0 0-2-2v0a2 2 0 0 0-2 2v2"/><path d="M10 10.5V6a2 2 0 0 0-2-2v0a2 2 0 0 0-2 2v8"/><path d="M18 8a2 2 0 1 1 4 0v6a8 8 0 0 1-8 8h-2c-2.8 0-4.5-.86-5.99-2.34l-3.6-3.6a2 2 0 0 1 2.83-2.82L7 15"/></svg>',
                'is_active' => true,
                'slug' => 'penerjemah-isyarat',
            ],
            [
                'name' => 'Dokumentasi & Navigasi Braille',
                'description' => 'Buku panduan karyawan, label ruangan, dan tombol navigasi gedung tersedia dalam cetakan huruf braille.',
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="6" cy="6" r="2" fill="currentColor"/><circle cx="6" cy="12" r="2" fill="currentColor"/><circle cx="6" cy="18" r="2"/><circle cx="18" cy="6" r="2"/><circle cx="18" cy="12" r="2" fill="currentColor"/><circle cx="18" cy="18" r="2" fill="currentColor"/></svg>',
                'is_active' => true,
                'slug' => 'navigasi-braille',
            ],
            [
                'name' => 'Dukungan Pembaca Layar',
                'description' => 'Software internal, portal intranet, dan modul tugas telah diaudit 100% kompatibel dengan NVDA & JAWS.',
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/><path d="M6 8h.01M9 8h6M6 12h.01M9 12h3"/></svg>',
                'is_active' => true,
                'slug' => 'screen-reader',
            ],
            [
                'name' => 'Navigasi Keyboard Penuh',
                'description' => 'Seluruh alur kerja sistem operasional dapat dioperasikan penuh tanpa keharusan menggunakan mouse fisik.',
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M6 8h.01M10 8h.01M14 8h.01M18 8h.01M6 12h.01M10 12h.01M14 12h.01M18 12h.01M8 16h8"/></svg>',
                'is_active' => true,
                'slug' => 'keyboard-navigasi',
            ],
            [
                'name' => 'Mode Kontras Tinggi',
                'description' => 'Monitor kerja fleksibel dan sistem software dengan rasio kontras visual tinggi untuk low-vision.',
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 2a10 10 0 0 1 0 20z" fill="currentColor"/></svg>',
                'is_active' => true,
                'slug' => 'kontras-tinggi',
            ],
            [
                'name' => 'Ruang Sensori Tenang (Quiet Room)',
                'description' => 'Ruangan hening berperedam suara dengan pencahayaan hangat untuk istirahat sensori neurodivergen.',
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/></svg>',
                'is_active' => true,
                'slug' => 'ruang-tenang',
            ],
            [
                'name' => 'Kerja Fleksibel / WFH Adaptif',
                'description' => 'Kebijakan kerja adaptif dari rumah atau jam kerja fleksibel yang disesuaikan dengan stamina talenta.',
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>',
                'is_active' => true,
                'slug' => 'wfh-fleksibel',
            ],
            [
                'name' => 'Manajemen Beban Kerja Adaptif',
                'description' => 'Sistem penugasan terstruktur, instruksi kerja tertulis jelas, dan pendampingan mentor khusus.',
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
                'is_active' => false, // seeded as Nonaktif to demonstrate status filtering!
                'slug' => 'manajemen-beban',
            ],
            [
                'name' => 'Meja Kerja Adjustable',
                'description' => 'Meja kerja sit-to-stand otomatis untuk postur ergonomis dan adaptasi kebutuhan mobilitas.',
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="6" width="18" height="4" rx="1"/><line x1="5" y1="10" x2="5" y2="20"/><line x1="19" y1="10" x2="19" y2="20"/><line x1="2" y1="20" x2="8" y2="20"/><line x1="16" y1="20" x2="22" y2="20"/><path d="M12 13l-2 2h4l-2-2"/></svg>',
                'is_active' => true,
                'slug' => 'meja-adjustable',
            ],
        ];

        foreach ($facilitiesData as $item) {
            $path = "facilities/icons/{$item['slug']}.svg";

            // Persist SVG icon to storage
            Storage::disk('public')->put($path, $item['svg']);
            $publicDir = public_path('storage/facilities/icons');
            if (! is_dir($publicDir)) {
                @mkdir($publicDir, 0755, true);
            }
            @file_put_contents(public_path('storage/' . $path), $item['svg']);

            Facility::updateOrCreate(
                ['name' => $item['name']],
                [
                    'description' => $item['description'],
                    'icon_path' => $path,
                    'is_active' => $item['is_active'],
                ]
            );
        }

        // Attach facilities to existing vacancies for realistic usage counts
        $vacancies = JobVacancy::all();
        if ($vacancies->isNotEmpty()) {
            $wheelchairFacility = Facility::where('name', 'Akses Kursi Roda')->first();
            $liftFacility = Facility::where('name', 'Lift Prioritas Aksesibel')->first();
            $toiletFacility = Facility::where('name', 'Toilet Ramah Disabilitas')->first();
            $screenReaderFacility = Facility::where('name', 'Dukungan Pembaca Layar')->first();
            $signLanguageFacility = Facility::where('name', 'Layanan Penerjemah Isyarat')->first();
            $wfhFacility = Facility::where('name', 'Kerja Fleksibel / WFH Adaptif')->first();

            foreach ($vacancies as $vacancy) {
                $attachIds = [];
                $tags = (array) ($vacancy->accessibility_tags ?? []);

                if ($wheelchairFacility && (in_array('ramp', $tags) || in_array('kursi_roda', $tags) || in_array('wheelchair', $tags))) {
                    $attachIds[] = $wheelchairFacility->id;
                }
                if ($liftFacility && in_array('lift', $tags)) {
                    $attachIds[] = $liftFacility->id;
                }
                if ($toiletFacility && (in_array('toilet_khusus', $tags) || in_array('toilet_disabilitas', $tags))) {
                    $attachIds[] = $toiletFacility->id;
                }
                if ($screenReaderFacility && in_array('screen_reader', $tags)) {
                    $attachIds[] = $screenReaderFacility->id;
                }
                if ($signLanguageFacility && (in_array('sign_language', $tags) || in_array('bisindo', $tags))) {
                    $attachIds[] = $signLanguageFacility->id;
                }
                if ($wfhFacility && (in_array('wfh_fleksibel', $tags) || in_array('jam_fleksibel', $tags))) {
                    $attachIds[] = $wfhFacility->id;
                }

                // If empty tags, give default wheelchair access for demo
                if (empty($attachIds) && $wheelchairFacility) {
                    $attachIds[] = $wheelchairFacility->id;
                }

                $vacancy->facilities()->syncWithoutDetaching($attachIds);
            }
        }
    }
}
