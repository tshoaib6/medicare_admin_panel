<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $companies = Company::withCount('plans')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.companies.index', compact('companies', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.companies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'unique:companies'],
            'description' => ['required', 'string'],
            'image_url' => ['nullable', 'url'],
            'rating' => ['required', 'numeric', 'min:0', 'max:5'],
            'phone' => ['required', 'string', 'max:20'],
            'specialties' => ['required', 'array', 'min:1'],
            'specialties.*' => ['required', 'string', 'max:100'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Company::create($request->all());

        return redirect()->route('admin.companies.index')
            ->with('success', 'Company created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        $company->load('plans');
        return view('admin.companies.show', compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        $company->load('plans');
        return view('admin.companies.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'unique:companies,name,' . $company->id],
            'description' => ['required', 'string'],
            'image_url' => ['nullable', 'url'],
            'rating' => ['required', 'numeric', 'min:0', 'max:5'],
            'phone' => ['required', 'string', 'max:20'],
            'specialties' => ['required', 'array', 'min:1'],
            'specialties.*' => ['required', 'string', 'max:100'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $company->update($request->all());

        return redirect()->route('admin.companies.index')
            ->with('success', 'Company updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        $company->delete();

        return redirect()->route('admin.companies.index')
            ->with('success', 'Company deleted successfully.');
    }
}
