@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard Overview</h1>
</div>

<style>
        .stat-card {
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15) !important;
        }
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
        }
        .bg-primary-gradient { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .bg-secondary-gradient { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .bg-info-gradient { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .bg-success-gradient { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
        .bg-warning-gradient { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
        .bg-danger-gradient { background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%); }
    </style>

<!-- Dashboard Statistics -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2 stat-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Users</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUsers }}</div>
                        <div class="text-xs text-success">+12% from last month</div>
                    </div>
                    <div class="col-auto">
                        <div class="stat-icon bg-primary-gradient">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2 stat-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Companies</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalCompanies }}</div>
                        <div class="text-xs text-success">+3 this month</div>
                    </div>
                    <div class="col-auto">
                        <div class="stat-icon bg-success-gradient">
                            <i class="fas fa-building"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2 stat-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Plans</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPlans }}</div>
                        <div class="text-xs text-info">Active plans</div>
                    </div>
                    <div class="col-auto">
                        <div class="stat-icon bg-info-gradient">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2 stat-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Callback Requests</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalCallbacks }}</div>
                        <div class="text-xs text-success">+15% from last month</div>
                    </div>
                    <div class="col-auto">
                        <div class="stat-icon bg-warning-gradient">
                            <i class="fas fa-phone"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2 stat-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Ads & Promotions</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAds }}</div>
                        <div class="text-xs text-info">Active campaigns</div>
                    </div>
                    <div class="col-auto">
                        <div class="stat-icon bg-danger-gradient">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-secondary shadow h-100 py-2 stat-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Questionnaires</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalQuestionnaires }}</div>
                        <div class="text-xs text-info">Available forms</div>
                    </div>
                    <div class="col-auto">
                        <div class="stat-icon bg-secondary-gradient">
                            <i class="fas fa-question-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Recent Activity Section -->
<div class="row">
    <!-- Recent Users -->
    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-users mr-2"></i>Recent Users
                </h6>
            </div>
            <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                @foreach($recentUsers as $user)
                <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                    <div>
                        <div class="font-weight-bold">{{ $user->first_name }} {{ $user->last_name }}</div>
                        <small class="text-muted">{{ $user->email }}</small>
                    </div>
                    <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Latest Callbacks -->
    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-phone mr-2"></i>Latest Callbacks
                </h6>
            </div>
            <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                @forelse($latestCallbacks as $callback)
                <div class="border-bottom py-2">
                    <div class="font-weight-bold">{{ $callback->user->first_name }} {{ $callback->user->last_name }}</div>
                    <small class="text-muted d-block">{{ $callback->company->name }}</small>
                    <div class="d-flex justify-content-between align-items-center mt-1">
                        <small class="text-muted">{{ $callback->call_date->format('M d, Y') }} at {{ $callback->call_time }}</small>
                        <span class="badge badge-info badge-sm">{{ ucfirst($callback->status) }}</span>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-muted">No callback requests yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
