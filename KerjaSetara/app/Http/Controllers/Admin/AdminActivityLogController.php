<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminActivityLogController extends Controller
{
    /**
     * Display the System & Security Activity Log (Log Aktivitas Sistem & Keamanan).
     */
    public function index(Request $request): View
    {
        $search = trim($request->query('search', ''));
        $levelFilter = $request->query('level', 'all');
        $roleFilter = $request->query('role', 'all');
        $actionFilter = $request->query('action', 'all');
        $dateRange = $request->query('date_range', '');
        $dateFrom = $request->query('date_from', '');
        $dateTo = $request->query('date_to', '');

        $query = ActivityLog::with('user')->latest('id');

        // 1. Search Query
        if (! empty($search)) {
            $query->search($search);
        }

        // 2. Level Filter (info, warning, error)
        if ($levelFilter !== 'all' && in_array($levelFilter, ['info', 'warning', 'error'], true)) {
            $query->where('level', $levelFilter);
        }

        // 3. Role Filter
        if ($roleFilter !== 'all') {
            if ($roleFilter === 'admin') {
                $query->where('user_role', 'admin');
            } elseif ($roleFilter === 'perusahaan' || $roleFilter === 'hrd') {
                $query->whereIn('user_role', ['hrd', 'company', 'perusahaan']);
            } elseif ($roleFilter === 'kandidat' || $roleFilter === 'user') {
                $query->whereIn('user_role', ['kandidat', 'user']);
            } elseif ($roleFilter === 'sistem') {
                $query->whereIn('user_role', ['sistem', 'system']);
            } elseif ($roleFilter === 'tamu') {
                $query->where(function ($q) {
                    $q->whereNull('user_role')
                        ->orWhereIn('user_role', ['tamu', 'guest']);
                });
            }
        }

        // 4. Action Type Filter
        if ($actionFilter !== 'all') {
            $query->where('action_type', $actionFilter);
        }

        // 5. Date Range Filter
        if (! empty($dateRange) && str_contains($dateRange, ' - ')) {
            [$fromStr, $toStr] = explode(' - ', $dateRange, 2);
            try {
                $query->whereDate('created_at', '>=', Carbon::parse($fromStr)->startOfDay())
                    ->whereDate('created_at', '<=', Carbon::parse($toStr)->endOfDay());
            } catch (\Exception $e) {
                // Ignore parse errors, continue
            }
        } else {
            if (! empty($dateFrom)) {
                $query->whereDate('created_at', '>=', $dateFrom);
            }
            if (! empty($dateTo)) {
                $query->whereDate('created_at', '<=', $dateTo);
            }
        }

        $logs = $query->paginate(10)->withQueryString();

        // Level Counts for summary / pills
        $levelCounts = [
            'info' => ActivityLog::where('level', 'info')->count(),
            'warning' => ActivityLog::where('level', 'warning')->count(),
            'error' => ActivityLog::where('level', 'error')->count(),
        ];

        return view('admin.activity_logs.index', compact(
            'logs',
            'search',
            'levelFilter',
            'roleFilter',
            'actionFilter',
            'dateRange',
            'dateFrom',
            'dateTo',
            'levelCounts'
        ));
    }
}
