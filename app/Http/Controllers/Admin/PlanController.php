<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Company;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    /**
     * Display a listing of all plans
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $company = $request->get('company');
        $status = $request->get('status');
        
        $plans = Plan::with('company')
            ->when($search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            })
            ->when($company, function ($query, $company) {
                $query->where('company_id', $company);
            })
            ->when($status, function ($query, $status) {
                $query->where('is_active', $status === 'active');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        $companies = Company::orderBy('name')->get();
        
        return view('admin.plans.index', compact('plans', 'companies', 'search', 'company', 'status'));
    }

    /**
     * Show the form for creating a new plan
     */
    public function create()
    {
        $companies = Company::orderBy('name')->get();
        return view('admin.plans.create', compact('companies'));
    }

    /**
     * Store a newly created plan in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'slug' => 'required|string|unique:plans,slug',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'company_id' => 'required|exists:companies,id',
            'benefits' => 'nullable|array',
            'benefits.*' => 'string',
            'eligibility_criteria' => 'nullable|string',
            'coverage_details' => 'nullable|string',
            'pricing_info' => 'nullable|string',
            'enrollment_period' => 'nullable|string',
            'contact_info' => 'nullable|string',
            'is_active' => 'boolean'
        ]);
        
        // Filter out empty benefits
        if (isset($validated['benefits'])) {
            $validated['benefits'] = array_filter($validated['benefits']);
        }
        
        $validated['is_active'] = $request->has('is_active');
        
        Plan::create($validated);
        
        return redirect()->route('admin.plans.index')
            ->with('success', 'Plan created successfully.');
    }

    /**
     * Display the specified plan
     */
    public function show(Plan $plan)
    {
        $plan->load('company', 'questionnaires');
        return view('admin.plans.show', compact('plan'));
    }

    /**
     * Show the form for editing the specified plan
     */
    public function edit(Plan $plan)
    {
        $companies = Company::orderBy('name')->get();
        return view('admin.plans.edit', compact('plan', 'companies'));
    }

    /**
     * Update the specified plan in storage
     */
    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'slug' => 'required|string|unique:plans,slug,' . $plan->id,
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'company_id' => 'required|exists:companies,id',
            'benefits' => 'nullable|array',
            'benefits.*' => 'string',
            'eligibility_criteria' => 'nullable|string',
            'coverage_details' => 'nullable|string',
            'pricing_info' => 'nullable|string',
            'enrollment_period' => 'nullable|string',
            'contact_info' => 'nullable|string',
            'is_active' => 'boolean'
        ]);
        
        // Filter out empty benefits
        if (isset($validated['benefits'])) {
            $validated['benefits'] = array_filter($validated['benefits']);
        }
        
        $validated['is_active'] = $request->has('is_active');
        
        $plan->update($validated);
        
        return redirect()->route('admin.plans.index')
            ->with('success', 'Plan updated successfully.');
    }

    /**
     * Remove the specified plan from storage
     */
    public function destroy(Plan $plan)
    {
        // Check if plan has associated questionnaires
        if ($plan->questionnaires()->count() > 0) {
            return redirect()->route('admin.plans.index')
                ->with('error', 'Cannot delete plan that has associated questionnaires.');
        }
        
        $plan->delete();
        
        return redirect()->route('admin.plans.index')
            ->with('success', 'Plan deleted successfully.');
    }
}
