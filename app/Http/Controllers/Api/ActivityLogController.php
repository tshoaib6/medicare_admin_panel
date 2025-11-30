<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of activity logs
     */
    public function index(Request $request): JsonResponse
    {
        $search = $request->get('search');
        $user = $request->get('user_id');
        $action = $request->get('action');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $perPage = $request->get('per_page', 20);
        
        $activities = ActivityLog::with('user')
            ->when($search, function ($query, $search) {
                $query->where('description', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('first_name', 'like', "%{$search}%")
                          ->orWhere('last_name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                    });
            })
            ->when($user, function ($query, $user) {
                $query->where('user_id', $user);
            })
            ->when($action, function ($query, $action) {
                $query->where('action', $action);
            })
            ->when($dateFrom, function ($query, $dateFrom) {
                $query->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($dateTo, function ($query, $dateTo) {
                $query->whereDate('created_at', '<=', $dateTo);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $activities,
            'message' => 'Activity logs retrieved successfully'
        ]);
    }

    /**
     * Display the specified activity log
     */
    public function show(ActivityLog $activityLog): JsonResponse
    {
        $activityLog->load('user');
        
        return response()->json([
            'success' => true,
            'data' => $activityLog,
            'message' => 'Activity log retrieved successfully'
        ]);
    }

    /**
     * Get activity statistics
     */
    public function stats(Request $request): JsonResponse
    {
        $dateFrom = $request->get('date_from', now()->subDays(30));
        $dateTo = $request->get('date_to', now());
        
        $stats = [
            'total_activities' => ActivityLog::whereBetween('created_at', [$dateFrom, $dateTo])->count(),
            'unique_users' => ActivityLog::whereBetween('created_at', [$dateFrom, $dateTo])
                ->distinct('user_id')->count('user_id'),
            'top_actions' => ActivityLog::whereBetween('created_at', [$dateFrom, $dateTo])
                ->selectRaw('action, COUNT(*) as count')
                ->groupBy('action')
                ->orderByDesc('count')
                ->limit(5)
                ->get(),
            'daily_activities' => ActivityLog::whereBetween('created_at', [$dateFrom, $dateTo])
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date')
                ->get()
        ];
        
        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'Activity statistics retrieved successfully'
        ]);
    }

    /**
     * Get activities for authenticated user
     */
    public function myActivities(Request $request): JsonResponse
    {
        $user = $request->user();
        $perPage = $request->get('per_page', 20);
        
        $activities = ActivityLog::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $activities,
            'message' => 'Your activities retrieved successfully'
        ]);
    }

    /**
     * Log a new activity
     */
    public function log(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'action' => 'required|string',
            'description' => 'required|string',
            'metadata' => 'nullable|array'
        ]);
        
        $validated['user_id'] = $request->user()->id ?? null;
        $validated['ip_address'] = $request->ip();
        $validated['user_agent'] = $request->userAgent();
        
        $activity = ActivityLog::create($validated);
        $activity->load('user');
        
        return response()->json([
            'success' => true,
            'data' => $activity,
            'message' => 'Activity logged successfully'
        ], 201);
    }

    /**
     * Export activity logs as CSV
     */
    public function export(Request $request): JsonResponse
    {
        $dateFrom = $request->get('date_from', now()->subDays(30));
        $dateTo = $request->get('date_to', now());
        
        $activities = ActivityLog::with('user')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->orderBy('created_at', 'desc')
            ->get();

        $csvData = [];
        $csvData[] = ['ID', 'User', 'Action', 'Description', 'IP Address', 'User Agent', 'Date'];

        foreach ($activities as $activity) {
            $csvData[] = [
                $activity->id,
                $activity->user ? $activity->user->name : 'Guest',
                $activity->action,
                $activity->description,
                $activity->ip_address,
                $activity->user_agent,
                $activity->created_at->format('Y-m-d H:i:s')
            ];
        }

        $filename = 'activity_logs_' . now()->format('Y_m_d_H_i_s') . '.csv';
        
        // Convert to CSV string
        $csvString = '';
        foreach ($csvData as $row) {
            $csvString .= '"' . implode('","', $row) . '"' . "\n";
        }

        return response()->json([
            'success' => true,
            'data' => [
                'filename' => $filename,
                'content' => base64_encode($csvString),
                'rows_exported' => count($activities)
            ],
            'message' => 'Activity logs exported successfully'
        ]);
    }
}