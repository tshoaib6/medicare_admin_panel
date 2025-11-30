@extends('layouts.admin')

@section('title', 'Users Management')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Users Management</h1>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-filter"></i> Filter Users
        </h6>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.users.index') }}">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" class="form-control" id="search" name="search" placeholder="Name, email, phone..." value="{{ $search ?? '' }}">
                </div>
                <div class="col-md-2 mb-3">
                    <label for="verified" class="form-label">Email Status</label>
                    <select class="form-control" id="verified" name="verified">
                        <option value="">All Users</option>
                        <option value="yes" {{ ($verified ?? '') == 'yes' ? 'selected' : '' }}>Verified</option>
                        <option value="no" {{ ($verified ?? '') == 'no' ? 'selected' : '' }}>Not Verified</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="auth_provider" class="form-label">Auth Provider</label>
                    <select class="form-control" id="auth_provider" name="auth_provider">
                        <option value="">All Providers</option>
                        <option value="email" {{ ($authProvider ?? '') == 'email' ? 'selected' : '' }}>Email</option>
                        <option value="google" {{ ($authProvider ?? '') == 'google' ? 'selected' : '' }}>Google</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="is_admin" class="form-label">User Type</label>
                    <select class="form-control" id="is_admin" name="is_admin">
                        <option value="">All Types</option>
                        <option value="yes" {{ ($isAdmin ?? '') == 'yes' ? 'selected' : '' }}>Admin</option>
                        <option value="no" {{ ($isAdmin ?? '') == 'no' ? 'selected' : '' }}>Regular User</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="has_medicare" class="form-label">Medicare Part B</label>
                    <select class="form-control" id="has_medicare" name="has_medicare">
                        <option value="">All Users</option>
                        <option value="yes" {{ ($hasMedicare ?? '') == 'yes' ? 'selected' : '' }}>Has Medicare Part B</option>
                        <option value="no" {{ ($hasMedicare ?? '') == 'no' ? 'selected' : '' }}>No Medicare Part B</option>
                    </select>
                </div>
                <div class="col-md-1 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search"></i> Filter
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-times"></i> Clear All
                    </a>
                </div>
            </div>
        </form>
        
        @if($search || $verified || $authProvider || $isAdmin || $hasMedicare)
            <div class="mt-3">
                <strong>Active Filters:</strong>
                @if($search)
                    <span class="badge badge-primary ml-1">
                        Search: "{{ $search }}"
                        <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="text-white ml-1">×</a>
                    </span>
                @endif
                @if($verified)
                    <span class="badge badge-info ml-1">
                        Verified: {{ $verified == 'yes' ? 'Yes' : 'No' }}
                        <a href="{{ request()->fullUrlWithQuery(['verified' => null]) }}" class="text-white ml-1">×</a>
                    </span>
                @endif
                @if($authProvider)
                    <span class="badge badge-success ml-1">
                        Provider: {{ ucfirst($authProvider) }}
                        <a href="{{ request()->fullUrlWithQuery(['auth_provider' => null]) }}" class="text-white ml-1">×</a>
                    </span>
                @endif
                @if($isAdmin)
                    <span class="badge badge-warning ml-1">
                        Type: {{ $isAdmin == 'yes' ? 'Admin' : 'User' }}
                        <a href="{{ request()->fullUrlWithQuery(['is_admin' => null]) }}" class="text-white ml-1">×</a>
                    </span>
                @endif
                @if($hasMedicare)
                    <span class="badge badge-secondary ml-1">
                        Medicare: {{ $hasMedicare == 'yes' ? 'Yes' : 'No' }}
                        <a href="{{ request()->fullUrlWithQuery(['has_medicare' => null]) }}" class="text-white ml-1">×</a>
                    </span>
                @endif
            </div>
        @endif
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Users List</h6>
    </div>
    <div class="card-body">
        @if($users->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag"></i> ID</th>
                            <th><i class="fas fa-user"></i> Name</th>
                            <th><i class="fas fa-envelope"></i> Email</th>
                            <th><i class="fas fa-phone"></i> Phone</th>
                            <th><i class="fas fa-shield-alt"></i> Provider</th>
                            <th><i class="fas fa-check-circle"></i> Verified</th>
                            <th><i class="fas fa-user-shield"></i> Type</th>
                            <th><i class="fas fa-calendar"></i> Registered</th>
                            <th><i class="fas fa-cog"></i> Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td><strong>#{{ $user->id }}</strong></td>
                            <td>
                                <div class="font-weight-bold">{{ $user->first_name }} {{ $user->last_name }}</div>
                                @if($user->year_of_birth)
                                    <small class="text-muted">Age: {{ date('Y') - $user->year_of_birth }}</small>
                                @endif
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone_number }}</td>
                            <td>
                                @if($user->auth_provider === 'google')
                                    <span class="badge badge-info">
                                        <i class="fab fa-google"></i> Google
                                    </span>
                                @else
                                    <span class="badge badge-warning">
                                        <i class="fas fa-envelope"></i> Email
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($user->email_verified_at)
                                    <span class="badge badge-success">
                                        <i class="fas fa-check"></i> Verified
                                    </span>
                                @else
                                    <span class="badge badge-danger">
                                        <i class="fas fa-times"></i> Not Verified
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($user->is_admin)
                                    <span class="badge badge-danger">
                                        <i class="fas fa-crown"></i> Admin
                                    </span>
                                @else
                                    <span class="badge badge-secondary">
                                        <i class="fas fa-user"></i> User
                                    </span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $users->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-4">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h5>No Users Found</h5>
                <p class="text-muted">Try adjusting your search criteria or filters.</p>
            </div>
        @endif
    </div>
</div>
@endsection
