<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'job_vacancy_id',
        'resume_name',
        'portfolio_url',
        'accessibility_notes',
        'consent_agreement',
        'status',
        'interview_at',
        'interview_method',
        'interview_link',
        'interview_assistive_facilities',
        'offer_salary',
        'offer_benefits',
        'offer_start_date',
        'offer_facilities',
        'offer_notes',
        'offer_status',
        'offer_letter_url',
        'rejection_feedback',
        'rejection_recommendation',
        'rejection_date',
        'cancelled_at',
        'cancellation_reason',
    ];

    protected $casts = [
        'consent_agreement' => 'boolean',
        'interview_at' => 'datetime',
        'offer_start_date' => 'date',
        'rejection_date' => 'date',
        'cancelled_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jobVacancy(): BelongsTo
    {
        return $this->belongsTo(JobVacancy::class, 'job_vacancy_id');
    }

    /**
     * Inisial nama perusahaan untuk logo lingkaran (misal: "ti", "nd", "sb", "af")
     */
    public function getCompanyInitialsAttribute(): string
    {
        $companyName = $this->jobVacancy->company->company_name ?? 'Perusahaan Mitra';

        // Bersihkan prefix umum seperti PT, CV, PT.
        $clean = preg_replace('/^(PT\.?|CV\.?|Firma)\s+/i', '', trim($companyName));
        $words = preg_split('/\s+/', $clean);

        if (count($words) >= 2) {
            return strtolower(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }

        return strtolower(substr($clean, 0, 2));
    }

    /**
     * Warna background avatar inisial perusahaan sesuai visual mockup
     */
    public function getInitialsBgClassAttribute(): string
    {
        $initials = $this->company_initials;

        return match ($initials) {
            'ti' => 'bg-[#193a7c]',  // PT Teknologi Inovatif (Dark Navy)
            'nd' => 'bg-[#4338ca]',  // Nusa Digital (Indigo)
            'sb' => 'bg-[#0f766e]',  // PT Solusi Bersama (Teal)
            'af' => 'bg-[#1e40af]',  // Arunika Finance (Blue)
            default => 'bg-[#193a7c]',
        };
    }

    /**
     * Label status sesuai mockup
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'interview' => 'Tahap Wawancara',
            'pending', 'reviewed' => 'Menunggu Review HR',
            'shortlisted' => 'Lolos Berkas',
            'accepted' => 'Tawaran Kerja Diterima 🎉',
            'rejected' => 'Tidak Dilanjutkan',
            'cancelled' => 'Dibatalkan',
            default => ucfirst($this->status),
        };
    }

    /**
     * Kategori filter tab (semua, pending, interview, accepted, rejected)
     */
    public function getFilterCategoryAttribute(): string
    {
        return match ($this->status) {
            'pending', 'reviewed', 'shortlisted' => 'pending',
            'interview' => 'interview',
            'accepted' => 'accepted',
            'rejected', 'cancelled' => 'rejected',
            default => 'all',
        };
    }

    /**
     * Styling badge status sesuai panduan desain dan kontras WCAG 2.1 AA
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'interview' => 'bg-[#fef3c7] text-[#92400e] border border-amber-200/60',
            'pending', 'reviewed' => 'bg-[#fef9c3] text-[#854d0e] border border-amber-200/60',
            'shortlisted' => 'bg-blue-50 text-blue-800 border border-blue-200',
            'accepted' => 'bg-[#ecfdf5] text-[#065f46] border border-emerald-200/60',
            'rejected' => 'bg-[#fef2f2] text-[#991b1b] border border-rose-200/60',
            'cancelled' => 'bg-slate-100 text-slate-600 border border-slate-200',
            default => 'bg-slate-100 text-slate-700 border-slate-200',
        };
    }

    /**
     * Tanggal melamar terformat dalam Bahasa Indonesia (contoh: "Dilamar pada 5 Okt 2026")
     */
    public function getFormattedAppliedDateAttribute(): string
    {
        $months = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
            7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ];

        $date = $this->created_at ?? now();
        $month = $months[$date->month] ?? $date->format('M');

        return "Dilamar pada {$date->day} {$month} {$date->year}";
    }

    /**
     * Format jadwal wawancara (contoh: "Rabu, 28 Oktober 2026 - Pukul 10.00 WIB")
     */
    public function getFormattedInterviewDateAttribute(): string
    {
        if (! $this->interview_at) {
            return 'Rabu, 28 Oktober 2026 - Pukul 10.00 WIB';
        }

        $days = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ];

        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $dayName = $days[$this->interview_at->format('l')] ?? $this->interview_at->format('l');
        $monthName = $months[$this->interview_at->month] ?? $this->interview_at->format('F');
        $time = $this->interview_at->format('H.i');

        return "{$dayName}, {$this->interview_at->day} {$monthName} {$this->interview_at->year} - Pukul {$time} WIB";
    }

    /**
     * Format tanggal mulai bekerja penawaran (contoh: "Senin, 2 November 2026")
     */
    public function getFormattedOfferStartDateAttribute(): string
    {
        if (! $this->offer_start_date) {
            return 'Senin, 2 November 2026';
        }

        $days = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ];

        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $dayName = $days[$this->offer_start_date->format('l')] ?? $this->offer_start_date->format('l');
        $monthName = $months[$this->offer_start_date->month] ?? $this->offer_start_date->format('F');

        return "{$dayName}, {$this->offer_start_date->day} {$monthName} {$this->offer_start_date->year}";
    }

    /**
     * Format tanggal evaluasi penolakan (contoh: "18 September 2026")
     */
    public function getFormattedRejectionDateAttribute(): string
    {
        if (! $this->rejection_date) {
            return '18 September 2026';
        }

        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $monthName = $months[$this->rejection_date->month] ?? $this->rejection_date->format('F');

        return "{$this->rejection_date->day} {$monthName} {$this->rejection_date->year}";
    }
}
