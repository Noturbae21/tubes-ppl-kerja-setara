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

    // ─── Accessors & Helpers ──────────────────────────────────────────────

    public function getFormattedDateAttribute(): string
    {
        $months = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
            7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
        ];

        $date = ($this->created_at ?? now())->setTimezone('Asia/Jakarta');
        $month = $months[$date->month] ?? $date->format('M');

        return sprintf('%02d %s %d', $date->day, $month, $date->year);
    }

    public function getFormattedTimeAttribute(): string
    {
        $date = ($this->created_at ?? now())->setTimezone('Asia/Jakarta');

        return $date->format('H.i.s').' WIB';
    }

    public function getActorIdentifierAttribute(): string
    {
        if ($this->user) {
            return $this->user->identifier;
        }

        if ($this->metadata && isset($this->metadata['identifier'])) {
            return (string) $this->metadata['identifier'];
        }

        if (in_array(strtolower((string) $this->user_role), ['sistem', 'system'], true)) {
            return 'Layanan autentikasi';
        }

        return 'Sesi tidak terautentikasi';
    }

    public function getActorNameAttribute(): string
    {
        if ($this->user_name) {
            return $this->user_name;
        }

        if ($this->user) {
            return $this->user->name;
        }

        if (in_array(strtolower((string) $this->user_role), ['sistem', 'system'], true)) {
            return 'Sistem';
        }

        return 'Aktor tidak dikenal';
    }

    public function getRoleLabelAttribute(): string
    {
        $role = strtolower((string) ($this->user_role ?? 'tamu'));

        return match ($role) {
            'admin' => 'Admin',
            'hrd', 'company', 'perusahaan' => 'Perusahaan',
            'kandidat', 'user' => 'Kandidat',
            'sistem', 'system' => 'Sistem',
            default => 'Tamu',
        };
    }

    public function getActionLabelAttribute(): string
    {
        return match (strtolower($this->action_type)) {
            'create' => 'Create',
            'update' => 'Update',
            'delete' => 'Delete',
            'login' => 'Login berhasil',
            'login_failed' => 'Login gagal',
            'logout' => 'Logout',
            'verify' => 'Verifikasi',
            'reject' => 'Tolak',
            'revision' => 'Minta Revisi',
            'access_denied' => 'Akses ditolak',
            'apply' => 'Create',
            'block' => 'Blokir',
            'activate' => 'Aktivasi',
            default => ucfirst($this->action_type),
        };
    }

    public function getResultLabelAttribute(): string
    {
        $result = strtolower((string) ($this->result ?? 'success'));

        if (in_array($result, ['success', 'berhasil', 'ok'], true)) {
            return 'Berhasil';
        }

        if (in_array($result, ['rejected', 'ditolak', 'denied'], true)) {
            return 'Ditolak';
        }

        return 'Gagal';
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
