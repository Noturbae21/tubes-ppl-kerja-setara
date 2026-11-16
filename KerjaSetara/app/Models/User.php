<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'full_name',
        'headline',
        'email',
        'password',
        'role',
        'status',
        'must_change_password',
        'phone',
        'city',
        'bio',
        'avatar',
        'resume_file',
        'portfolio_url',
        'linkedin_url',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'must_change_password' => 'boolean',
        ];
    }

    public function isActive(): bool
    {
        return ($this->status ?? 'active') === 'active';
    }

    public function isBlocked(): bool
    {
        return $this->status === 'blocked';
    }

    public function getIdentifierAttribute(): string
    {
        $prefix = match ($this->role) {
            'admin' => 'KS-A',
            'hrd', 'company' => 'KS-C',
            default => 'KS-U',
        };

        return sprintf('%s%03d', $prefix, $this->id);
    }

    public function getFormattedJoinedDateAttribute(): string
    {
        $months = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
            7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
        ];

        return sprintf('%02d %s %d', $this->created_at->day, $months[$this->created_at->month] ?? $this->created_at->format('M'), $this->created_at->year);
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'admin' => 'Admin',
            'hrd', 'company' => 'Perusahaan',
            default => 'Kandidat',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->isBlocked() ? 'Diblokir' : 'Aktif';
    }

    public function getInitialsAttribute(): string
    {
        $name = trim($this->name ?? $this->full_name ?? 'Admin');
        $words = preg_split('/\s+/', $name);
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1).substr($words[1], 0, 1));
        }

        return strtoupper(substr($name, 0, 2));
    }

    public function getAvatarUrlAttribute(): ?string
    {
        if (! $this->avatar) {
            return null;
        }

        if (str_starts_with($this->avatar, 'http://') || str_starts_with($this->avatar, 'https://')) {
            return $this->avatar;
        }

        return Storage::disk('public')->url($this->avatar);
    }

    public function accessibilityPreference(): HasOne
    {
        return $this->hasOne(UserAccessibilityPreference::class);
    }

    public function accessibilityPreferences(): HasOne
    {
        return $this->hasOne(UserAccessibilityPreference::class);
    }

    public function getAccessibilityCategoryAttribute(): ?string
    {
        return $this->accessibilityPreference?->category;
    }

    public function getAccessibilityPreferencesAttribute(): array
    {
        if ($this->accessibilityPreference && ! empty($this->accessibilityPreference->additional_preferences)) {
            return $this->accessibilityPreference->additional_preferences;
        }

        return $this->accessibilityPreference?->getActivePreferences() ?? [];
    }

    public function getAccessibilityNotesAttribute(): ?string
    {
        return $this->accessibilityPreference?->notes;
    }

    public function companyProfile()
    {
        return $this->hasOne(CompanyProfile::class);
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function savedJobs()
    {
        return $this->hasMany(SavedJob::class);
    }

    public function hasAppliedTo(int $jobVacancyId): bool
    {
        return $this->applications()->where('job_vacancy_id', $jobVacancyId)->exists();
    }

    public function hasSaved(int $jobVacancyId): bool
    {
        return $this->savedJobs()->where('job_vacancy_id', $jobVacancyId)->exists();
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isHrd(): bool
    {
        return in_array($this->role, ['hrd', 'company']);
    }

    public function isCompany(): bool
    {
        return in_array($this->role, ['hrd', 'company']);
    }

    public function getOrCreateCompanyProfile(): CompanyProfile
    {
        return $this->companyProfile ?? CompanyProfile::firstOrCreate(
            ['user_id' => $this->id],
            [
                'company_name' => $this->name ? (str_starts_with($this->name, 'PT ') ? $this->name : 'PT '.$this->name) : 'PT Solusi Inklusif Indonesia',
                'industry' => 'Teknologi Informasi & Konsultan',
                'location' => $this->city ?? 'Jakarta Selatan',
                'description' => 'Profil perusahaan inklusif untuk rekrutmen talenta disabilitas.',
                'accessibility_features' => ['ramp', 'toilet_khusus', 'screen_reader'],
                'employee_count' => 50,
                'is_inclusive_certified' => true,
            ]
        );
    }

    public function isKandidat(): bool
    {
        return in_array($this->role, ['kandidat', 'user']);
    }

    public function isUser(): bool
    {
        return in_array($this->role, ['kandidat', 'user']);
    }
}
