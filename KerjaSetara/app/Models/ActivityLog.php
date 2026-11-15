<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_name',
        'user_role',
        'action_type',
        'description',
        'level',
        'result',
        'ip_address',
        'target_type',
        'target_id',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    // ─── Relations ────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ─── Static Helper ────────────────────────────────────────────────────

    /**
     * Create a new activity log entry.
     *
     * @param  string  $actionType  e.g. 'login', 'create', 'update', 'delete', 'verify', 'block'
     * @param  string  $description  Human-readable description
     * @param  array  $options  Optional overrides: level, result, user, ip, target_type, target_id, metadata
     */
    public static function log(string $actionType, string $description, array $options = []): static
    {
        $user = $options['user'] ?? Auth::user();
        $ip = $options['ip'] ?? Request::ip();

        return static::create([
            'user_id' => $user?->id,
            'user_name' => $user?->name,
            'user_role' => $user?->role,
            'action_type' => $actionType,
            'description' => $description,
            'level' => $options['level'] ?? 'info',
            'result' => $options['result'] ?? 'success',
            'ip_address' => $ip,
            'target_type' => $options['target_type'] ?? null,
            'target_id' => $options['target_id'] ?? null,
            'metadata' => $options['metadata'] ?? null,
        ]);
    }

    // ─── Scopes ───────────────────────────────────────────────────────────

    public function scopeDateRange($query, ?string $from, ?string $to)
    {
        if ($from) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to) {
            $query->whereDate('created_at', '<=', $to);
        }

        return $query;
    }

    public function scopeOfLevel($query, string $level)
    {
        return $query->where('level', $level);
    }

    public function scopeOfRole($query, string $role)
    {
        return $query->where('user_role', $role);
    }

    public function scopeOfActionType($query, string $actionType)
    {
        return $query->where('action_type', $actionType);
    }

    public function scopeSearch($query, ?string $keyword)
    {
        if (! $keyword) {
            return $query;
        }

        return $query->where(function ($q) use ($keyword) {
            $q->where('description', 'LIKE', "%{$keyword}%")
                ->orWhere('user_name', 'LIKE', "%{$keyword}%")
                ->orWhere('action_type', 'LIKE', "%{$keyword}%")
                ->orWhere('target_type', 'LIKE', "%{$keyword}%");
        });
    }
}
