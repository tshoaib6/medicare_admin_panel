@extends('layouts.admin')

@section('title', 'Questionnaire Responses')

@section('page-header')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-poll-h"></i> Questionnaire Responses
    </h1>
    <div class="d-flex">
        <button class="btn btn-info btn-sm mr-2" onclick="refreshStats()">
            <i class="fas fa-chart-bar"></i> Refresh Stats
        </button>
        <a href="{{ route('admin.questionnaire-responses.export') }}" class="btn btn-success btn-sm">
            <i class="fas fa-download"></i> Export CSV
        </a>
    </div>
</div>
@endsection

@section('content')
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Responses</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="stat-total">{{ $responses->total() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-comments fa-2x text-gray-300"></i>
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
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Completed</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="stat-completed">-</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">In Progress</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="stat-progress">-</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Completion Rate</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="stat-rate">-</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-percentage fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Filters</h6>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.questionnaire-responses.index') }}">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="search">Search Users</label>
                        <input type="text" class="form-control" name="search" id="search" 
                               placeholder="Name or email..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="questionnaire_id">Questionnaire</label>
                        <select class="form-control" name="questionnaire_id" id="questionnaire_id">
                            <option value="">All Questionnaires</option>
                            @foreach($questionnaires as $questionnaire)
                                <option value="{{ $questionnaire->id }}" 
                                        {{ request('questionnaire_id') == $questionnaire->id ? 'selected' : '' }}>
                                    {{ $questionnaire->title }} ({{ $questionnaire->plan->name ?? 'No Plan' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" name="status" id="status">
                            <option value="">All Status</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" 
                                        {{ request('status') == $status ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="per_page">Per Page</label>
                        <select class="form-control" name="per_page" id="per_page">
                            <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <div class="form-group w-100">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-search"></i> Filter
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Responses Table -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            Questionnaire Responses ({{ $responses->total() }} total)
        </h6>
    </div>
    <div class="card-body">
        @if($responses->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Questionnaire</th>
                            <th>Plan</th>
                            <th>Status</th>
                            <th>Progress</th>
                            <th>Started</th>
                            <th>Completed</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($responses as $response)
                        <tr>
                            <td>{{ $response->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-primary">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold">{{ $response->user->first_name }} {{ $response->user->last_name }}</div>
                                        <div class="text-gray-600 small">{{ $response->user->email }}</div>
                                        <div class="text-gray-600 small">{{ $response->user->phone_number }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="font-weight-bold">{{ $response->questionnaire->title }}</div>
                                <div class="text-gray-600 small">{{ Str::limit($response->questionnaire->description, 50) }}</div>
                            </td>
                            <td>
                                @if($response->questionnaire->plan)
                                    <span class="badge badge-info">{{ $response->questionnaire->plan->name }}</span>
                                @else
                                    <span class="badge badge-secondary">No Plan</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusColors = [
                                        'completed' => 'success',
                                        'in_progress' => 'warning', 
                                        'abandoned' => 'danger'
                                    ];
                                @endphp
                                <span class="badge badge-{{ $statusColors[$response->status] ?? 'secondary' }}">
                                    {{ ucfirst(str_replace('_', ' ', $response->status)) }}
                                </span>
                            </td>
                            <td>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar" role="progressbar" 
                                         style="width: {{ $response->completion_percentage }}%;" 
                                         aria-valuenow="{{ $response->completion_percentage }}" 
                                         aria-valuemin="0" aria-valuemax="100">
                                        {{ $response->completion_percentage }}%
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($response->started_at)
                                    <div class="small">{{ $response->started_at->format('M d, Y') }}</div>
                                    <div class="text-gray-600 small">{{ $response->started_at->format('H:i A') }}</div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($response->completed_at)
                                    <div class="small">{{ $response->completed_at->format('M d, Y') }}</div>
                                    <div class="text-gray-600 small">{{ $response->completed_at->format('H:i A') }}</div>
                                    @if($response->time_taken)
                                        <div class="text-success small">{{ $response->time_taken }} min</div>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown no-arrow">
                                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" 
                                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                         aria-labelledby="dropdownMenuLink">
                                        <a class="dropdown-item" href="{{ route('admin.questionnaire-responses.show', $response) }}">
                                            <i class="fas fa-eye fa-sm fa-fw mr-2 text-gray-400"></i>
                                            View Details
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <form method="POST" action="{{ route('admin.questionnaire-responses.destroy', $response) }}" 
                                              style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this response?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="fas fa-trash fa-sm fa-fw mr-2 text-gray-400"></i>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                    Showing {{ $responses->firstItem() }} to {{ $responses->lastItem() }} of {{ $responses->total() }} results
                </div>
                <div>
                    {{ $responses->links() }}
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-poll-h fa-3x text-gray-300 mb-3"></i>
                <h5 class="text-gray-600">No questionnaire responses found</h5>
                <p class="text-gray-500">Responses will appear here once users start completing questionnaires.</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function refreshStats() {
    fetch('{{ route("admin.questionnaire-responses.stats") }}')
        .then(response => response.json())
        .then(data => {
            document.getElementById('stat-total').textContent = data.total;
            document.getElementById('stat-completed').textContent = data.completed;
            document.getElementById('stat-progress').textContent = data.in_progress;
            document.getElementById('stat-rate').textContent = data.completion_rate + '%';
        })
        .catch(error => {
            console.error('Error fetching stats:', error);
        });
}

// Auto refresh stats every 30 seconds
setInterval(refreshStats, 30000);

// Initial stats load
document.addEventListener('DOMContentLoaded', refreshStats);
</script>
@endpush