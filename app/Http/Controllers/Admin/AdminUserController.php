<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    /**
     * Display admin dashboard with statistics
     * GET /admin/dashboard
     */
    public function dashboard()
    {
        $totalUsers = User::count();
        $verifiedUsers = User::whereNotNull('email_verified_at')->count();
        $totalAdmins = User::where('is_admin', true)->count();
        $googleUsers = User::where('auth_provider', 'google')->count();
        $emailUsers = User::where('auth_provider', 'email')->count();
        
        // Phase 2 statistics
        $totalCompanies = \App\Models\Company::count();
        $totalPlans = \App\Models\Plan::count();
        $totalCallbacks = \App\Models\CallbackRequest::count();
        $totalAds = \App\Models\Ad::count();
        $totalQuestionnaires = \App\Models\Questionnaire::count();
        
        // Recent data
        $recentUsers = User::latest()->limit(5)->get();
        $latestCallbacks = \App\Models\CallbackRequest::with(['user', 'company'])->latest()->limit(5)->get();
        $recentActivities = \App\Models\ActivityLog::with('user')->latest()->limit(10)->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'verifiedUsers',
            'totalAdmins',
            'googleUsers',
            'emailUsers',
            'totalCompanies',
            'totalPlans',
            'totalCallbacks',
            'totalAds',
            'totalQuestionnaires',
            'recentUsers',
            'latestCallbacks',
            'recentActivities'
        ));
    }

    /**
     * Display list of all users
     * GET /admin/users
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $verified = $request->get('verified');
        $authProvider = $request->get('auth_provider');
        $isAdmin = $request->get('is_admin');
        $hasMedicare = $request->get('has_medicare');
        
        $users = User::query()
            ->when($search, function ($query, $search) {
                $query->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%");
            })
            ->when($verified !== null, function ($query) use ($verified) {
                if ($verified === 'yes') {
                    $query->whereNotNull('email_verified_at');
                } elseif ($verified === 'no') {
                    $query->whereNull('email_verified_at');
                }
            })
            ->when($authProvider, function ($query, $authProvider) {
                $query->where('auth_provider', $authProvider);
            })
            ->when($isAdmin !== null, function ($query) use ($isAdmin) {
                $query->where('is_admin', $isAdmin === 'yes');
            })
            ->when($hasMedicare !== null, function ($query) use ($hasMedicare) {
                $query->where('has_medicare_part_b', $hasMedicare === 'yes');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.users.index', compact('users', 'search', 'verified', 'authProvider', 'isAdmin', 'hasMedicare'));
    }

    /**
     * Display details of a specific user
     * GET /admin/users/{id}
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        
        return view('admin.users.show', compact('user'));
    }
}
