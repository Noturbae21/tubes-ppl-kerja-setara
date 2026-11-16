<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class CompanyProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'description',
        'industry',
        'location',
        'accessibility_features',
        'logo_path',
        'website',
        'phone',
        'nib_file',
        'npwp_file',
        'is_inclusive_certified',
        'employee_count',
        'verification_status',
        'verification_notes',
        'verified_at',
        'verified_by',
        'rejection_reason',
        'revision_reason',
    ];

    protected $casts = [
        'accessibility_features' => 'array',
        'is_inclusive_certified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    // ─── Relations ────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function vacancies(): HasMany
    {
        return $this->hasMany(JobVacancy::class, 'company_id');
    }

    public function activeVacancies(): HasMany
    {
        return $this->hasMany(JobVacancy::class, 'company_id')->where('is_active', true);
    }

    // ─── Accessors & Helpers ──────────────────────────────────────────────

    public function getIdentifierAttribute(): string
    {
        return sprintf('KS-C%03d', $this->id);
    }

    public function isVerified(): bool
    {
        return $this->verification_status === 'verified';
    }

    public function isPending(): bool
    {
        return in_array($this->verification_status, ['pending', null], true);
    }

    public function isRejected(): bool
    {
        return $this->verification_status === 'rejected';
    }

    public function needsRevision(): bool
    {
        return in_array($this->verification_status, ['needs_revision', 'revision'], true);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->verification_status) {
            'verified' => 'Terverifikasi',
            'rejected' => 'Ditolak',
            'needs_revision', 'revision' => 'Perlu Revisi',
            default => 'Pending',
        };
    }

    public function getStatusSubtitleAttribute(): string
    {
        if ($this->isVerified()) {
            $date = $this->verified_at ? $this->verified_at->translatedFormat('d M') : 'Disetujui';

            return 'Disetujui '.$date;
        }

        if ($this->isRejected()) {
            return $this->rejection_reason ? Str::limit($this->rejection_reason, 26) : 'Izin tidak sesuai';
        }

        if ($this->needsRevision()) {
            return $this->revision_reason ? Str::limit($this->revision_reason, 26) : 'Perlu revisi berkas';
        }

        return 'Menunggu tinjauan';
    }

    public function getFormattedRegisteredDateAttribute(): string
    {
        $months = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
            7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
        ];

        $date = $this->created_at ?? Carbon::now();
        $month = $months[$date->month] ?? $date->format('M');

        return sprintf('%02d %s %d', $date->day, $month, $date->year);
    }

    public function getFormattedRegisteredTimeAttribute(): string
    {
        $date = $this->created_at ?? Carbon::now();

        return $date->setTimezone('Asia/Jakarta')->format('H.i').' WIB';
    }

    public function hasNibDocument(): bool
    {
        return ! empty($this->nib_file);
    }

    public function hasNpwpDocument(): bool
    {
        return ! empty($this->npwp_file);
    }

    public function getNibFileNameAttribute(): string
    {
        return $this->nib_file ?? ('nib_'.Str::slug($this->company_name, '_').'_contoh.pdf');
    }

    public function getNpwpFileNameAttribute(): string
    {
        return $this->npwp_file ?? ('npwp_'.Str::slug($this->company_name, '_').'_contoh.pdf');
    }

    // ─── Scopes ───────────────────────────────────────────────────────────

    public function scopeStatus(Builder $query, ?string $status): Builder
    {
        if (! $status || $status === 'all') {
            return $query;
        }

        if ($status === 'pending') {
            return $query->where(function ($q) {
                $q->where('verification_status', 'pending')
                    ->orWhereNull('verification_status');
            });
        }

        if ($status === 'verified' || $status === 'terverifikasi') {
            return $query->where('verification_status', 'verified');
        }

        if ($status === 'rejected' || $status === 'ditolak') {
            return $query->where('verification_status', 'rejected');
        }

        if ($status === 'needs_revision' || $status === 'revision' || $status === 'perlu_revisi') {
            return $query->whereIn('verification_status', ['needs_revision', 'revision']);
        }

        return $query->where('verification_status', $status);
    }

    public function scopeSearch(Builder $query, ?string $keyword): Builder
    {
        if (! $keyword) {
            return $query;
        }

        $keyword = trim($keyword);

        return $query->where(function ($q) use ($keyword) {
            $q->where('company_name', 'LIKE', "%{$keyword}%")
                ->orWhere('industry', 'LIKE', "%{$keyword}%")
                ->orWhere('location', 'LIKE', "%{$keyword}%")
                ->orWhere('description', 'LIKE', "%{$keyword}%")
                ->orWhereHas('user', function ($uq) use ($keyword) {
                    $uq->where('name', 'LIKE', "%{$keyword}%")
                        ->orWhere('email', 'LIKE', "%{$keyword}%");
                });

            if (preg_match('/^KS-C(\d+)$/i', $keyword, $matches)) {
                $q->orWhere('id', (int) $matches[1]);
            } elseif (is_numeric($keyword)) {
                $q->orWhere('id', (int) $keyword);
            }
        });
    }
}
