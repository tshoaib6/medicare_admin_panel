@extends('layouts.admin')

@section('title', 'Activity Logs')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-history text-info mr-2"></i>
            Activity Logs & System Monitoring
        </h1>
        <div>
            <a href="{{ route('admin.activity-logs.export', request()->query()) }}" class="btn btn-success mr-2">
                <i class="fas fa-download fa-sm text-white-50"></i> Export CSV
            </a>
            <button class="btn btn-info" onclick="location.reload()">
                <i class="fas fa-sync-alt fa-sm text-white-50"></i> Refresh
            </button>
        </div>
    </div>

    <!-- Statistics Cards Row -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Activities</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['total']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Today's Activity</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['today']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Active Users Today</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['unique_users_today']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Unique IPs Today</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['unique_ips_today']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-globe fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Actions Chart -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <!-- Activity Timeline -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-clock mr-1"></i>
                        Today's Activity Timeline (Hourly)
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="hourlyChart" width="400" height="100"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Top Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-chart-pie mr-1"></i>
                        Top Actions
                    </h6>
                </div>
                <div class="card-body">
                    @foreach($stats['top_actions'] as $actionStat)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-sm font-weight-bold">{{ ucwords(str_replace('_', ' ', $actionStat->action)) }}</span>
                                <span class="text-sm">{{ number_format($actionStat->count) }}</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-success" role="progressbar" 
                                     style="width: {{ ($actionStat->count / $stats['top_actions']->max('count')) * 100 }}%">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter mr-1"></i>
                Advanced Filters & Search
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="row">
                <div class="col-lg-3 col-md-6 mb-3">
                    <label for="search" class="form-label small">Search</label>
                    <input type="text" class="form-control" name="search" id="search" 
                           placeholder="Search activities..." value="{{ request('search') }}">
                </div>
                
                <div class="col-lg-2 col-md-6 mb-3">
                    <label for="user_id" class="form-label small">User</label>
                    <select name="user_id" id="user_id" class="form-control">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-3">
                    <label for="action" class="form-label small">Action</label>
                    <select name="action" id="action" class="form-control">
                        <option value="">All Actions</option>
                        @foreach($actions as $actionOption)
                            <option value="{{ $actionOption }}" {{ request('action') == $actionOption ? 'selected' : '' }}>
                                {{ ucwords(str_replace('_', ' ', $actionOption)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-3">
                    <label for="date_from" class="form-label small">From Date</label>
                    <input type="date" class="form-control" name="date_from" id="date_from" 
                           value="{{ request('date_from') }}">
                </div>
                
                <div class="col-lg-2 col-md-6 mb-3">
                    <label for="date_to" class="form-label small">To Date</label>
                    <input type="date" class="form-control" name="date_to" id="date_to" 
                           value="{{ request('date_to') }}">
                </div>
                
                <div class="col-lg-1 col-md-12 mb-3">
                    <label class="form-label small">&nbsp;</label>
                    <div class="d-flex flex-column">
                        <button type="submit" class="btn btn-primary btn-sm mb-1">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Activity Logs Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list mr-1"></i>
                Activity Logs ({{ number_format($activities->total()) }} total)
            </h6>
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-info dropdown-toggle" type="button" id="viewOptions" data-toggle="dropdown">
                    <i class="fas fa-eye"></i> View Options
                </button>
                <div class="dropdown-menu" aria-labelledby="viewOptions">
                    <button class="dropdown-item" onclick="toggleColumn('metadata')">
                        <i class="fas fa-code mr-2"></i>Toggle Metadata
                    </button>
                    <button class="dropdown-item" onclick="toggleColumn('ip_address')">
                        <i class="fas fa-globe mr-2"></i>Toggle IP Address
                    </button>
                    <button class="dropdown-item" onclick="toggleColumn('user_agent')">
                        <i class="fas fa-desktop mr-2"></i>Toggle User Agent
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($activities->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="activityTable" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th width="60">ID</th>
                                <th width="130">Date/Time</th>
                                <th width="120">User</th>
                                <th width="140">Action</th>
                                <th>Description</th>
                                <th width="100" class="ip_address">IP Address</th>
                                <th width="80" class="user_agent">Browser</th>
                                <th width="60" class="metadata">Data</th>
                                <th width="60">Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activities as $activity)
                            <tr>
                                <td class="text-center">
                                    <small class="font-weight-bold">#{{ $activity->id }}</small>
                                </td>
                                <td class="small">
                                    <div class="font-weight-bold">{{ $activity->created_at->format('M j, Y') }}</div>
                                    <div class="text-muted">{{ $activity->created_at->format('g:i A') }}</div>
                                </td>
                                <td>
                                    @if($activity->user)
                                        <div class="d-flex align-items-center">
                                            <div class="mr-2">
                                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" 
                                                     style="width: 32px; height: 32px; font-size: 12px; color: white;">
                                                    {{ strtoupper(substr($activity->user->name, 0, 2)) }}
                                                </div>
                                            </div>
                                            <div class="small">
                                                <div class="font-weight-bold">{{ $activity->user->name }}</div>
                                                <div class="text-muted">{{ Str::limit($activity->user->email, 20) }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="badge badge-secondary">System</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-{{ $activity->action_color }} d-flex align-items-center">
                                        <i class="{{ $activity->action_icon }} mr-1"></i>
                                        {{ $activity->action_label }}
                                    </span>
                                </td>
                                <td class="small">
                                    {{ Str::limit($activity->description, 80) }}
                                </td>
                                <td class="small ip_address">
                                    @if($activity->ip_address)
                                        <code>{{ $activity->ip_address }}</code>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td class="small user_agent">
                                    @if($activity->user_agent)
                                        <span class="badge badge-light">{{ $activity->browser }}</span>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td class="text-center metadata">
                                    @if($activity->metadata)
                                        <button class="btn btn-outline-info btn-sm" onclick="showMetadata({{ json_encode($activity->metadata) }})">
                                            <i class="fas fa-code"></i>
                                        </button>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.activity-logs.show', $activity->id) }}" 
                                       class="btn btn-info btn-sm" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="mt-3 d-flex justify-content-between align-items-center">
                    <div class="small text-muted">
                        Showing {{ $activities->firstItem() }} to {{ $activities->lastItem() }} of {{ number_format($activities->total()) }} results
                    </div>
                    <div>
                        {{ $activities->appends(request()->query())->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-history fa-3x text-gray-300"></i>
                    </div>
                    <h5 class="text-gray-600">No activity logs found</h5>
                    <p class="text-muted">
                        @if(request()->hasAny(['search', 'user_id', 'action', 'date_from', 'date_to']))
                            No activities match your current filters. <a href="{{ route('admin.activity-logs.index') }}">Clear filters</a> to see all activities.
                        @else
                            No activities have been logged yet.
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Metadata Modal -->
<div class="modal fade" id="metadataModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-code mr-2"></i>
                    Activity Metadata
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <pre id="metadataContent" class="bg-light p-3 rounded"></pre>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Initialize hourly activity chart
    initHourlyChart();
    
    // Date range validation
    $('#date_from').on('change', function() {
        var fromDate = $(this).val();
        if (fromDate) {
            $('#date_to').attr('min', fromDate);
        }
    });
    
    // Auto-refresh every 30 seconds
    setInterval(function() {
        if (!$('.modal').hasClass('show')) { // Don't refresh if modal is open
            location.reload();
        }
    }, 30000);
});

function initHourlyChart() {
    var ctx = document.getElementById('hourlyChart').getContext('2d');
    var hourlyData = @json($stats['hourly_activity']);
    
    // Fill missing hours with 0
    var labels = [];
    var data = [];
    for (var i = 0; i < 24; i++) {
        labels.push(i + ':00');
        data.push(hourlyData[i] || 0);
    }
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Activities',
                data: data,
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
}

function showMetadata(metadata) {
    $('#metadataContent').text(JSON.stringify(metadata, null, 2));
    $('#metadataModal').modal('show');
}

function toggleColumn(columnClass) {
    $('.' + columnClass).toggle();
}

// Real-time updates notification
function checkForUpdates() {
    // This could be enhanced with WebSocket or polling for real-time updates
    console.log('Checking for new activities...');
}
</script>
@endpush