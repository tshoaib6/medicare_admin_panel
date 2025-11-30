<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdController extends Controller
{
    /**
     * Display a listing of all ads and promotions
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $type = $request->get('type');
        $status = $request->get('status');
        
        $ads = Ad::query()
            ->when($search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('target_audience', 'like', "%{$search}%");
            })
            ->when($type, function ($query, $type) {
                $query->where('type', $type);
            })
            ->when($status, function ($query, $status) {
                $query->where('is_active', $status === 'active');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('admin.ads.index', compact('ads', 'search', 'type', 'status'));
    }

    /**
     * Show the form for creating a new ad
     */
    public function create()
    {
        return view('admin.ads.create');
    }

    /**
     * Store a newly created ad in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:banner,popup,inline,sidebar',
            'content_html' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'target_url' => 'nullable|url',
            'target_audience' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean'
        ]);
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $validated['image_url'] = $file->storeAs('ads', $filename, 'public');
        }
        
        $validated['is_active'] = $request->has('is_active');
        $validated['click_count'] = 0;
        $validated['impression_count'] = 0;
        
        Ad::create($validated);
        
        return redirect()->route('admin.ads.index')
            ->with('success', 'Ad created successfully.');
    }

    /**
     * Display the specified ad
     */
    public function show(Ad $ad)
    {
        return view('admin.ads.show', compact('ad'));
    }

    /**
     * Show the form for editing the specified ad
     */
    public function edit(Ad $ad)
    {
        return view('admin.ads.edit', compact('ad'));
    }

    /**
     * Update the specified ad in storage
     */
    public function update(Request $request, Ad $ad)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:banner,popup,inline,sidebar',
            'content_html' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'target_url' => 'nullable|url',
            'target_audience' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean'
        ]);
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($ad->image_url && Storage::disk('public')->exists($ad->image_url)) {
                Storage::disk('public')->delete($ad->image_url);
            }
            
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $validated['image_url'] = $file->storeAs('ads', $filename, 'public');
        }
        
        $validated['is_active'] = $request->has('is_active');
        
        $ad->update($validated);
        
        return redirect()->route('admin.ads.index')
            ->with('success', 'Ad updated successfully.');
    }

    /**
     * Remove the specified ad from storage
     */
    public function destroy(Ad $ad)
    {
        // Delete associated image
        if ($ad->image_url && Storage::disk('public')->exists($ad->image_url)) {
            Storage::disk('public')->delete($ad->image_url);
        }
        
        $ad->delete();
        
        return redirect()->route('admin.ads.index')
            ->with('success', 'Ad deleted successfully.');
    }

    /**
     * Toggle active status
     */
    public function toggleStatus(Ad $ad)
    {
        $ad->update(['is_active' => !$ad->is_active]);
        
        $status = $ad->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "Advertisement {$status} successfully!");
    }

    /**
     * Bulk operations
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'ads' => 'required|array|min:1',
            'ads.*' => 'exists:ads,id'
        ]);

        $ads = Ad::whereIn('id', $request->ads);

        switch ($request->action) {
            case 'activate':
                $ads->update(['is_active' => true]);
                return redirect()->back()->with('success', 'Selected ads activated successfully!');
                
            case 'deactivate':
                $ads->update(['is_active' => false]);
                return redirect()->back()->with('success', 'Selected ads deactivated successfully!');
                
            case 'delete':
                // Delete associated images
                foreach ($ads->get() as $ad) {
                    if ($ad->image_url && Storage::disk('public')->exists($ad->image_url)) {
                        Storage::disk('public')->delete($ad->image_url);
                    }
                }
                $ads->delete();
                return redirect()->back()->with('success', 'Selected ads deleted successfully!');
        }
    }
}
