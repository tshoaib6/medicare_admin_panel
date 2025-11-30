@extends('layouts.admin')

@section('title', 'Activity Log Details')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-eye text-info mr-2"></i>
            Activity Log Details
        </h1>
        <div>
            <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Logs
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Activity Details -->
        <div class="col-lg-8">
            <!-- Activity Overview Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="{{ $activity->action_icon }} mr-1"></i>
                        Activity #{{ $activity->id }}
                    </h6>
                    <span class="badge badge-{{ $activity->action_color }} badge-pill">
                        {{ $activity->action_label }}
                    </span>
                </div>
                <div class="card-body">
                    <!-- Action Description -->
                    <div class="mb-4">
                        <h5 class="font-weight-bold text-dark mb-2">
                            <i class="fas fa-align-left mr-1"></i>
                            Description
                        </h5>
                        <p class="text-muted mb-0">{{ $activity->description }}</p>
                    </div>

                    <!-- Activity Details Grid -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="font-weight-bold text-dark mb-2">
                                <i class="fas fa-calendar mr-1"></i>
                                Timestamp
                            </h6>
                            <div>
                                <div class="font-weight-bold">{{ $activity->created_at->format('F j, Y') }}</div>
                                <div class="text-muted">{{ $activity->created_at->format('g:i:s A T') }}</div>
                                <small class="text-info">{{ $activity->created_at->diffForHumans() }}</small>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <h6 class="font-weight-bold text-dark mb-2">
                                <i class="fas fa-user mr-1"></i>
                                User Information
                            </h6>
                            @if($activity->user)
                                <div class="d-flex align-items-center">
                                    <div class="mr-3">
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 40px; height: 40px; font-size: 14px; color: white;">
                                            {{ strtoupper(substr($activity->user->name, 0, 2)) }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold">{{ $activity->user->name }}</div>
                                        <div class="text-muted">{{ $activity->user->email }}</div>
                                        <small class="text-info">ID: {{ $activity->user->id }}</small>
                                    </div>
                                </div>
                            @else
                                <div class="d-flex align-items-center">
                                    <div class="mr-3">
                                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 40px; height: 40px; font-size: 14px; color: white;">
                                            <i class="fas fa-cog"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold">System</div>
                                        <div class="text-muted">Automated action</div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-6 mb-3">
                            <h6 class="font-weight-bold text-dark mb-2">
                                <i class="fas fa-globe mr-1"></i>
                                IP Address
                            </h6>
                            @if($activity->ip_address)
                                <div>
                                    <code class="bg-light px-2 py-1 rounded">{{ $activity->ip_address }}</code>
                                    <div class="mt-1">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Geographic tracking available
                                        </small>
                                    </div>
                                </div>
                            @else
                                <span class="text-muted">Not recorded</span>
                            @endif
                        </div>

                        <div class="col-md-6 mb-3">
                            <h6 class="font-weight-bold text-dark mb-2">
                                <i class="fas fa-desktop mr-1"></i>
                                Browser & Platform
                            </h6>
                            @if($activity->user_agent)
                                <div>
                                    <span class="badge badge-primary">{{ $activity->browser }}</span>
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            <strong>User Agent:</strong><br>
                                            {{ Str::limit($activity->user_agent, 80) }}
                                        </small>
                                    </div>
                                </div>
                            @else
                                <span class="text-muted">Not recorded</span>
                            @endif
                        </div>
                    </div>

                    <!-- Metadata Section -->
                    @if($activity->metadata)
                        <div class="mt-4">
                            <h6 class="font-weight-bold text-dark mb-3">
                                <i class="fas fa-code mr-1"></i>
                                Additional Data & Metadata
                            </h6>
                            <div class="bg-light p-3 rounded">
                                <pre class="mb-0"><code>{{ json_encode($activity->metadata, JSON_PRETTY_PRINT) }}</code></pre>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Related Activities -->
            @if($relatedActivities->count() > 0)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-success">
                            <i class="fas fa-link mr-1"></i>
                            Related Activities
                            <small class="text-muted">(Same user/IP within 2 hours)</small>
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            @foreach($relatedActivities as $relatedActivity)
                                <div class="timeline-item d-flex align-items-start mb-3 pb-3 border-bottom">
                                    <div class="timeline-marker mr-3 mt-1">
                                        <div class="bg-{{ $relatedActivity->action_color }} rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 32px; height: 32px; font-size: 12px; color: white;">
                                            <i class="{{ $relatedActivity->action_icon }}"></i>
                                        </div>
                                    </div>
                                    <div class="timeline-content flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1">
                                                    <span class="badge badge-{{ $relatedActivity->action_color }} mr-2">
                                                        {{ $relatedActivity->action_label }}
                                                    </span>
                                                    #{{ $relatedActivity->id }}
                                                </h6>
                                                <p class="mb-1 text-muted small">{{ $relatedActivity->description }}</p>
                                                <div class="small text-info">
                                                    @if($relatedActivity->user)
                                                        {{ $relatedActivity->user->name }} • 
                                                    @endif
                                                    {{ $relatedActivity->created_at->format('M j, g:i A') }}
                                                </div>
                                            </div>
                                            <a href="{{ route('admin.activity-logs.show', $relatedActivity->id) }}" 
                                               class="btn btn-outline-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar Information -->
        <div class="col-lg-4">
            <!-- Quick Info Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-info-circle mr-1"></i>
                        Quick Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-5 text-muted small">Log ID:</div>
                        <div class="col-7 font-weight-bold">#{{ $activity->id }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5 text-muted small">Action:</div>
                        <div class="col-7">
                            <span class="badge badge-{{ $activity->action_color }}">{{ $activity->action }}</span>
                        </div>
                    </div>
                    @if($activity->user)
                        <div class="row mb-2">
                            <div class="col-5 text-muted small">User ID:</div>
                            <div class="col-7 font-weight-bold">{{ $activity->user_id }}</div>
                        </div>
                    @endif
                    @if($activity->ip_address)
                        <div class="row mb-2">
                            <div class="col-5 text-muted small">IP Address:</div>
                            <div class="col-7">
                                <code>{{ $activity->ip_address }}</code>
                            </div>
                        </div>
                    @endif
                    <div class="row mb-2">
                        <div class="col-5 text-muted small">Browser:</div>
                        <div class="col-7">{{ $activity->browser }}</div>
                    </div>
                    <div class="row">
                        <div class="col-5 text-muted small">Recorded:</div>
                        <div class="col-7 small">{{ $activity->created_at->diffForHumans() }}</div>
                    </div>
                </div>
            </div>

            <!-- Navigation Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-compass mr-1"></i>
                        Navigation
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $prevActivity = \App\Models\ActivityLog::where('id', '<', $activity->id)->orderByDesc('id')->first();
                        $nextActivity = \App\Models\ActivityLog::where('id', '>', $activity->id)->orderBy('id')->first();
                    @endphp
                    
                    <div class="d-flex justify-content-between mb-3">
                        @if($prevActivity)
                            <a href="{{ route('admin.activity-logs.show', $prevActivity->id) }}" 
                               class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-chevron-left mr-1"></i>Previous
                            </a>
                        @else
                            <span class="btn btn-secondary btn-sm disabled">
                                <i class="fas fa-chevron-left mr-1"></i>Previous
                            </span>
                        @endif
                        
                        @if($nextActivity)
                            <a href="{{ route('admin.activity-logs.show', $nextActivity->id) }}" 
                               class="btn btn-outline-secondary btn-sm">
                                Next<i class="fas fa-chevron-right ml-1"></i>
                            </a>
                        @else
                            <span class="btn btn-secondary btn-sm disabled">
                                Next<i class="fas fa-chevron-right ml-1"></i>
                            </span>
                        @endif
                    </div>
                    
                    <hr>
                    
                    <div class="text-center">
                        <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-primary btn-sm btn-block">
                            <i class="fas fa-list mr-2"></i>View All Logs
                        </a>
                        
                        @if($activity->user)
                            <a href="{{ route('admin.activity-logs.index', ['user_id' => $activity->user_id]) }}" 
                               class="btn btn-info btn-sm btn-block mt-2">
                                <i class="fas fa-user mr-2"></i>View User's Activities
                            </a>
                        @endif
                        
                        <a href="{{ route('admin.activity-logs.index', ['action' => $activity->action]) }}" 
                           class="btn btn-success btn-sm btn-block mt-2">
                            <i class="fas fa-filter mr-2"></i>Filter by Action
                        </a>
                    </div>
                </div>
            </div>

            <!-- System Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-secondary">
                        <i class="fas fa-server mr-1"></i>
                        System Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="small">
                        <div class="row mb-2">
                            <div class="col-6 text-muted">Database ID:</div>
                            <div class="col-6">{{ $activity->id }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6 text-muted">Table:</div>
                            <div class="col-6">activity_logs</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6 text-muted">Created:</div>
                            <div class="col-6">{{ $activity->created_at->format('Y-m-d H:i:s') }}</div>
                        </div>
                        <div class="row">
                            <div class="col-6 text-muted">Updated:</div>
                            <div class="col-6">{{ $activity->updated_at->format('Y-m-d H:i:s') }}</div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="text-center">
                        <button class="btn btn-outline-secondary btn-sm" onclick="copyToClipboard('{{ $activity->id }}')">
                            <i class="fas fa-copy mr-1"></i>Copy ID
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success message
        toastr.success('ID copied to clipboard!');
    }).catch(function(err) {
        console.error('Could not copy text: ', err);
    });
}

// Add some interactivity
$(document).ready(function() {
    // Highlight the current activity in any timeline
    $('.timeline-item').hover(
        function() {
            $(this).addClass('bg-light');
        },
        function() {
            $(this).removeClass('bg-light');
        }
    );
    
    // Auto-expand long metadata
    $('pre code').each(function() {
        if ($(this).height() > 200) {
            $(this).css('max-height', '200px');
            $(this).css('overflow-y', 'auto');
        }
    });
});
</script>
@endpush