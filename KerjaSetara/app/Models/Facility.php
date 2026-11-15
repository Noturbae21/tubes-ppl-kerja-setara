<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    // ─── Relations ────────────────────────────────────────────────────────

    public function jobVacancies(): BelongsToMany
    {
        return $this->belongsToMany(JobVacancy::class, 'facility_job_vacancy');
    }

    // ─── Accessors ────────────────────────────────────────────────────────

    public function getUsageCountAttribute(): int
    {
        return $this->jobVacancies()->count();
    }
}
