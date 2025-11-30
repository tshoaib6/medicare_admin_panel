@extends('layouts.admin')

@section('title', 'User Profile')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">User Profile</h1>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Users
    </a>
</div>

<!-- User Profile Content -->
<div class="row">
    <!-- Profile Card -->
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white text-center">
                <div class="avatar mb-3">
                    <i class="fas fa-user-circle fa-4x"></i>
                </div>
                <h4 class="mb-1">{{ $user->first_name }} {{ $user->last_name }}</h4>
                <p class="mb-0 opacity-75">{{ $user->email }}</p>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-right">
                            <h5 class="font-weight-bold mb-1">{{ $user->created_at->format('M Y') }}</h5>
                            <small class="text-muted text-uppercase">Member Since</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h5 class="font-weight-bold mb-1">
                            @if($user->is_admin)
                                <span class="badge badge-danger">Admin</span>
                            @else
                                <span class="badge badge-secondary">User</span>
                            @endif
                        </h5>
                        <small class="text-muted text-uppercase">Role</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Details Cards -->
    <div class="col-lg-8">
        <!-- Personal Information -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-user mr-2"></i>Personal Information
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold text-muted">First Name</label>
                        <p class="mb-0">{{ $user->first_name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold text-muted">Last Name</label>
                        <p class="mb-0">{{ $user->last_name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold text-muted">Email Address</label>
                        <p class="mb-0">{{ $user->email }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold text-muted">Phone Number</label>
                        <p class="mb-0">{{ $user->phone_number ?? 'Not provided' }}</p>
                    </div>
                    @if($user->year_of_birth)
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold text-muted">Year of Birth</label>
                        <p class="mb-0">{{ $user->year_of_birth }} (Age: {{ date('Y') - $user->year_of_birth }})</p>
                    </div>
                    @endif
                    @if($user->gender)
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold text-muted">Gender</label>
                        <p class="mb-0">{{ ucfirst($user->gender) }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Account Information -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-shield-alt mr-2"></i>Account Information
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold text-muted">Auth Provider</label>
                        <p class="mb-0">
                            @if($user->auth_provider === 'google')
                                <span class="badge badge-info">
                                    <i class="fab fa-google mr-1"></i>Google
                                </span>
                            @else
                                <span class="badge badge-warning">
                                    <i class="fas fa-envelope mr-1"></i>Email
                                </span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold text-muted">Email Verification</label>
                        <p class="mb-0">
                            @if($user->email_verified_at)
                                <span class="badge badge-success">
                                    <i class="fas fa-check mr-1"></i>Verified
                                </span>
                                <br><small class="text-muted">{{ $user->email_verified_at->format('M d, Y H:i') }}</small>
                            @else
                                <span class="badge badge-danger">
                                    <i class="fas fa-times mr-1"></i>Not Verified
                                </span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold text-muted">User Type</label>
                        <p class="mb-0">
                            @if($user->is_admin)
                                <span class="badge badge-danger">
                                    <i class="fas fa-crown mr-1"></i>Administrator
                                </span>
                            @else
                                <span class="badge badge-secondary">
                                    <i class="fas fa-user mr-1"></i>Regular User
                                </span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Timeline -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-clock mr-2"></i>Account Timeline
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold text-muted">Registration Date</label>
                        <p class="mb-0">{{ $user->created_at->format('M d, Y') }}</p>
                        <small class="text-muted">{{ $user->created_at->format('H:i:s') }} ({{ $user->created_at->diffForHumans() }})</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold text-muted">Last Profile Update</label>
                        <p class="mb-0">{{ $user->updated_at->format('M d, Y') }}</p>
                        <small class="text-muted">{{ $user->updated_at->format('H:i:s') }} ({{ $user->updated_at->diffForHumans() }})</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card-header.bg-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}
</style>
@endsection
