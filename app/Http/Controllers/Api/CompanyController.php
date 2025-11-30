<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CompanyController extends Controller
{
    /**
     * Display a listing of companies
     */
    public function index(Request $request): JsonResponse
    {
        $search = $request->get('search');
        $perPage = $request->get('per_page', 15);
        
        $companies = Company::withCount(['plans', 'callbackRequests'])
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $companies,
            'message' => 'Companies retrieved successfully'
        ]);
    }

    /**
     * Store a newly created company
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image_url' => 'nullable|url',
            'rating' => 'nullable|numeric|between:0,5',
            'phone' => 'required|string',
            'specialties' => 'required|array',
            'specialties.*' => 'string'
        ]);
        
        $company = Company::create($validated);
        
        return response()->json([
            'success' => true,
            'data' => $company,
            'message' => 'Company created successfully'
        ], 201);
    }

    /**
     * Display the specified company
     */
    public function show(Company $company): JsonResponse
    {
        $company->load(['plans', 'callbackRequests']);
        
        return response()->json([
            'success' => true,
            'data' => $company,
            'message' => 'Company retrieved successfully'
        ]);
    }

    /**
     * Update the specified company
     */
    public function update(Request $request, Company $company): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image_url' => 'nullable|url',
            'rating' => 'nullable|numeric|between:0,5',
            'phone' => 'required|string',
            'specialties' => 'required|array',
            'specialties.*' => 'string'
        ]);
        
        $company->update($validated);
        
        return response()->json([
            'success' => true,
            'data' => $company->fresh(),
            'message' => 'Company updated successfully'
        ]);
    }

    /**
     * Remove the specified company
     */
    public function destroy(Company $company): JsonResponse
    {
        // Check if company has associated plans or callback requests
        if ($company->plans()->count() > 0 || $company->callbackRequests()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete company that has associated plans or callback requests'
            ], 422);
        }
        
        $company->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Company deleted successfully'
        ]);
    }

    /**
     * Get company statistics
     */
    public function stats(Request $request): JsonResponse
    {
        $stats = [
            'total_companies' => Company::count(),
            'companies_with_plans' => Company::has('plans')->count(),
            'average_rating' => Company::avg('rating'),
            'top_rated_companies' => Company::orderByDesc('rating')->limit(5)->get(),
            'specialties_distribution' => Company::selectRaw('JSON_UNQUOTE(JSON_EXTRACT(specialties, "$[*]")) as specialty')
                ->get()
                ->flatMap(function ($company) {
                    return $company->specialties ?? [];
                })
                ->countBy()
                ->take(10),
            'companies_by_callback_requests' => Company::withCount('callbackRequests')
                ->orderByDesc('callback_requests_count')
                ->limit(5)
                ->get()
        ];
        
        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'Company statistics retrieved successfully'
        ]);
    }
}