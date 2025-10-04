<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityLogController extends Controller
{
    /**
     * Display activity logs with filtering options
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(50);

        // Get filter options
        $actions = ActivityLog::distinct()->pluck('action')->sort();
        $users = User::select('id', 'name')->orderBy('name')->get();
        $severities = ['low', 'medium', 'high', 'critical'];

        // Get statistics
        $stats = [
            'total_logs' => ActivityLog::count(),
            'today_logs' => ActivityLog::whereDate('created_at', today())->count(),
            'critical_logs' => ActivityLog::where('severity', 'critical')->whereDate('created_at', '>=', now()->subDays(7))->count(),
            'failed_logins' => ActivityLog::where('action', 'login_failed')->whereDate('created_at', today())->count(),
        ];

        return view('logs.index', compact('logs', 'actions', 'users', 'severities', 'stats'));
    }

    /**
     * Show detailed view of a specific log entry
     */
    public function show(ActivityLog $log)
    {
        return view('logs.show', compact('log'));
    }

    /**
     * Get security dashboard data
     */
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'locked_users' => User::whereNotNull('locked_until')->where('locked_until', '>', now())->count(),
            'recent_activities' => ActivityLog::with('user')->recent(1)->limit(10)->get(),
            'security_alerts' => ActivityLog::whereIn('severity', ['high', 'critical'])->recent(7)->count(),
            'failed_logins_today' => ActivityLog::where('action', 'login_failed')->whereDate('created_at', today())->count(),
        ];

        return view('logs.dashboard', compact('stats'));
    }

    /**
     * Clear old logs (admin only)
     */
    public function cleanup(Request $request)
    {
        $request->validate([
            'days' => 'required|integer|min:30|max:365'
        ]);

        $cutoffDate = now()->subDays($request->days);
        $deletedCount = ActivityLog::where('created_at', '<', $cutoffDate)->delete();

        ActivityLog::log([
            'action' => 'logs_cleanup',
            'description' => "Cleaned up {$deletedCount} log entries older than {$request->days} days",
            'severity' => 'medium',
            'properties' => [
                'deleted_count' => $deletedCount,
                'cutoff_date' => $cutoffDate->toDateString()
            ]
        ]);

        return redirect()->route('logs.index')
            ->with('success', "Successfully deleted {$deletedCount} old log entries.");
    }
}
