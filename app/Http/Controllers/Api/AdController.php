<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AdController extends Controller
{
    /**
     * Display a listing of ads
     */
    public function index(Request $request): JsonResponse
    {
        $search = $request->get('search');
        $type = $request->get('type');
        $status = $request->get('status');
        $perPage = $request->get('per_page', 15);
        
        $ads = Ad::query()
            ->when($search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('target_audience', 'like', "%{$search}%");
            })
            ->when($type, function ($query, $type) {
                $query->where('type', $type);
            })
            ->when($status !== null, function ($query) use ($status) {
                $query->where('is_active', $status === 'active');
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $ads,
            'message' => 'Ads retrieved successfully'
        ]);
    }

    /**
     * Store a newly created ad
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:banner,popup,inline,sidebar',
            'content_html' => 'nullable|string',
            'image_url' => 'nullable|url',
            'target_url' => 'nullable|url',
            'target_audience' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean'
        ]);
        
        $validated['click_count'] = 0;
        $validated['impression_count'] = 0;
        
        $ad = Ad::create($validated);
        
        return response()->json([
            'success' => true,
            'data' => $ad,
            'message' => 'Ad created successfully'
        ], 201);
    }

    /**
     * Display the specified ad
     */
    public function show(Ad $ad): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $ad,
            'message' => 'Ad retrieved successfully'
        ]);
    }

    /**
     * Update the specified ad
     */
    public function update(Request $request, Ad $ad): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:banner,popup,inline,sidebar',
            'content_html' => 'nullable|string',
            'image_url' => 'nullable|url',
            'target_url' => 'nullable|url',
            'target_audience' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean'
        ]);
        
        $ad->update($validated);
        
        return response()->json([
            'success' => true,
            'data' => $ad->fresh(),
            'message' => 'Ad updated successfully'
        ]);
    }

    /**
     * Remove the specified ad
     */
    public function destroy(Ad $ad): JsonResponse
    {
        $ad->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Ad deleted successfully'
        ]);
    }

    /**
     * Get active ads for public display
     */
    public function active(Request $request): JsonResponse
    {
        $type = $request->get('type');
        
        $ads = Ad::where('is_active', true)
            ->when($type, function ($query, $type) {
                $query->where('type', $type);
            })
            ->where(function ($query) {
                $query->whereNull('start_date')
                    ->orWhere('start_date', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            })
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $ads,
            'message' => 'Active ads retrieved successfully'
        ]);
    }

    /**
     * Track ad impression
     */
    public function trackImpression(Ad $ad): JsonResponse
    {
        $ad->increment('impression_count');
        
        return response()->json([
            'success' => true,
            'message' => 'Impression tracked successfully'
        ]);
    }

    /**
     * Track ad click
     */
    public function trackClick(Ad $ad): JsonResponse
    {
        $ad->increment('click_count');
        
        return response()->json([
            'success' => true,
            'message' => 'Click tracked successfully'
        ]);
    }

    /**
     * Toggle ad active status
     */
    public function toggleStatus(Ad $ad): JsonResponse
    {
        $ad->update(['is_active' => !$ad->is_active]);
        
        return response()->json([
            'success' => true,
            'data' => $ad->fresh(),
            'message' => $ad->is_active ? 'Ad activated' : 'Ad deactivated'
        ]);
    }

    /**
     * Perform bulk actions on ads
     */
    public function bulkAction(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'ids' => 'required|array',
            'ids.*' => 'exists:ads,id'
        ]);

        $ads = Ad::whereIn('id', $validated['ids']);

        switch ($validated['action']) {
            case 'activate':
                $ads->update(['is_active' => true]);
                $message = "Activated {$ads->count()} ads";
                break;
            case 'deactivate':
                $ads->update(['is_active' => false]);
                $message = "Deactivated {$ads->count()} ads";
                break;
            case 'delete':
                $count = $ads->count();
                $ads->delete();
                $message = "Deleted {$count} ads";
                break;
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    /**
     * Get ad analytics and performance data
     */
    public function analytics(Request $request): JsonResponse
    {
        $dateFrom = $request->get('date_from', now()->subDays(30));
        $dateTo = $request->get('date_to', now());
        
        $analytics = [
            'total_ads' => Ad::whereBetween('created_at', [$dateFrom, $dateTo])->count(),
            'active_ads' => Ad::where('is_active', true)->count(),
            'total_impressions' => Ad::sum('impression_count'),
            'total_clicks' => Ad::sum('click_count'),
            'click_through_rate' => Ad::sum('impression_count') > 0 
                ? round((Ad::sum('click_count') / Ad::sum('impression_count')) * 100, 2) 
                : 0,
            'top_performing_ads' => Ad::selectRaw('id, title, click_count, impression_count, 
                CASE WHEN impression_count > 0 THEN (click_count / impression_count) * 100 ELSE 0 END as ctr')
                ->orderByRaw('ctr DESC')
                ->limit(5)
                ->get(),
            'ads_by_type' => Ad::selectRaw('type, COUNT(*) as count, SUM(click_count) as total_clicks, SUM(impression_count) as total_impressions')
                ->groupBy('type')
                ->get()
        ];
        
        return response()->json([
            'success' => true,
            'data' => $analytics,
            'message' => 'Ad analytics retrieved successfully'
        ]);
    }
}