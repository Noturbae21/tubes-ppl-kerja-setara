<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Facility extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'icon_path',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $appends = [
        'identifier',
        'usage_count',
        'status_label',
        'icon_filename',
    ];

    // ─── Relations ────────────────────────────────────────────────────────

    public function jobVacancies(): BelongsToMany
    {
        return $this->belongsToMany(JobVacancy::class, 'facility_job_vacancy');
    }

    // ─── Accessors ────────────────────────────────────────────────────────

    public function getIdentifierAttribute(): string
    {
        return sprintf('FAS-%03d', $this->id);
    }

    public function getUsageCountAttribute(): int
    {
        return $this->jobVacancies()->count();
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->is_active ? 'Aktif' : 'Nonaktif';
    }

    public function getIconFilenameAttribute(): string
    {
        if (! $this->icon_path) {
            return 'default.svg';
        }

        return basename($this->icon_path);
    }

    public function getIconUrlAttribute(): ?string
    {
        if (! $this->icon_path) {
            return null;
        }

        if (str_starts_with($this->icon_path, 'http://') || str_starts_with($this->icon_path, 'https://')) {
            return $this->icon_path;
        }

        return route('admin.facilities.icon', $this);
    }

    public function getSvgContentAttribute(): ?string
    {
        if (! $this->icon_path) {
            return $this->getDefaultSvgIcon();
        }

        if (Storage::disk('public')->exists($this->icon_path)) {
            return Storage::disk('public')->get($this->icon_path);
        }

        // Check if file exists directly in public
        $publicPath = public_path('storage/' . $this->icon_path);
        if (file_exists($publicPath)) {
            return file_get_contents($publicPath);
        }

        $directPath = public_path($this->icon_path);
        if (file_exists($directPath)) {
            return file_get_contents($directPath);
        }

        return $this->getDefaultSvgIcon();
    }

    /**
     * Default fallback SVG icon if no custom file exists.
     */
    public function getDefaultSvgIcon(): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6"><circle cx="12" cy="12" r="10"></circle><path d="M12 16v-4"></path><path d="M12 8h.01"></path></svg>';
    }

    // ─── Scopes ───────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeStatus($query, ?string $status)
    {
        if ($status === 'active') {
            return $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            return $query->where('is_active', false);
        }

        return $query;
    }

    public function scopeSearch($query, ?string $search)
    {
        if (! empty($search)) {
            $search = trim($search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");

                if (preg_match('/^FAS-(\d+)$/i', $search, $matches)) {
                    $q->orWhere('id', (int) $matches[1]);
                } elseif (is_numeric($search)) {
                    $q->orWhere('id', (int) $search);
                }
            });
        }

        return $query;
    }
}
