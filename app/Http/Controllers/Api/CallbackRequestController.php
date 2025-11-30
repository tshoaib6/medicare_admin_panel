<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CallbackRequest;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CallbackRequestController extends Controller
{
    /**
     * Display a listing of callback requests
     */
    public function index(Request $request): JsonResponse
    {
        $search = $request->get('search');
        $company = $request->get('company_id');
        $status = $request->get('status');
        $user = $request->get('user_id');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $perPage = $request->get('per_page', 15);
        
        $callbacks = CallbackRequest::with(['user', 'company'])
            ->when($search, function ($query, $search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone_number', 'like', "%{$search}%");
                });
            })
            ->when($company, function ($query, $company) {
                $query->where('company_id', $company);
            })
            ->when($user, function ($query, $user) {
                $query->where('user_id', $user);
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($dateFrom, function ($query, $dateFrom) {
                $query->whereDate('call_date', '>=', $dateFrom);
            })
            ->when($dateTo, function ($query, $dateTo) {
                $query->whereDate('call_date', '<=', $dateTo);
            })
            ->orderBy('call_date', 'desc')
            ->orderBy('call_time', 'desc')
            ->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $callbacks,
            'message' => 'Callback requests retrieved successfully'
        ]);
    }

    /**
     * Store a newly created callback request
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'company_id' => 'required|exists:companies,id',
            'call_date' => 'required|date|after_or_equal:today',
            'call_time' => 'required|string',
            'message' => 'nullable|string',
            'status' => 'nullable|in:pending,scheduled,completed,cancelled',
            'admin_notes' => 'nullable|string'
        ]);
        
        $validated['status'] = $validated['status'] ?? 'pending';
        
        $callback = CallbackRequest::create($validated);
        $callback->load(['user', 'company']);
        
        return response()->json([
            'success' => true,
            'data' => $callback,
            'message' => 'Callback request created successfully'
        ], 201);
    }

    /**
     * Display the specified callback request
     */
    public function show(CallbackRequest $callbackRequest): JsonResponse
    {
        $callbackRequest->load(['user', 'company']);
        
        return response()->json([
            'success' => true,
            'data' => $callbackRequest,
            'message' => 'Callback request retrieved successfully'
        ]);
    }

    /**
     * Update the specified callback request
     */
    public function update(Request $request, CallbackRequest $callbackRequest): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'company_id' => 'required|exists:companies,id',
            'call_date' => 'required|date',
            'call_time' => 'required|string',
            'message' => 'nullable|string',
            'status' => 'required|in:pending,scheduled,completed,cancelled',
            'admin_notes' => 'nullable|string'
        ]);
        
        $callbackRequest->update($validated);
        $callbackRequest->load(['user', 'company']);
        
        return response()->json([
            'success' => true,
            'data' => $callbackRequest->fresh(['user', 'company']),
            'message' => 'Callback request updated successfully'
        ]);
    }

    /**
     * Remove the specified callback request
     */
    public function destroy(CallbackRequest $callbackRequest): JsonResponse
    {
        $callbackRequest->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Callback request deleted successfully'
        ]);
    }

    /**
     * Update callback request status
     */
    public function updateStatus(Request $request, CallbackRequest $callbackRequest): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,scheduled,completed,cancelled',
            'admin_notes' => 'nullable|string'
        ]);
        
        $callbackRequest->update($validated);
        $callbackRequest->load(['user', 'company']);
        
        return response()->json([
            'success' => true,
            'data' => $callbackRequest,
            'message' => 'Callback request status updated successfully'
        ]);
    }

    /**
     * Get callback requests for authenticated user
     */
    public function myRequests(Request $request): JsonResponse
    {
        $user = $request->user();
        $perPage = $request->get('per_page', 15);
        
        $callbacks = $user->callbackRequests()
            ->with('company')
            ->orderBy('call_date', 'desc')
            ->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $callbacks,
            'message' => 'Your callback requests retrieved successfully'
        ]);
    }

    /**
     * Get callback request statistics
     */
    public function stats(Request $request): JsonResponse
    {
        $dateFrom = $request->get('date_from', now()->subDays(30));
        $dateTo = $request->get('date_to', now());
        
        $stats = [
            'total_requests' => CallbackRequest::whereBetween('created_at', [$dateFrom, $dateTo])->count(),
            'status_breakdown' => CallbackRequest::whereBetween('created_at', [$dateFrom, $dateTo])
                ->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->get(),
            'daily_requests' => CallbackRequest::whereBetween('created_at', [$dateFrom, $dateTo])
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date')
                ->get(),
            'top_companies' => CallbackRequest::with('company')
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->selectRaw('company_id, COUNT(*) as count')
                ->groupBy('company_id')
                ->orderByDesc('count')
                ->limit(5)
                ->get()
        ];
        
        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'Callback request statistics retrieved successfully'
        ]);
    }

    /**
     * Perform bulk actions on callback requests
     */
    public function bulkAction(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'action' => 'required|in:update_status,delete',
            'ids' => 'required|array',
            'ids.*' => 'exists:callback_requests,id',
            'status' => 'required_if:action,update_status|in:pending,scheduled,completed,cancelled'
        ]);

        $callbacks = CallbackRequest::whereIn('id', $validated['ids']);

        if ($validated['action'] === 'update_status') {
            $callbacks->update(['status' => $validated['status']]);
            $message = "Status updated for {$callbacks->count()} callback requests";
        } else {
            $count = $callbacks->count();
            $callbacks->delete();
            $message = "{$count} callback requests deleted";
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }
}