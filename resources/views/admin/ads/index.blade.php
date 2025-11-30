@extends('layouts.admin')

@section('title', 'Ads & Promotions Management')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-bullhorn text-primary mr-2"></i>
            Ads & Promotions Management
        </h1>
        <a href="{{ route('admin.ads.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Create New Ad
        </a>
    </div>

    <!-- Statistics Cards Row -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Ads</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $ads->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bullhorn fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active Ads</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $ads->where('is_active', true)->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-play-circle fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Clicks</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($ads->sum('click_count')) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-mouse-pointer fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Impressions</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($ads->sum('impression_count')) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-eye fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter mr-1"></i>
                Filter & Search
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.ads.index') }}" class="row">
                <div class="col-md-3 mb-3">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" class="form-control" name="search" id="search" 
                           placeholder="Search title, description..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2 mb-3">
                    <label for="type" class="form-label">Type</label>
                    <select name="type" id="type" class="form-control">
                        <option value="">All Types</option>
                        <option value="banner" {{ request('type') == 'banner' ? 'selected' : '' }}>Banner</option>
                        <option value="popup" {{ request('type') == 'popup' ? 'selected' : '' }}>Popup</option>
                        <option value="inline" {{ request('type') == 'inline' ? 'selected' : '' }}>Inline</option>
                        <option value="sidebar" {{ request('type') == 'sidebar' ? 'selected' : '' }}>Sidebar</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex">
                        <button type="submit" class="btn btn-primary mr-2">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <a href="{{ route('admin.ads.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Ads Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list mr-1"></i>
                Advertisements List
            </h6>
            @if($ads->count() > 0)
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="bulkActions" data-toggle="dropdown">
                        <i class="fas fa-cogs"></i> Bulk Actions
                    </button>
                    <div class="dropdown-menu" aria-labelledby="bulkActions">
                        <button class="dropdown-item" onclick="bulkAction('activate')">
                            <i class="fas fa-play text-success mr-2"></i>Activate Selected
                        </button>
                        <button class="dropdown-item" onclick="bulkAction('deactivate')">
                            <i class="fas fa-pause text-warning mr-2"></i>Deactivate Selected
                        </button>
                        <div class="dropdown-divider"></div>
                        <button class="dropdown-item text-danger" onclick="bulkAction('delete')">
                            <i class="fas fa-trash mr-2"></i>Delete Selected
                        </button>
                    </div>
                </div>
            @endif
        </div>
        <div class="card-body">
            @if($ads->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th width="30">
                                    <input type="checkbox" id="selectAll" class="form-check-input">
                                </th>
                                <th width="80">Image</th>
                                <th>Title</th>
                                <th width="100">Type</th>
                                <th width="120">Status</th>
                                <th width="100">Clicks</th>
                                <th width="100">Impressions</th>
                                <th width="80">CTR %</th>
                                <th width="110">Duration</th>
                                <th width="160">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ads as $ad)
                            <tr>
                                <td>
                                    <input type="checkbox" name="selected_ads[]" value="{{ $ad->id }}" class="form-check-input ad-checkbox">
                                </td>
                                <td class="text-center">
                                    @if($ad->image_url)
                                        <img src="{{ Storage::url($ad->image_url) }}" alt="Ad Image" 
                                             class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                             style="width: 50px; height: 50px; border-radius: 4px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $ad->title }}</strong>
                                    @if($ad->description)
                                        <br><small class="text-muted">{{ Str::limit($ad->description, 50) }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-{{ $ad->type === 'banner' ? 'primary' : ($ad->type === 'popup' ? 'info' : ($ad->type === 'inline' ? 'success' : 'warning')) }} badge-pill">
                                        {{ ucfirst($ad->type) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $ad->status_color }} badge-pill">
                                        {{ $ad->status }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <strong>{{ number_format($ad->click_count) }}</strong>
                                </td>
                                <td class="text-center">
                                    <strong>{{ number_format($ad->impression_count) }}</strong>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-{{ $ad->click_through_rate > 5 ? 'success' : ($ad->click_through_rate > 2 ? 'warning' : 'danger') }}">
                                        {{ number_format($ad->click_through_rate, 2) }}%
                                    </span>
                                </td>
                                <td class="small">
                                    @if($ad->start_date)
                                        <strong>Start:</strong> {{ $ad->start_date->format('M j, Y') }}<br>
                                    @endif
                                    @if($ad->end_date)
                                        <strong>End:</strong> {{ $ad->end_date->format('M j, Y') }}
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.ads.show', $ad) }}" class="btn btn-info btn-sm" 
                                           title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.ads.edit', $ad) }}" class="btn btn-warning btn-sm"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.ads.toggleStatus', $ad) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-{{ $ad->is_active ? 'secondary' : 'success' }} btn-sm"
                                                    title="{{ $ad->is_active ? 'Deactivate' : 'Activate' }}">
                                                <i class="fas fa-{{ $ad->is_active ? 'pause' : 'play' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.ads.destroy', $ad) }}" method="POST" 
                                              class="d-inline" onsubmit="return confirm('Are you sure you want to delete this ad?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Delete">
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
                <div class="mt-3">
                    {{ $ads->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-bullhorn fa-3x text-gray-300"></i>
                    </div>
                    <h5 class="text-gray-600">No advertisements found</h5>
                    <p class="text-muted">
                        @if(request()->hasAny(['search', 'type', 'status']))
                            No ads match your current filters. <a href="{{ route('admin.ads.index') }}">Clear filters</a> to see all ads.
                        @else
                            Start promoting your Medicare services by creating your first advertisement.
                        @endif
                    </p>
                    @if(!request()->hasAny(['search', 'type', 'status']))
                        <a href="{{ route('admin.ads.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus mr-2"></i>Create First Ad
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Bulk Action Form -->
<form id="bulkActionForm" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="action" id="bulkActionType">
    <div id="bulkActionIds"></div>
</form>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Select All functionality
    $('#selectAll').change(function() {
        $('.ad-checkbox').prop('checked', $(this).is(':checked'));
    });
    
    // Update Select All when individual checkboxes change
    $('.ad-checkbox').change(function() {
        var total = $('.ad-checkbox').length;
        var checked = $('.ad-checkbox:checked').length;
        
        if (checked === 0) {
            $('#selectAll').prop('indeterminate', false).prop('checked', false);
        } else if (checked === total) {
            $('#selectAll').prop('indeterminate', false).prop('checked', true);
        } else {
            $('#selectAll').prop('indeterminate', true);
        }
    });
});

function bulkAction(action) {
    var checkedBoxes = $('.ad-checkbox:checked');
    
    if (checkedBoxes.length === 0) {
        alert('Please select at least one advertisement.');
        return;
    }
    
    var actionText = {
        'activate': 'activate',
        'deactivate': 'deactivate',
        'delete': 'delete'
    };
    
    if (action === 'delete') {
        if (!confirm(`Are you sure you want to ${actionText[action]} ${checkedBoxes.length} selected ad(s)? This action cannot be undone.`)) {
            return;
        }
    } else {
        if (!confirm(`Are you sure you want to ${actionText[action]} ${checkedBoxes.length} selected ad(s)?`)) {
            return;
        }
    }
    
    $('#bulkActionType').val(action);
    $('#bulkActionIds').empty();
    
    checkedBoxes.each(function() {
        $('#bulkActionIds').append('<input type="hidden" name="ads[]" value="' + $(this).val() + '">');
    });
    
    $('#bulkActionForm').attr('action', '{{ route("admin.ads.bulk") }}').submit();
}

// Auto-refresh page every 5 minutes for updated analytics
setTimeout(function() {
    location.reload();
}, 300000);
</script>
@endpush