<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'is_inclusive_certified',
        'employee_count',
    ];

    protected $casts = [
        'accessibility_features' => 'array',
        'is_inclusive_certified' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vacancies()
    {
        return $this->hasMany(JobVacancy::class, 'company_id');
    }

    public function activeVacancies()
    {
        return $this->hasMany(JobVacancy::class, 'company_id')->where('is_active', true);
    }
}
