<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of all activity logs with analytics
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user');
        
        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        if ($request->filled('action')) {
            $query->where('action', 'like', '%' . $request->action . '%');
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        if ($request->filled('ip_address')) {
            $query->where('ip_address', $request->ip_address);
        }
        
        // Get filtered results
        $activities = $query->orderByDesc('created_at')
                           ->paginate(25)
                           ->withQueryString();
        
        // Get statistics
        $stats = $this->getActivityStatistics();
        
        // Get filter options
        $users = User::orderBy('first_name')->get();
        $actions = ActivityLog::distinct()
                             ->orderBy('action')
                             ->pluck('action');
        $topIPs = ActivityLog::selectRaw('ip_address, COUNT(*) as count')
                            ->whereNotNull('ip_address')
                            ->groupBy('ip_address')
                            ->orderByDesc('count')
                            ->limit(10)
                            ->pluck('ip_address');
        
        return view('admin.activity-logs.index', compact(
            'activities', 
            'stats',
            'users', 
            'actions',
            'topIPs'
        ));
    }

    /**
     * Display the specified activity log with detailed information
     */
    public function show($id)
    {
        $activity = ActivityLog::with('user')->findOrFail($id);
        
        // Get related activities (same user, similar time)
        $relatedActivities = ActivityLog::with('user')
            ->where('id', '!=', $activity->id)
            ->where(function($query) use ($activity) {
                $query->where('user_id', $activity->user_id)
                      ->orWhere('ip_address', $activity->ip_address);
            })
            ->whereBetween('created_at', [
                $activity->created_at->subHours(2),
                $activity->created_at->addHours(2)
            ])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();
        
        return view('admin.activity-logs.show', compact('activity', 'relatedActivities'));
    }

    /**
     * Export activity logs as CSV
     */
    public function export(Request $request)
    {
        $query = ActivityLog::with('user');
        
        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        if ($request->filled('action')) {
            $query->where('action', 'like', '%' . $request->action . '%');
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $activities = $query->orderByDesc('created_at')->get();
        
        $filename = 'activity-logs-' . now()->format('Y-m-d-H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}"
        ];
        
        $callback = function() use ($activities) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'ID', 'Date/Time', 'User', 'Email', 'Action', 'Description', 
                'IP Address', 'Browser', 'Metadata'
            ]);
            
            // CSV Data
            foreach ($activities as $activity) {
                fputcsv($file, [
                    $activity->id,
                    $activity->created_at->format('Y-m-d H:i:s'),
                    $activity->user ? $activity->user->name : 'System',
                    $activity->user ? $activity->user->email : 'N/A',
                    $activity->action_label,
                    $activity->description,
                    $activity->ip_address ?? 'N/A',
                    $activity->browser,
                    $activity->metadata ? json_encode($activity->metadata) : 'N/A'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get activity statistics for dashboard
     */
    private function getActivityStatistics()
    {
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();
        
        return [
            'total' => ActivityLog::count(),
            'today' => ActivityLog::whereDate('created_at', $today)->count(),
            'this_week' => ActivityLog::where('created_at', '>=', $thisWeek)->count(),
            'this_month' => ActivityLog::where('created_at', '>=', $thisMonth)->count(),
            'unique_users_today' => ActivityLog::whereDate('created_at', $today)
                                              ->distinct('user_id')
                                              ->whereNotNull('user_id')
                                              ->count('user_id'),
            'unique_ips_today' => ActivityLog::whereDate('created_at', $today)
                                            ->distinct('ip_address')
                                            ->whereNotNull('ip_address')
                                            ->count('ip_address'),
            'top_actions' => ActivityLog::selectRaw('action, COUNT(*) as count')
                                       ->groupBy('action')
                                       ->orderByDesc('count')
                                       ->limit(5)
                                       ->get(),
            'hourly_activity' => ActivityLog::selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
                                           ->whereDate('created_at', $today)
                                           ->groupBy('hour')
                                           ->orderBy('hour')
                                           ->pluck('count', 'hour')
                                           ->toArray()
        ];
    }
}
