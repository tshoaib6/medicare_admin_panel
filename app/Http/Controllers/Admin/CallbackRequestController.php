<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CallbackRequest;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CallbackRequestController extends Controller
{

    /**
     * Display a listing of all callback requests
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $company = $request->get('company_id');
        $status = $request->get('status');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        
        $query = CallbackRequest::with(['user', 'company'])
            ->when($search, function ($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->whereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('first_name', 'like', "%{$search}%")
                              ->orWhere('last_name', 'like', "%{$search}%")
                              ->orWhere('email', 'like', "%{$search}%")
                              ->orWhere('phone_number', 'like', "%{$search}%");
                    })->orWhereHas('company', function ($companyQuery) use ($search) {
                        $companyQuery->where('name', 'like', "%{$search}%");
                    })->orWhere('notes', 'like', "%{$search}%");
                });
            })
            ->when($company, function ($query, $company) {
                $query->where('company_id', $company);
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($dateFrom, function ($query, $dateFrom) {
                $query->whereDate('call_date', '>=', $dateFrom);
            })
            ->when($dateTo, function ($query, $dateTo) {
                $query->whereDate('call_date', '<=', $dateTo);
            });
            
        $callbacks = $query->orderBy('call_date', 'desc')
            ->orderBy('call_time', 'desc')
            ->paginate(15);
            
        // Get statistics
        $stats = [
            'total' => CallbackRequest::count(),
            'pending' => CallbackRequest::where('status', 'pending')->count(),
            'scheduled' => CallbackRequest::where('status', 'scheduled')->count(),
            'completed' => CallbackRequest::where('status', 'completed')->count(),
            'cancelled' => CallbackRequest::where('status', 'cancelled')->count(),
            'today' => CallbackRequest::whereDate('call_date', Carbon::today())->count(),
        ];
            
        $companies = Company::orderBy('name')->get();
        
        return view('admin.callback-requests.index', compact(
            'callbacks', 
            'companies', 
            'search', 
            'company', 
            'status', 
            'dateFrom', 
            'dateTo',
            'stats'
        ));
    }

    /**
     * Show the form for creating a new callback request
     */
    public function create()
    {
        $companies = Company::orderBy('name')->get();
        $users = User::orderBy('first_name')->get();
        return view('admin.callback-requests.create', compact('companies', 'users'));
    }

    /**
     * Store a newly created callback request in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'company_id' => 'required|exists:companies,id',
            'call_date' => 'required|date|after_or_equal:today',
            'call_time' => 'required|string',
            'message' => 'nullable|string',
            'status' => 'required|in:pending,scheduled,completed,cancelled',
            'admin_notes' => 'nullable|string'
        ]);
        
        CallbackRequest::create($validated);
        
        return redirect()->route('admin.callback-requests.index')
            ->with('success', 'Callback request created successfully.');
    }

    /**
     * Display the specified callback request
     */
    public function show(CallbackRequest $callbackRequest)
    {
        $callbackRequest->load(['user', 'company']);
        return view('admin.callback-requests.show', compact('callbackRequest'));
    }

    /**
     * Show the form for editing the specified callback request
     */
    public function edit(CallbackRequest $callbackRequest)
    {
        $companies = Company::orderBy('name')->get();
        $users = User::orderBy('first_name')->get();
        return view('admin.callback-requests.edit', compact('callbackRequest', 'companies', 'users'));
    }

    /**
     * Update the specified callback request in storage
     */
    public function update(Request $request, CallbackRequest $callbackRequest)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'company_id' => 'required|exists:companies,id',
            'call_date' => 'required|date',
            'call_time' => 'required|date_format:H:i',
            'time_zone' => 'nullable|string|max:50',
            'status' => 'required|in:pending,scheduled,completed,cancelled',
            'notes' => 'nullable|string|max:1000'
        ]);
        
        $callbackRequest->update($validated);
        
        return redirect()->route('admin.callback-requests.show', $callbackRequest)
            ->with('success', 'Callback request updated successfully.');
    }
    
    /**
     * Quick update status only
     */
    public function updateStatus(Request $request, CallbackRequest $callbackRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,scheduled,completed,cancelled',
            'notes' => 'nullable|string|max:1000'
        ]);
        
        $callbackRequest->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'status' => $callbackRequest->status
        ]);
    }

    /**
     * Remove the specified callback request from storage
     */
    public function destroy(CallbackRequest $callbackRequest)
    {
        $callbackRequest->delete();
        
        return redirect()->route('admin.callback-requests.index')
            ->with('success', 'Callback request deleted successfully.');
    }
}
