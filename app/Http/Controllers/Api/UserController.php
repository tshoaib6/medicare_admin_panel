<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request): JsonResponse
    {
        $search = $request->get('search');
        $admin = $request->get('admin');
        $guest = $request->get('guest');
        $perPage = $request->get('per_page', 20);
        
        $users = User::withCount(['callbackRequests', 'activityLogs'])
            ->when($search, function ($query, $search) {
                $query->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%");
            })
            ->when($admin !== null, function ($query) use ($admin) {
                $query->where('is_admin', $admin === 'true');
            })
            ->when($guest !== null, function ($query) use ($guest) {
                $query->where('is_guest', $guest === 'true');
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $users,
            'message' => 'Users retrieved successfully'
        ]);
    }

    /**
     * Display the specified user
     */
    public function show(User $user): JsonResponse
    {
        $user->load(['callbackRequests', 'activityLogs' => function($query) {
            $query->orderBy('created_at', 'desc')->limit(10);
        }]);
        
        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'User retrieved successfully'
        ]);
    }

    /**
     * Toggle admin status for user
     */
    public function toggleAdmin(User $user): JsonResponse
    {
        $user->update(['is_admin' => !$user->is_admin]);
        
        return response()->json([
            'success' => true,
            'data' => $user->fresh(),
            'message' => $user->is_admin ? 'User granted admin privileges' : 'User admin privileges revoked'
        ]);
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user): JsonResponse
    {
        // Prevent deletion of admin users
        if ($user->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete admin users'
            ], 422);
        }

        // Check if user has associated data
        if ($user->callbackRequests()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete user with callback requests. Archive the user instead.'
            ], 422);
        }

        $user->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }
}