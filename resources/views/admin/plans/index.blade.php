@extends('layouts.admin')

@section('title', 'Plans Management')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Plans Management</h1>
    <a href="{{ route('admin.plans.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> Add New Plan
    </a>
</div>

<!-- Content Row -->
<div class="row">
    <!-- Plans Overview Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Plans</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $plans->total() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Available Plans</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $plans->where('is_available', true)->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            With Benefits</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $plans->whereNotNull('benefits')->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-heart fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            With Questionnaires</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $plans->filter(function($p) { return $p->questionnaires && $p->questionnaires->count() > 0; })->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-question-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter Section -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Search & Filters</h6>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.plans.index') }}" class="row">
            <div class="col-md-4 mb-2">
                <input type="text" name="search" class="form-control" placeholder="Search plans..." 
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-3 mb-2">
                <select name="available" class="form-control">
                    <option value="">All Plans</option>
                    <option value="1" {{ request('available') == '1' ? 'selected' : '' }}>Available</option>
                    <option value="0" {{ request('available') == '0' ? 'selected' : '' }}>Unavailable</option>
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <select name="has_benefits" class="form-control">
                    <option value="">All Plans</option>
                    <option value="1" {{ request('has_benefits') == '1' ? 'selected' : '' }}>With Benefits</option>
                    <option value="0" {{ request('has_benefits') == '0' ? 'selected' : '' }}>No Benefits</option>
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <div class="btn-group w-100">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-search"></i> Search
                    </button>
                    <a href="{{ route('admin.plans.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-times"></i> Clear
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Plans Table -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Plans List</h6>
    </div>
    <div class="card-body">
        @if($plans->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Slug</th>
                            <th>Color</th>
                            <th>Available (toggle)</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($plans as $plan)
                        <tr>
                            <td>{{ $plan->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($plan->icon)
                                        <i class="{{ $plan->icon }} fa-lg mr-2" style="color: {{ $plan->color ?? '#007bff' }};"></i>
                                    @endif
                                    <div>
                                        <div class="font-weight-bold">{{ $plan->title }}</div>
                                        @if($plan->description)
                                            <small class="text-muted">{{ Str::limit($plan->description, 50) }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <code>{{ $plan->slug }}</code>
                            </td>
                            <td>
                                @if($plan->color)
                                    <div class="d-flex align-items-center">
                                        <div class="color-preview" style="width: 20px; height: 20px; background-color: {{ $plan->color }}; border: 1px solid #ccc; border-radius: 3px; margin-right: 8px;"></div>
                                        <span>{{ $plan->color }}</span>
                                    </div>
                                @else
                                    <span class="text-muted">No color</span>
                                @endif
                            </td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input plan-availability-toggle" 
                                           id="toggle-{{ $plan->id }}" 
                                           data-plan-id="{{ $plan->id }}"
                                           {{ $plan->is_available ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="toggle-{{ $plan->id }}">
                                        <span class="badge badge-{{ $plan->is_available ? 'success' : 'danger' }}">
                                            {{ $plan->is_available ? 'Available' : 'Unavailable' }}
                                        </span>
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.plans.show', $plan) }}" 
                                       class="btn btn-info btn-sm" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.plans.edit', $plan) }}" 
                                       class="btn btn-warning btn-sm" title="Edit Plan">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.plans.destroy', $plan) }}" method="POST" 
                                          style="display: inline;" 
                                          onsubmit="return confirm('Are you sure you want to delete this plan?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Delete Plan">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $plans->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-clipboard-list fa-3x text-gray-400 mb-3"></i>
                <h5 class="text-gray-600">No plans found</h5>
                <p class="text-gray-500">Get started by creating your first insurance plan.</p>
                <a href="{{ route('admin.plans.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Plan
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Auto-submit form on filter change
    document.querySelectorAll('select[name="available"], select[name="has_benefits"]').forEach(function(select) {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });

    // Enhanced search with debouncing
    let searchTimeout;
    document.querySelector('input[name="search"]').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            this.form.submit();
        }, 500);
    });

    // Toggle availability via AJAX
    document.querySelectorAll('.plan-availability-toggle').forEach(function(toggle) {
        toggle.addEventListener('change', function() {
            const planId = this.dataset.planId;
            const isAvailable = this.checked;
            
            fetch(`/admin/plans/${planId}/toggle-availability`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ is_available: isAvailable })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update badge text and color
                    const badge = this.parentElement.querySelector('.badge');
                    badge.textContent = isAvailable ? 'Available' : 'Unavailable';
                    badge.className = `badge badge-${isAvailable ? 'success' : 'danger'}`;
                } else {
                    // Revert toggle if failed
                    this.checked = !isAvailable;
                    alert('Failed to update plan availability');
                }
            })
            .catch(error => {
                // Revert toggle if failed
                this.checked = !isAvailable;
                alert('Error updating plan availability');
            });
        });
    });
</script>
@endsection