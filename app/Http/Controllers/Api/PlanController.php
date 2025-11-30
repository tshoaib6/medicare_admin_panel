<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PlanController extends Controller
{
    /**
     * Display a listing of plans
     */
    public function index(Request $request): JsonResponse
    {
        $search = $request->get('search');
        $company = $request->get('company_id');
        $status = $request->get('status');
        $perPage = $request->get('per_page', 15);
        
        $plans = Plan::with('company')
            ->when($search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            })
            ->when($company, function ($query, $company) {
                $query->where('company_id', $company);
            })
            ->when($status !== null, function ($query) use ($status) {
                $query->where('is_available', $status === 'active');
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $plans,
            'message' => 'Plans retrieved successfully'
        ]);
    }

    /**
     * Store a newly created plan
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'slug' => 'required|string|unique:plans,slug',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'company_id' => 'required|exists:companies,id',
            'icon' => 'nullable|string',
            'color' => 'nullable|string',
            'benefits' => 'nullable|array',
            'benefits.*' => 'string',
            'is_available' => 'boolean'
        ]);
        
        // Filter out empty benefits
        if (isset($validated['benefits'])) {
            $validated['benefits'] = array_filter($validated['benefits']);
        }
        
        $plan = Plan::create($validated);
        $plan->load('company');
        
        return response()->json([
            'success' => true,
            'data' => $plan,
            'message' => 'Plan created successfully'
        ], 201);
    }

    /**
     * Display the specified plan
     */
    public function show(Plan $plan): JsonResponse
    {
        $plan->load(['company', 'questionnaires']);
        
        return response()->json([
            'success' => true,
            'data' => $plan,
            'message' => 'Plan retrieved successfully'
        ]);
    }

    /**
     * Update the specified plan
     */
    public function update(Request $request, Plan $plan): JsonResponse
    {
        $validated = $request->validate([
            'slug' => 'required|string|unique:plans,slug,' . $plan->id,
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'company_id' => 'required|exists:companies,id',
            'icon' => 'nullable|string',
            'color' => 'nullable|string',
            'benefits' => 'nullable|array',
            'benefits.*' => 'string',
            'is_available' => 'boolean'
        ]);
        
        // Filter out empty benefits
        if (isset($validated['benefits'])) {
            $validated['benefits'] = array_filter($validated['benefits']);
        }
        
        $plan->update($validated);
        $plan->load('company');
        
        return response()->json([
            'success' => true,
            'data' => $plan->fresh(['company']),
            'message' => 'Plan updated successfully'
        ]);
    }

    /**
     * Remove the specified plan
     */
    public function destroy(Plan $plan): JsonResponse
    {
        // Check if plan has associated questionnaires
        if ($plan->questionnaires()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete plan that has associated questionnaires'
            ], 422);
        }
        
        $plan->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Plan deleted successfully'
        ]);
    }

    /**
     * Toggle plan availability
     */
    public function toggleAvailability(Plan $plan): JsonResponse
    {
        $plan->update(['is_available' => !$plan->is_available]);
        
        return response()->json([
            'success' => true,
            'data' => $plan->fresh(),
            'message' => $plan->is_available ? 'Plan is now available' : 'Plan is now unavailable'
        ]);
    }
}