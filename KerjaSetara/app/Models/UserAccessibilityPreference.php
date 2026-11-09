<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAccessibilityPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category',
        'wheelchair_access',
        'sign_language',
        'screen_reader',
        'quiet_room',
        'accessible_restroom',
        'flexible_hours',
        'adjustable_desk',
        'accessible_parking',
        'braille',
        'high_contrast',
        'additional_preferences',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'wheelchair_access' => 'boolean',
            'sign_language' => 'boolean',
            'screen_reader' => 'boolean',
            'quiet_room' => 'boolean',
            'accessible_restroom' => 'boolean',
            'flexible_hours' => 'boolean',
            'adjustable_desk' => 'boolean',
            'accessible_parking' => 'boolean',
            'braille' => 'boolean',
            'high_contrast' => 'boolean',
            'additional_preferences' => 'array',
        ];
    }

    /**
     * Get the user that owns the accessibility preference.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Map of standard accessibility features, labels, and matching tags.
     */
    public static function standardFacilityTaxonomy(): array
    {
        return [
            'wheelchair_access' => [
                'label' => 'Akses Kursi Roda',
                'tags' => ['wheelchair_access', 'ramp', 'lift', 'ramped_access', 'kursi_roda'],
                'icon' => 'wheelchair',
            ],
            'sign_language' => [
                'label' => 'Penerjemah Isyarat',
                'tags' => ['sign_language', 'interpreter', 'bisindo', 'bahasa_isyarat'],
                'icon' => 'hands',
            ],
            'screen_reader' => [
                'label' => 'Screen Reader Software',
                'tags' => ['screen_reader', 'software_pembaca_layar', 'pembaca_layar'],
                'icon' => 'screen-reader',
            ],
            'quiet_room' => [
                'label' => 'Ruang Tenang',
                'tags' => ['quiet_room', 'ruang_tenang'],
                'icon' => 'sun',
            ],
            'accessible_restroom' => [
                'label' => 'Toilet Ramah Disabilitas',
                'tags' => ['toilet_khusus', 'toilet_disabilitas', 'accessible_restroom'],
                'icon' => 'toilet',
            ],
            'flexible_hours' => [
                'label' => 'Jam Kerja Fleksibel',
                'tags' => ['jam_fleksibel', 'flexible_hours', 'wfh_fleksibel', 'remote'],
                'icon' => 'clock',
            ],
            'adjustable_desk' => [
                'label' => 'Meja Kerja Adjustable',
                'tags' => ['meja_adjustable', 'adjustable_desk', 'sit_to_stand'],
                'icon' => 'table',
            ],
            'accessible_parking' => [
                'label' => 'Parkir Khusus Disabilitas',
                'tags' => ['parkir_disabilitas', 'accessible_parking'],
                'icon' => 'parking',
            ],
            'braille' => [
                'label' => 'Materi / Navigasi Braille',
                'tags' => ['braille'],
                'icon' => 'braille',
            ],
            'high_contrast' => [
                'label' => 'Mode Kontras Tinggi',
                'tags' => ['high_contrast'],
                'icon' => 'contrast',
            ],
        ];
    }

    /**
     * Get active preferences with metadata (labels, tags, icons).
     */
    public function getActivePreferencesWithMetadata(): array
    {
        $taxonomy = static::standardFacilityTaxonomy();
        $active = [];

        foreach ($taxonomy as $key => $meta) {
            if ($this->{$key}) {
                $active[$key] = $meta;
            }
        }

        $aliases = [
            'kursi_roda' => 'wheelchair_access',
            'ramped_access' => 'wheelchair_access',
            'bisindo' => 'sign_language',
            'bahasa_isyarat' => 'sign_language',
            'toilet_disabilitas' => 'accessible_restroom',
            'toilet_khusus' => 'accessible_restroom',
            'ruang_tenang' => 'quiet_room',
            'jam_fleksibel' => 'flexible_hours',
            'wfh_fleksibel' => 'flexible_hours',
            'meja_adjustable' => 'adjustable_desk',
            'parkir_disabilitas' => 'accessible_parking',
        ];

        if (! empty($this->additional_preferences) && is_array($this->additional_preferences)) {
            foreach ($this->additional_preferences as $slug) {
                $targetKey = $aliases[$slug] ?? $slug;
                if (isset($taxonomy[$targetKey])) {
                    if (! isset($active[$targetKey])) {
                        $active[$targetKey] = $taxonomy[$targetKey];
                    }
                } elseif (! isset($active[$slug])) {
                    $active[$slug] = [
                        'label' => ucwords(str_replace('_', ' ', $slug)),
                        'tags' => [$slug],
                        'icon' => 'check-circle',
                    ];
                }
            }
        }

        // If no explicit preferences toggled yet, use category defaults
        if (empty($active)) {
            $cat = strtolower($this->category ?? 'fisik');
            $active = match ($cat) {
                'komunikasi' => [
                    'wheelchair_access' => $taxonomy['wheelchair_access'],
                    'sign_language' => $taxonomy['sign_language'],
                    'screen_reader' => $taxonomy['screen_reader'],
                ],
                'digital' => [
                    'screen_reader' => $taxonomy['screen_reader'],
                    'high_contrast' => $taxonomy['high_contrast'],
                    'flexible_hours' => $taxonomy['flexible_hours'],
                ],
                'neurodiverse' => [
                    'quiet_room' => $taxonomy['quiet_room'],
                    'flexible_hours' => $taxonomy['flexible_hours'],
                    'wheelchair_access' => $taxonomy['wheelchair_access'],
                ],
                default => [
                    'wheelchair_access' => $taxonomy['wheelchair_access'],
                    'accessible_restroom' => $taxonomy['accessible_restroom'],
                    'sign_language' => $taxonomy['sign_language'],
                ],
            };
        }

        return $active;
    }

    /**
     * Get active preferences as list of key strings.
     */
    public function getActivePreferences(): array
    {
        return array_keys($this->getActivePreferencesWithMetadata());
    }
}
