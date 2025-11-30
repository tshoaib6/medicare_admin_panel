@extends('layouts.admin')

@section('title', 'Edit Callback Request')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Callback Request</h1>
    <div>
        <a href="{{ route('admin.callback-requests.show', $callbackRequest) }}" class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm mr-2">
            <i class="fas fa-eye fa-sm text-white-50"></i> View Details
        </a>
        <a href="{{ route('admin.callback-requests.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to List
        </a>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Update Callback Request Information</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.callback-requests.update', $callbackRequest) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <!-- Left Column -->
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user_id" class="form-label font-weight-bold">
                                    Customer <span class="text-danger">*</span>
                                </label>
                                <select class="form-control @error('user_id') is-invalid @enderror" 
                                        id="user_id" name="user_id" required>
                                    <option value="">Select a customer...</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id', $callbackRequest->user_id) == $user->id ? 'selected' : '' }}>
                                            {{ $user->first_name }} {{ $user->last_name }} - {{ $user->email }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="company_id" class="form-label font-weight-bold">
                                    Company <span class="text-danger">*</span>
                                </label>
                                <select class="form-control @error('company_id') is-invalid @enderror" 
                                        id="company_id" name="company_id" required>
                                    <option value="">Select a company...</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}" {{ old('company_id', $callbackRequest->company_id) == $company->id ? 'selected' : '' }}>
                                            {{ $company->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('company_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="call_date" class="form-label font-weight-bold">
                                    Call Date <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control @error('call_date') is-invalid @enderror" 
                                       id="call_date" name="call_date" 
                                       value="{{ old('call_date', $callbackRequest->call_date->format('Y-m-d')) }}" 
                                       required>
                                @error('call_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="call_time" class="form-label font-weight-bold">
                                    Call Time <span class="text-danger">*</span>
                                </label>
                                <input type="time" class="form-control @error('call_time') is-invalid @enderror" 
                                       id="call_time" name="call_time" 
                                       value="{{ old('call_time', \Carbon\Carbon::parse($callbackRequest->call_time)->format('H:i')) }}" 
                                       required>
                                @error('call_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="time_zone" class="form-label font-weight-bold">Time Zone</label>
                                <select class="form-control @error('time_zone') is-invalid @enderror" 
                                        id="time_zone" name="time_zone">
                                    <option value="">Select timezone...</option>
                                    <option value="EST" {{ old('time_zone', $callbackRequest->time_zone) == 'EST' ? 'selected' : '' }}>Eastern (EST)</option>
                                    <option value="CST" {{ old('time_zone', $callbackRequest->time_zone) == 'CST' ? 'selected' : '' }}>Central (CST)</option>
                                    <option value="MST" {{ old('time_zone', $callbackRequest->time_zone) == 'MST' ? 'selected' : '' }}>Mountain (MST)</option>
                                    <option value="PST" {{ old('time_zone', $callbackRequest->time_zone) == 'PST' ? 'selected' : '' }}>Pacific (PST)</option>
                                </select>
                                @error('time_zone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="notes" class="form-label font-weight-bold">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" name="notes" rows="5"
                                  placeholder="Add any additional notes about this callback request...">{{ old('notes', $callbackRequest->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Use this space to add important context, customer preferences, or call outcomes.
                        </small>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-lg-4">
                    <div class="card bg-light mb-3">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold">Status Management</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="status" class="form-label font-weight-bold">Current Status</label>
                                <select class="form-control @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                    <option value="pending" {{ old('status', $callbackRequest->status) == 'pending' ? 'selected' : '' }}>
                                        📋 Pending
                                    </option>
                                    <option value="scheduled" {{ old('status', $callbackRequest->status) == 'scheduled' ? 'selected' : '' }}>
                                        📅 Scheduled
                                    </option>
                                    <option value="completed" {{ old('status', $callbackRequest->status) == 'completed' ? 'selected' : '' }}>
                                        ✅ Completed
                                    </option>
                                    <option value="cancelled" {{ old('status', $callbackRequest->status) == 'cancelled' ? 'selected' : '' }}>
                                        ❌ Cancelled
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="alert alert-info">
                                <h6 class="alert-heading">Status Guidelines</h6>
                                <ul class="mb-0 small">
                                    <li><strong>Pending:</strong> Awaiting confirmation</li>
                                    <li><strong>Scheduled:</strong> Time confirmed with customer</li>
                                    <li><strong>Completed:</strong> Call was made successfully</li>
                                    <li><strong>Cancelled:</strong> Request was cancelled</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="card bg-white border">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold text-primary">Request Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>Request ID:</strong><br>
                                <span class="text-muted">#{{ $callbackRequest->id }}</span>
                            </div>
                            <div class="mb-3">
                                <strong>Created:</strong><br>
                                <span class="text-muted">{{ $callbackRequest->created_at->format('M j, Y g:i A') }}</span>
                            </div>
                            <div class="mb-3">
                                <strong>Last Updated:</strong><br>
                                <span class="text-muted">{{ $callbackRequest->updated_at->format('M j, Y g:i A') }}</span>
                            </div>
                            
                            @if(\Carbon\Carbon::parse($callbackRequest->call_date)->isPast() && $callbackRequest->status !== 'completed')
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <strong>Overdue!</strong><br>
                                    This callback is past due. Consider updating the status or rescheduling.
                                </div>
                            @elseif(\Carbon\Carbon::parse($callbackRequest->call_date)->isToday())
                                <div class="alert alert-info">
                                    <i class="fas fa-calendar-day"></i>
                                    <strong>Due Today!</strong><br>
                                    This callback is scheduled for today.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-group mt-4">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.callback-requests.show', $callbackRequest) }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Callback Request
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('status');
    const notesTextarea = document.getElementById('notes');
    
    // Add status change confirmation
    let originalStatus = statusSelect.value;
    
    statusSelect.addEventListener('change', function() {
        const newStatus = this.value;
        const statusMessages = {
            'pending': 'This will mark the request as pending review.',
            'scheduled': 'This will mark the request as scheduled and confirmed.',
            'completed': 'This will mark the request as completed. Consider adding outcome notes.',
            'cancelled': 'This will mark the request as cancelled. Please add a reason in the notes.'
        };
        
        if (newStatus !== originalStatus && statusMessages[newStatus]) {
            if (!confirm(`${statusMessages[newStatus]} Continue?`)) {
                this.value = originalStatus;
                return;
            }
            
            // Add automatic note if changing to cancelled or completed
            if (newStatus === 'cancelled' || newStatus === 'completed') {
                const timestamp = new Date().toLocaleDateString() + ' ' + new Date().toLocaleTimeString();
                let autoNote = `\n\n--- Status changed to ${newStatus} on ${timestamp} ---\n`;
                
                if (newStatus === 'completed') {
                    autoNote += 'Call completed. ';
                } else if (newStatus === 'cancelled') {
                    autoNote += 'Request cancelled. ';
                }
                
                notesTextarea.value += autoNote;
                notesTextarea.focus();
            }
            
            originalStatus = newStatus;
        }
    });

    // Highlight overdue dates
    const dateInput = document.getElementById('call_date');
    const today = new Date().toISOString().split('T')[0];
    
    dateInput.addEventListener('change', function() {
        if (this.value < today) {
            this.classList.add('is-warning');
            if (!confirm('The selected date is in the past. Are you sure you want to keep this date?')) {
                this.value = today;
                this.classList.remove('is-warning');
            }
        } else {
            this.classList.remove('is-warning');
        }
    });
    
    // Add custom styling for warning dates
    const style = document.createElement('style');
    style.textContent = `
        .form-control.is-warning {
            border-color: #ffc107;
            box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
        }
    `;
    document.head.appendChild(style);
});
</script>
@endsection