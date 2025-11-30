@extends('layouts.admin')

@section('title', 'Company Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Company Details</h1>
    <div>
        <a href="{{ route('admin.companies.edit', $company) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Edit Company
        </a>
        <a href="{{ route('admin.companies.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Companies
        </a>
    </div>
</div>

<div class="row">
    <!-- Company Profile Card -->
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white text-center">
                <div class="company-logo mb-3">
                    @if($company->logo)
                        <img src="{{ Storage::url($company->logo) }}" alt="{{ $company->name }} Logo" 
                             class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                    @else
                        <i class="fas fa-building fa-4x"></i>
                    @endif
                </div>
                <h4 class="mb-1">{{ $company->name }}</h4>
                <p class="mb-0">
                    <span class="badge badge-{{ $company->is_active ? 'success' : 'danger' }}">
                        {{ $company->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </p>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-right">
                            <h5 class="font-weight-bold mb-1">{{ $company->plans_count ?? ($company->plans ? $company->plans->count() : 0) }}</h5>
                            <small class="text-muted text-uppercase">Plans</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h5 class="font-weight-bold mb-1">{{ $company->created_at->format('M Y') }}</h5>
                        <small class="text-muted text-uppercase">Member Since</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Company Details -->
    <div class="col-lg-8">
        <!-- Basic Information -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-info-circle mr-2"></i>Basic Information
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold text-muted">Company Name</label>
                        <p class="mb-0">{{ $company->name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold text-muted">Email Address</label>
                        <p class="mb-0">
                            <a href="mailto:{{ $company->email }}">{{ $company->email }}</a>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold text-muted">Phone Number</label>
                        <p class="mb-0">
                            @if($company->phone)
                                <a href="tel:{{ $company->phone }}">{{ $company->phone }}</a>
                            @else
                                <span class="text-muted">Not provided</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold text-muted">Website</label>
                        <p class="mb-0">
                            @if($company->website)
                                <a href="{{ $company->website }}" target="_blank" rel="noopener">
                                    {{ $company->website }} <i class="fas fa-external-link-alt"></i>
                                </a>
                            @else
                                <span class="text-muted">Not provided</span>
                            @endif
                        </p>
                    </div>
                    @if($company->address)
                    <div class="col-12 mb-3">
                        <label class="font-weight-bold text-muted">Address</label>
                        <p class="mb-0">{{ $company->address }}</p>
                    </div>
                    @endif
                    @if($company->description)
                    <div class="col-12 mb-3">
                        <label class="font-weight-bold text-muted">Description</label>
                        <p class="mb-0">{{ $company->description }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Plans Information -->
        @if($company->plans && $company->plans->count() > 0)
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-clipboard-list mr-2"></i>Insurance Plans ({{ $company->plans ? $company->plans->count() : 0 }})
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Plan Name</th>
                                <th>Type</th>
                                <th>Monthly Premium</th>
                                <th>Deductible</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($company->plans as $plan)
                            <tr>
                                <td>{{ $plan->name }}</td>
                                <td><span class="badge badge-info">{{ $plan->type }}</span></td>
                                <td>${{ number_format($plan->monthly_premium, 2) }}</td>
                                <td>${{ number_format($plan->deductible, 2) }}</td>
                                <td>
                                    <span class="badge badge-{{ $plan->is_active ? 'success' : 'danger' }}">
                                        {{ $plan->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.plans.show', $plan) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @else
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-clipboard-list mr-2"></i>Insurance Plans
                </h6>
            </div>
            <div class="card-body text-center py-4">
                <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                <h5>No Plans Available</h5>
                <p class="text-muted">This company hasn't added any insurance plans yet.</p>
                <a href="{{ route('admin.plans.create', ['company_id' => $company->id]) }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add First Plan
                </a>
            </div>
        </div>
        @endif

        <!-- System Information -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-clock mr-2"></i>System Information
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold text-muted">Created Date</label>
                        <p class="mb-0">{{ $company->created_at->format('M d, Y') }}</p>
                        <small class="text-muted">{{ $company->created_at->format('H:i:s') }} ({{ $company->created_at->diffForHumans() }})</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold text-muted">Last Updated</label>
                        <p class="mb-0">{{ $company->updated_at->format('M d, Y') }}</p>
                        <small class="text-muted">{{ $company->updated_at->format('H:i:s') }} ({{ $company->updated_at->diffForHumans() }})</small>
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