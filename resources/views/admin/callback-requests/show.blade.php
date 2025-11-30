@extends('layouts.admin')

@section('title', 'Callback Request Details')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Callback Request Details</h1>
    <div>
        <a href="{{ route('admin.callback-requests.edit', $callbackRequest) }}" class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm mr-2">
            <i class="fas fa-edit fa-sm text-white-50"></i> Edit Request
        </a>
        <a href="{{ route('admin.callback-requests.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to List
        </a>
    </div>
</div>

<div class="row">
    <!-- Main Details -->
    <div class="col-lg-8">
        <!-- Customer Information -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Customer Information</h6>
                <span class="badge badge-{{ $callbackRequest->status === 'pending' ? 'warning' : ($callbackRequest->status === 'scheduled' ? 'info' : ($callbackRequest->status === 'completed' ? 'success' : 'danger')) }} badge-lg">
                    {{ ucfirst($callbackRequest->status) }}
                </span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="font-weight-bold text-gray-800">Full Name</h6>
                        <p class="text-gray-700 mb-3">{{ $callbackRequest->user->first_name }} {{ $callbackRequest->user->last_name }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="font-weight-bold text-gray-800">Email Address</h6>
                        <p class="text-gray-700 mb-3">
                            <a href="mailto:{{ $callbackRequest->user->email }}" class="text-decoration-none">
                                <i class="fas fa-envelope text-info"></i> {{ $callbackRequest->user->email }}
                            </a>
                        </p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <h6 class="font-weight-bold text-gray-800">Phone Number</h6>
                        @if($callbackRequest->user->phone_number)
                            <p class="text-gray-700 mb-3">
                                <a href="tel:{{ $callbackRequest->user->phone_number }}" class="text-decoration-none">
                                    <i class="fas fa-phone text-success"></i> {{ $callbackRequest->user->phone_number }}
                                </a>
                            </p>
                        @else
                            <p class="text-muted mb-3">Not provided</p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h6 class="font-weight-bold text-gray-800">Member Since</h6>
                        <p class="text-gray-700 mb-3">{{ $callbackRequest->user->created_at->format('F j, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Callback Schedule -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Callback Schedule</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="font-weight-bold text-gray-800">Scheduled Date</h6>
                        <p class="text-gray-700 mb-3">
                            <i class="fas fa-calendar text-info"></i> 
                            {{ \Carbon\Carbon::parse($callbackRequest->call_date)->format('l, F j, Y') }}
                            @if(\Carbon\Carbon::parse($callbackRequest->call_date)->isToday())
                                <span class="badge badge-warning ml-2">Today</span>
                            @elseif(\Carbon\Carbon::parse($callbackRequest->call_date)->isTomorrow())
                                <span class="badge badge-info ml-2">Tomorrow</span>
                            @elseif(\Carbon\Carbon::parse($callbackRequest->call_date)->isPast())
                                <span class="badge badge-danger ml-2">Overdue</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="font-weight-bold text-gray-800">Scheduled Time</h6>
                        <p class="text-gray-700 mb-3">
                            <i class="fas fa-clock text-info"></i> 
                            {{ \Carbon\Carbon::parse($callbackRequest->call_time)->format('g:i A') }}
                            @if($callbackRequest->time_zone)
                                <small class="text-muted">({{ $callbackRequest->time_zone }})</small>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <h6 class="font-weight-bold text-gray-800">Associated Company</h6>
                        <div class="d-flex align-items-center">
                            <span class="badge badge-info mr-2">{{ $callbackRequest->company->name }}</span>
                            @if($callbackRequest->company->phone_number)
                                <small class="text-muted">
                                    <i class="fas fa-phone"></i> {{ $callbackRequest->company->phone_number }}
                                </small>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="font-weight-bold text-gray-800">Request Created</h6>
                        <p class="text-gray-700 mb-3">{{ $callbackRequest->created_at->format('F j, Y \a\t g:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notes Section -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Notes & Comments</h6>
            </div>
            <div class="card-body">
                @if($callbackRequest->notes)
                    <div class="mb-3">
                        <h6 class="font-weight-bold text-gray-800">Admin Notes</h6>
                        <div class="bg-light p-3 rounded">
                            <p class="mb-0">{{ $callbackRequest->notes }}</p>
                        </div>
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-sticky-note fa-2x text-gray-400 mb-2"></i>
                        <p class="text-gray-500">No notes available for this callback request.</p>
                        <a href="{{ route('admin.callback-requests.edit', $callbackRequest) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-plus"></i> Add Notes
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Status Management -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Status Management</h6>
            </div>
            <div class="card-body">
                <form id="statusUpdateForm">
                    @csrf
                    <div class="form-group">
                        <label for="status" class="font-weight-bold">Current Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="pending" {{ $callbackRequest->status === 'pending' ? 'selected' : '' }}>
                                📋 Pending
                            </option>
                            <option value="scheduled" {{ $callbackRequest->status === 'scheduled' ? 'selected' : '' }}>
                                📅 Scheduled
                            </option>
                            <option value="completed" {{ $callbackRequest->status === 'completed' ? 'selected' : '' }}>
                                ✅ Completed
                            </option>
                            <option value="cancelled" {{ $callbackRequest->status === 'cancelled' ? 'selected' : '' }}>
                                ❌ Cancelled
                            </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="quickNotes" class="font-weight-bold">Quick Notes</label>
                        <textarea class="form-control" id="quickNotes" name="notes" rows="3" 
                                  placeholder="Add status update notes...">{{ $callbackRequest->notes }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-save"></i> Update Status
                    </button>
                </form>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="tel:{{ $callbackRequest->user->phone_number }}" 
                       class="btn btn-success btn-block {{ !$callbackRequest->user->phone_number ? 'disabled' : '' }}">
                        <i class="fas fa-phone"></i> Call Customer
                    </a>
                    
                    <a href="mailto:{{ $callbackRequest->user->email }}" class="btn btn-info btn-block">
                        <i class="fas fa-envelope"></i> Send Email
                    </a>
                    
                    <a href="{{ route('admin.callback-requests.edit', $callbackRequest) }}" class="btn btn-warning btn-block">
                        <i class="fas fa-edit"></i> Edit Request
                    </a>
                    
                    <hr>
                    
                    @if($callbackRequest->status === 'pending')
                        <button onclick="quickStatusUpdate('scheduled')" class="btn btn-outline-info btn-block">
                            <i class="fas fa-calendar-check"></i> Mark as Scheduled
                        </button>
                    @endif
                    
                    @if(in_array($callbackRequest->status, ['pending', 'scheduled']))
                        <button onclick="quickStatusUpdate('completed')" class="btn btn-outline-success btn-block">
                            <i class="fas fa-check"></i> Mark as Completed
                        </button>
                    @endif
                    
                    <button onclick="quickStatusUpdate('cancelled')" class="btn btn-outline-danger btn-block">
                        <i class="fas fa-times"></i> Cancel Request
                    </button>
                </div>
            </div>
        </div>

        <!-- Timeline -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Request Timeline</h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <h6 class="font-weight-bold">Request Created</h6>
                            <p class="text-muted mb-0">{{ $callbackRequest->created_at->format('M j, Y g:i A') }}</p>
                        </div>
                    </div>
                    
                    @if($callbackRequest->updated_at != $callbackRequest->created_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="font-weight-bold">Last Updated</h6>
                                <p class="text-muted mb-0">{{ $callbackRequest->updated_at->format('M j, Y g:i A') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Delete Action -->
        <div class="card shadow mb-4 border-danger">
            <div class="card-header py-3 bg-danger">
                <h6 class="m-0 font-weight-bold text-white">Danger Zone</h6>
            </div>
            <div class="card-body">
                <p class="text-muted">Delete this callback request permanently. This action cannot be undone.</p>
                <form action="{{ route('admin.callback-requests.destroy', $callbackRequest) }}" method="POST" 
                      onsubmit="return confirm('Are you sure you want to delete this callback request? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-block">
                        <i class="fas fa-trash"></i> Delete Request
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.timeline {
    position: relative;
    padding-left: 2rem;
}

.timeline:before {
    content: '';
    position: absolute;
    left: 0.75rem;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e3e6f0;
}

.timeline-item {
    position: relative;
    margin-bottom: 1rem;
}

.timeline-marker {
    position: absolute;
    left: -2.25rem;
    top: 0.25rem;
    width: 0.75rem;
    height: 0.75rem;
    border-radius: 50%;
    border: 2px solid #fff;
}

.timeline-content {
    padding-left: 0.5rem;
}

.badge-lg {
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Status update form
    document.getElementById('statusUpdateForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const status = formData.get('status');
        const notes = formData.get('notes');
        
        updateStatus(status, notes);
    });
});

function quickStatusUpdate(status) {
    const confirmMessage = `Are you sure you want to mark this request as "${status}"?`;
    
    if (confirm(confirmMessage)) {
        updateStatus(status);
    }
}

function updateStatus(status, notes = '') {
    fetch('{{ route("admin.callback-requests.update", $callbackRequest) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            status: status,
            notes: notes,
            user_id: {{ $callbackRequest->user_id }},
            company_id: {{ $callbackRequest->company_id }},
            call_date: '{{ $callbackRequest->call_date }}',
            call_time: '{{ $callbackRequest->call_time }}',
            time_zone: '{{ $callbackRequest->time_zone }}',
            _method: 'PUT'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success || response.ok) {
            showAlert('success', 'Status updated successfully');
            // Reload page after 1 second
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showAlert('error', data.message || 'Failed to update status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'An error occurred while updating status');
    });
}

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
</script>
@endsection