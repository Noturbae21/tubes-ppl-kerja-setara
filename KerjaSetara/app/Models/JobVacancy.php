<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobVacancy extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'title',
        'description',
        'location',
        'job_type',
        'category',
        'accessibility_tags',
        'salary_min',
        'salary_max',
        'is_active',
        'deadline',
    ];

    protected $casts = [
        'accessibility_tags' => 'array',
        'is_active' => 'boolean',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
        'deadline' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class, 'job_vacancy_id');
    }

    public function savedJobs()
    {
        return $this->hasMany(SavedJob::class, 'job_vacancy_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByAccessibility($query, string $tag)
    {
        return $query->whereJsonContains('accessibility_tags', $tag);
    }

    public function scopeSearch($query, string $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('title', 'LIKE', "%{$keyword}%")
                ->orWhere('description', 'LIKE', "%{$keyword}%")
                ->orWhere('location', 'LIKE', "%{$keyword}%")
                ->orWhereHas('company', function ($cq) use ($keyword) {
                    $cq->where('company_name', 'LIKE', "%{$keyword}%");
                });
        });
    }

    public function getFormattedSalaryAttribute(): string
    {
        if (! $this->salary_min && ! $this->salary_max) {
            return 'Negosiasi';
        }
        $min = $this->salary_min ? 'Rp '.number_format($this->salary_min, 0, ',', '.') : null;
        $max = $this->salary_max ? 'Rp '.number_format($this->salary_max, 0, ',', '.') : null;
        if ($min && $max) {
            return "{$min} – {$max}";
        }

        return $min ?? $max;
    }

    public function getJobTypeLabelAttribute(): string
    {
        return match (strtolower($this->job_type)) {
            'full-time' => 'Penuh Waktu',
            'part-time' => 'Paruh Waktu',
            'hybrid' => 'Hybrid',
            'remote' => 'Remote / WFH',
            default => ucfirst($this->job_type),
        };
    }

    public function getCategoryLabelAttribute(): string
    {
        return match (strtolower($this->category)) {
            'fisik' => 'Akses Kursi Roda & Mobilitas',
            'komunikasi' => 'Ramah Tunarungu & Wicara',
            'digital' => 'Dukungan Pembaca Layar & Digital',
            'neurodiverse' => 'Ramah Neurodiverse & Sensori',
            default => ucfirst($this->category),
        };
    }

    /**
     * Return structured responsibilities based on job position & description.
     */
    public function getResponsibilitiesListAttribute(): array
    {
        $titleLower = strtolower($this->title);

        if (str_contains($titleLower, 'designer') || str_contains($titleLower, 'ui/ux')) {
            return [
                'Merancang antarmuka pengguna (UI) dan alur pengalaman pengguna (UX) yang mematuhi standar aksesibilitas WCAG 2.1 Level AA.',
                'Melakukan usability testing berkala bersama talenta penyandang disabilitas untuk memvalidasi kemudahan navigasi.',
                'Menyiapkan komponen desain sistem berstandar inklusif (rasio kontras warna tinggi, touch target optimal, serta struktur hierarki tipografi).',
                'Berkolaborasi erat bersama tim engineering untuk memastikan implementasi desain aksesibel tepat guna.',
            ];
        }

        if (str_contains($titleLower, 'engineer') || str_contains($titleLower, 'developer')) {
            return [
                'Mengembangkan dan memelihara fitur perangkat lunak yang scalable, andal, dan ramah assistive technology.',
                'Memastikan seluruh komponen kode mendukung navigasi keyboard penuh, ARIA semantics, dan pembaca layar (screen reader).',
                'Menulis unit test dan integration test untuk mencegah regresi fitur dan menjaga performa sistem.',
                'Berpartisipasi aktif dalam sesi code review berkala dan diskusi arsitektur bersama tim teknis inklusif.',
            ];
        }

        if (str_contains($titleLower, 'analyst') || str_contains($titleLower, 'data')) {
            return [
                'Mengumpulkan, membersihkan, dan menganalisis dataset operasional untuk menghasilkan insight bisnis yang strategis.',
                'Membangun dashboard visualisasi data interaktif dengan skema warna ramah buta warna (colorblind-safe palettes).',
                'Menyusun laporan kinerja mingguan dan bulanan dengan format dokumen yang terstruktur dan ramah screen reader.',
                'Bekerjasama dengan manajer lini bisnis untuk memformulasikan rekomendasi berbasis data objektif.',
            ];
        }

        if (str_contains($titleLower, 'customer') || str_contains($titleLower, 'teller')) {
            return [
                'Melayani kebutuhan komunikasi pelanggan secara ramah, profesional, dan empatik menggunakan saluran aksesibel.',
                'Menggunakan Bahasa Isyarat Indonesia (BISINDO) atau kanal teks terintegrasi untuk melayani nasabah/pengguna tuli.',
                'Mencatat dan menindaklanjuti pertanyaan atau kendala pelanggan ke dalam sistem manajemen tiket secara akurat.',
                'Menjaga standar kepuasan pelanggan dan kerahasiaan data transaksi sesuai kebijakan perusahaan.',
            ];
        }

        if (str_contains($titleLower, 'marketing') || str_contains($titleLower, 'writer') || str_contains($titleLower, 'content')) {
            return [
                'Menyusun artikel, materi kampanye, dan konten media sosial yang edukatif, inklusif, dan bebas bias diskriminatif.',
                'Memastikan setiap konten visual dilengkapi deskripsi teks alternatif (Alt-Text) yang deskriptif dan subtitle video.',
                'Mengelola jadwal publikasi konten secara terorganisir dengan beban kerja yang terukur dan adaptif.',
                'Menganalisis performa konten dan tingkat keterlibatan audiens untuk mengoptimalkan strategi komunikasi.',
            ];
        }

        // Generic fallback
        return [
            'Menjalankan tugas dan tanggung jawab harian sesuai panduan operasional posisi '.$this->title.'.',
            'Bekerjasama secara aktif dalam tim yang mendukung keberagaman dan budaya kerja inklusif.',
            'Memberikan masukan konstruktif untuk meningkatkan aksesibilitas lingkungan kerja bersama.',
            'Menjaga kualitas hasil kerja dan kepatuhan terhadap standar etika profesional perusahaan.',
        ];
    }

    /**
     * Return structured qualifications based on job position & description.
     */
    public function getQualificationsListAttribute(): array
    {
        $titleLower = strtolower($this->title);

        if (str_contains($titleLower, 'designer') || str_contains($titleLower, 'ui/ux')) {
            return [
                'Pengalaman minimal 1 tahun dalam desain antarmuka digital (Figma, Adobe XD, atau platform sejenis).',
                'Memiliki pemahaman dasar mengenai pedoman aksesibilitas web (WCAG 2.1) dan universal design.',
                'Memiliki portofolio karya desain yang menampilkan proses berpikir logis dan berorientasi pengguna.',
                'Kemampuan komunikasi kolaboratif yang baik, terbuka untuk berbagai preferensi saluran komunikasi.',
            ];
        }

        if (str_contains($titleLower, 'engineer') || str_contains($titleLower, 'developer')) {
            return [
                'Kemampuan pemrograman yang solid (misal: JavaScript, Python, PHP, Java, atau bahasa relevan lainnya).',
                'Pemahaman mengenai clean architecture, semantic markup, dan prinsip keyboard navigation.',
                'Keterbukaan menggunakan perangkat bantu aksesibilitas (screen reader, custom keymap, voice recognition).',
                'Mampu bekerja mandiri maupun kolaboratif dalam lingkungan kerja remote atau hybrid.',
            ];
        }

        if (str_contains($titleLower, 'customer') || str_contains($titleLower, 'teller')) {
            return [
                'Kemampuan komunikasi yang baik; penguasaan Bahasa Isyarat Indonesia (BISINDO) menjadi nilai plus besar.',
                'Pendidikan minimal SMA/SMK atau Diploma/Sarjana semua jurusan.',
                'Memiliki sikap ramah, sabar, teliti, dan keinginan tulus membantu orang lain.',
                'Mampu mengoperasikan komputer dan aplikasi pesan instan/email dengan lancar.',
            ];
        }

        // Generic fallback
        return [
            'Pendidikan relevan atau pengalaman kerja/portofolio yang sebanding.',
            'Kemauan belajar tinggi dan dedikasi untuk berkembang di lingkungan perusahaan yang inklusif.',
            'Mampu mengoperasikan perangkat kerja digital sesuai kebutuhan posisi.',
            'Terbuka bagi talenta disabilitas dengan berbagai latar belakang akomodasi.',
        ];
    }

    /**
     * Returns rich details for all accessibility facilities associated with this vacancy.
     */
    public function getFacilityDetailsAttribute(): array
    {
        $tags = $this->accessibility_tags ?? [];
        if (! is_array($tags)) {
            $tags = [];
        }

        $allFacilities = self::availableFacilities();
        $details = [];

        foreach ($tags as $tag) {
            if (isset($allFacilities[$tag])) {
                $details[$tag] = $allFacilities[$tag];
            } else {
                $details[$tag] = [
                    'label' => ucwords(str_replace('_', ' ', $tag)),
                    'icon' => 'check-circle',
                    'desc' => 'Fasilitas aksesibilitas terverifikasi di lokasi kerja.',
                    'category' => 'umum',
                ];
            }
        }

        return $details;
    }

    /**
     * Master repository of all accessibility facilities with meta icons & descriptions.
     */
    public static function availableFacilities(): array
    {
        return [
            'ramp' => [
                'label' => 'Ramp Akses Kursi Roda',
                'icon' => 'wheelchair',
                'badge' => 'Akses Kursi Roda',
                'desc' => 'Jalur bidang miring tanpa anak tangga dengan kemiringan aman menuju lobi dan seluruh ruang kerja.',
                'category' => 'fisik',
            ],
            'lift' => [
                'label' => 'Lift Prioritas Aksesibel',
                'icon' => 'elevator',
                'badge' => 'Lift Aksesibel',
                'desc' => 'Lift penumpang luas dengan tombol rendah, huruf braille, dan panduan suara di setiap lantai.',
                'category' => 'fisik',
            ],
            'toilet_khusus' => [
                'label' => 'Toilet Ramah Disabilitas',
                'icon' => 'toilet',
                'badge' => 'Toilet Inklusif',
                'desc' => 'Toilet berstandar internasional dengan pegangan tangan (grab rails), tombol darurat, dan pintu lebar.',
                'category' => 'fisik',
            ],
            'parkir_disabilitas' => [
                'label' => 'Parkir Khusus Disabilitas',
                'icon' => 'parking',
                'badge' => 'Parkir Prioritas',
                'desc' => 'Slot parkir khusus berdimensi ekstra dekat dengan lift dan pintu masuk utama gedung.',
                'category' => 'fisik',
            ],
            'jalur_pemandu' => [
                'label' => 'Jalur Pemandu (Guiding Block)',
                'icon' => 'tactile',
                'badge' => 'Tactile Paving',
                'desc' => 'Ubin taktil pengarah bertekstur di sepanjang koridor utama untuk memudahkan navigasi pengguna tongkat.',
                'category' => 'fisik',
            ],
            'sign_language' => [
                'label' => 'Interpreter Bahasa Isyarat',
                'icon' => 'hands',
                'badge' => 'Ramah Tunarungu',
                'desc' => 'Dukungan juru bahasa isyarat (BISINDO) bersertifikasi untuk sesi wawancara, onboarding, dan rapat berkala.',
                'category' => 'komunikasi',
            ],
            'braille' => [
                'label' => 'Dokumentasi & Navigasi Braille',
                'icon' => 'braille',
                'badge' => 'Materi Braille',
                'desc' => 'Buku panduan karyawan, label ruangan, dan tombol navigasi gedung tersedia dalam cetakan huruf braille.',
                'category' => 'komunikasi',
            ],
            'screen_reader' => [
                'label' => 'Dukungan Pembaca Layar',
                'icon' => 'screen-reader',
                'badge' => 'Screen Reader Friendly',
                'desc' => 'Software internal, portal intranet, dan modul tugas telah diaudit 100% kompatibel dengan NVDA & JAWS.',
                'category' => 'digital',
            ],
            'keyboard_navigation' => [
                'label' => 'Navigasi Keyboard Penuh',
                'icon' => 'keyboard',
                'badge' => 'Keyboard Only',
                'desc' => 'Seluruh alur kerja sistem operasional dapat dioperasikan penuh tanpa keharusan menggunakan mouse fisik.',
                'category' => 'digital',
            ],
            'high_contrast' => [
                'label' => 'Mode Kontras Tinggi',
                'icon' => 'contrast',
                'badge' => 'High Contrast Mode',
                'desc' => 'Monitor kerja fleksibel dan sistem software dengan rasio kontras visual tinggi untuk low-vision.',
                'category' => 'digital',
            ],
            'voice_command' => [
                'label' => 'Dukungan Perintah Suara',
                'icon' => 'microphone',
                'badge' => 'Voice Input',
                'desc' => 'Peralatan mikrofon noise-cancelling dan software speech-to-text untuk membantu input data.',
                'category' => 'digital',
            ],
            'ruang_tenang' => [
                'label' => 'Ruang Sensori Tenang (Quiet Room)',
                'icon' => 'sun',
                'badge' => 'Ruang Tenang',
                'desc' => 'Ruangan hening berperedam suara dengan pencahayaan hangat untuk istirahat sensori neurodivergen.',
                'category' => 'neurodiverse',
            ],
            'wfh_fleksibel' => [
                'label' => 'Kerja Fleksibel / WFH Adaptif',
                'icon' => 'home',
                'badge' => 'Fleksibel / WFH',
                'desc' => 'Kebijakan kerja adaptif dari rumah atau jam kerja fleksibel yang disesuaikan dengan stamina talenta.',
                'category' => 'neurodiverse',
            ],
            'manajemen_beban_adaptif' => [
                'label' => 'Manajemen Beban Kerja Adaptif',
                'icon' => 'clock',
                'badge' => 'Beban Kerja Ramah',
                'desc' => 'Sistem penugasan terstruktur, instruksi kerja tertulis jelas, dan pendampingan mentor khusus.',
                'category' => 'neurodiverse',
            ],
        ];
    }

    /**
     * Matching Engine: Computes compatibility percentage and facility breakdown
     * calibrated against the candidate's accessibility profile.
     */
    public function calculateMatching(?User $user = null, ?string $categoryOverride = null): array
    {
        $candidateCategory = $categoryOverride ?: ($user?->accessibility_category ?: 'fisik');
        $jobTags = $this->accessibility_tags ?: [];
        $allFacilities = self::availableFacilities();

        // Facilities expected for candidate category
        $categoryExpectations = [
            'fisik' => [
                'primary' => ['ramp', 'lift', 'toilet_khusus', 'parkir_disabilitas'],
                'secondary' => ['jalur_pemandu', 'wfh_fleksibel'],
                'label' => 'Disabilitas Fisik & Pengguna Kursi Roda',
            ],
            'komunikasi' => [
                'primary' => ['sign_language', 'wfh_fleksibel'],
                'secondary' => ['braille', 'toilet_khusus'],
                'label' => 'Disabilitas Sensorik (Tunarungu & Wicara)',
            ],
            'digital' => [
                'primary' => ['screen_reader', 'keyboard_navigation', 'high_contrast'],
                'secondary' => ['voice_command', 'wfh_fleksibel'],
                'label' => 'Disabilitas Penglihatan & Akses Digital',
            ],
            'neurodiverse' => [
                'primary' => ['ruang_tenang', 'manajemen_beban_adaptif', 'wfh_fleksibel'],
                'secondary' => ['keyboard_navigation'],
                'label' => 'Kondisi Neurodiverse & Sensori',
            ],
        ];

        $expectation = $categoryExpectations[$candidateCategory] ?? $categoryExpectations['fisik'];
        $primaryNeeds = $expectation['primary'];
        $secondaryNeeds = $expectation['secondary'];

        $matchedPrimary = [];
        $unmatchedPrimary = [];
        $matchedSecondary = [];
        $extraFacilities = [];

        foreach ($primaryNeeds as $need) {
            if (in_array($need, $jobTags)) {
                $matchedPrimary[] = $allFacilities[$need] ?? ['label' => $need];
            } else {
                $unmatchedPrimary[] = $allFacilities[$need] ?? ['label' => $need];
            }
        }

        foreach ($secondaryNeeds as $need) {
            if (in_array($need, $jobTags)) {
                $matchedSecondary[] = $allFacilities[$need] ?? ['label' => $need];
            }
        }

        foreach ($jobTags as $tag) {
            if (! in_array($tag, $primaryNeeds) && ! in_array($tag, $secondaryNeeds)) {
                if (isset($allFacilities[$tag])) {
                    $extraFacilities[] = $allFacilities[$tag];
                }
            }
        }

        // Percentage calculation
        $totalPrimary = count($primaryNeeds);
        $countMatchedPrimary = count($matchedPrimary);
        $countMatchedSecondary = count($matchedSecondary);

        if ($this->category === $candidateCategory) {
            $baseScore = 80;
            $bonus = ($totalPrimary > 0 ? ($countMatchedPrimary / $totalPrimary) * 15 : 10) + ($countMatchedSecondary * 2);
            $percentage = (int) min(98, max(75, round($baseScore + $bonus)));
        } else {
            // Check if tags overlap anyway (e.g. tech remote jobs having digital tags)
            if ($totalPrimary > 0 && $countMatchedPrimary > 0) {
                $percentage = (int) min(95, max(65, round(65 + ($countMatchedPrimary / $totalPrimary) * 25 + ($countMatchedSecondary * 3))));
            } else {
                $percentage = in_array('wfh_fleksibel', $jobTags) || $this->job_type === 'remote' ? 78 : 65;
            }
        }

        // Category direct match boost
        if ($countMatchedPrimary === $totalPrimary && $totalPrimary > 0) {
            $percentage = max(95, $percentage);
        }

        // Status & Color
        if ($percentage >= 90) {
            $badge = 'Sangat Cocok';
            $badgeColor = 'text-emerald-700 bg-emerald-50 border-emerald-200';
            $ringColor = '#10B981'; // emerald-500
            $summaryText = 'Fasilitas di lowongan ini memenuhi seluruh standar kebutuhan aksesibilitas utama Anda.';
        } elseif ($percentage >= 75) {
            $badge = 'Cocok';
            $badgeColor = 'text-blue-700 bg-blue-50 border-blue-200';
            $ringColor = '#2563EB'; // blue-600
            $summaryText = 'Sebagian besar fasilitas ramah inklusi yang Anda perlukan telah disediakan oleh perusahaan ini.';
        } else {
            $badge = 'Cukup Cocok';
            $badgeColor = 'text-amber-700 bg-amber-50 border-amber-200';
            $ringColor = '#F59E0B'; // amber-500
            $summaryText = 'Perusahaan menyediakan fasilitas kerja dasar, akomodasi khusus dapat diajukan saat proses wawancara.';
        }

        return [
            'percentage' => $percentage,
            'badge' => $badge,
            'badge_color' => $badgeColor,
            'ring_color' => $ringColor,
            'candidate_category' => $candidateCategory,
            'category_title' => $expectation['label'],
            'matched_primary' => $matchedPrimary,
            'unmatched_primary' => $unmatchedPrimary,
            'matched_secondary' => $matchedSecondary,
            'extra_facilities' => $extraFacilities,
            'summary_text' => $summaryText,
        ];
    }
}
