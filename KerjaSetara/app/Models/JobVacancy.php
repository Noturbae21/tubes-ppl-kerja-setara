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
                ->orWhere('location', 'LIKE', "%{$keyword}%");
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
}
