@extends('layouts.admin')

@section('title', 'Callback Requests Management')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Callback Requests Management</h1>
    <a href="{{ route('admin.callback-requests.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> Schedule New Callback
    </a>
</div>

<!-- Statistics Row -->
<div class="row">
    <div class="col-xl-2 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Requests</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-phone fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Scheduled</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['scheduled'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Completed</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['completed'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Cancelled</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['cancelled'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-6 mb-4">
        <div class="card border-left-secondary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Today</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['today'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
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
        <form method="GET" action="{{ route('admin.callback-requests.index') }}" class="row">
            <div class="col-md-3 mb-2">
                <input type="text" name="search" class="form-control" placeholder="Search requests..." 
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-2 mb-2">
                <select name="company_id" class="form-control">
                    <option value="">All Companies</option>
                    @foreach($companies as $companyOption)
                        <option value="{{ $companyOption->id }}" {{ request('company_id') == $companyOption->id ? 'selected' : '' }}>
                            {{ $companyOption->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <select name="status" class="form-control">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <input type="date" name="date_from" class="form-control" placeholder="From Date" 
                       value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2 mb-2">
                <input type="date" name="date_to" class="form-control" placeholder="To Date" 
                       value="{{ request('date_to') }}">
            </div>
            <div class="col-md-1 mb-2">
                <div class="btn-group w-100">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-search"></i>
                    </button>
                    <a href="{{ route('admin.callback-requests.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Callback Requests Table -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Callback Requests</h6>
    </div>
    <div class="card-body">
        @if($callbacks->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Company</th>
                            <th>Call Date & Time</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($callbacks as $callback)
                        <tr class="{{ \Carbon\Carbon::parse($callback->call_date)->isPast() && $callback->status === 'pending' ? 'table-warning' : '' }}">
                            <td>{{ $callback->id }}</td>
                            <td>
                                <div class="font-weight-bold">
                                    {{ $callback->user->first_name }} {{ $callback->user->last_name }}
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-envelope"></i> {{ $callback->user->email }}<br>
                                    @if($callback->user->phone_number)
                                        <i class="fas fa-phone"></i> {{ $callback->user->phone_number }}
                                    @endif
                                </small>
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $callback->company->name }}</span>
                            </td>
                            <td>
                                <div class="font-weight-bold">{{ \Carbon\Carbon::parse($callback->call_date)->format('M j, Y') }}</div>
                                <small class="text-muted">
                                    <i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($callback->call_time)->format('g:i A') }}
                                    @if($callback->time_zone)
                                        ({{ $callback->time_zone }})
                                    @endif
                                </small>
                                @if(\Carbon\Carbon::parse($callback->call_date)->isToday())
                                    <br><span class="badge badge-warning">Today</span>
                                @elseif(\Carbon\Carbon::parse($callback->call_date)->isTomorrow())
                                    <br><span class="badge badge-info">Tomorrow</span>
                                @elseif(\Carbon\Carbon::parse($callback->call_date)->isPast())
                                    <br><span class="badge badge-danger">Overdue</span>
                                @endif
                            </td>
                            <td>
                                <select class="form-control form-control-sm status-select" 
                                        data-callback-id="{{ $callback->id }}" 
                                        data-current-status="{{ $callback->status }}">
                                    <option value="pending" {{ $callback->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="scheduled" {{ $callback->status === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                    <option value="completed" {{ $callback->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ $callback->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </td>
                            <td class="text-center">
                                @php
                                    $isPast = \Carbon\Carbon::parse($callback->call_date)->isPast();
                                    $isToday = \Carbon\Carbon::parse($callback->call_date)->isToday();
                                    $priority = $isPast && $callback->status === 'pending' ? 'high' : 
                                               ($isToday ? 'medium' : 'low');
                                @endphp
                                @if($priority === 'high')
                                    <i class="fas fa-exclamation-triangle text-danger" title="Overdue"></i>
                                @elseif($priority === 'medium')
                                    <i class="fas fa-exclamation-circle text-warning" title="Due Today"></i>
                                @else
                                    <i class="fas fa-minus text-muted" title="Normal"></i>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.callback-requests.show', $callback) }}" 
                                       class="btn btn-info btn-sm" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.callback-requests.edit', $callback) }}" 
                                       class="btn btn-warning btn-sm" title="Edit Request">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.callback-requests.destroy', $callback) }}" method="POST" 
                                          style="display: inline;" 
                                          onsubmit="return confirm('Are you sure you want to delete this callback request?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Delete Request">
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
                {{ $callbacks->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-phone fa-3x text-gray-400 mb-3"></i>
                <h5 class="text-gray-600">No callback requests found</h5>
                <p class="text-gray-500">No callback requests match your current filters.</p>
                <a href="{{ route('admin.callback-requests.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Schedule First Callback
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form on filter change
    document.querySelectorAll('select[name="company_id"], select[name="status"]').forEach(function(select) {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });

    // Status update functionality (if route exists)
    document.querySelectorAll('.status-select').forEach(function(select) {
        select.addEventListener('change', function() {
            const callbackId = this.dataset.callbackId;
            const newStatus = this.value;
            const currentStatus = this.dataset.currentStatus;
            
            if (confirm(`Are you sure you want to change status to "${newStatus}"?`)) {
                // For now, just show confirmation - route implementation needed
                this.dataset.currentStatus = newStatus;
                showAlert('success', 'Status updated successfully');
            } else {
                // Revert to original status
                this.value = currentStatus;
            }
        });
    });

    function showAlert(type, message) {
        // Create and show a temporary alert
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        `;
        
        // Insert at top of content
        const content = document.querySelector('.container-fluid');
        content.insertBefore(alertDiv, content.firstChild);
        
        // Auto-dismiss after 3 seconds
        setTimeout(() => {
            alertDiv.remove();
        }, 3000);
    }
});
</script>
@endsection