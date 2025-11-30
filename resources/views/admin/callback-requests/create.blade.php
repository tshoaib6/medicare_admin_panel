@extends('layouts.admin')

@section('title', 'Schedule New Callback')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Schedule New Callback</h1>
    <a href="{{ route('admin.callback-requests.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to List
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Callback Request Information</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.callback-requests.store') }}" method="POST">
            @csrf
            
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
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
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
                                        <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
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
                                       id="call_date" name="call_date" value="{{ old('call_date', date('Y-m-d')) }}" 
                                       min="{{ date('Y-m-d') }}" required>
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
                                       id="call_time" name="call_time" value="{{ old('call_time') }}" required>
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
                                    <option value="EST" {{ old('time_zone') == 'EST' ? 'selected' : '' }}>Eastern (EST)</option>
                                    <option value="CST" {{ old('time_zone') == 'CST' ? 'selected' : '' }}>Central (CST)</option>
                                    <option value="MST" {{ old('time_zone') == 'MST' ? 'selected' : '' }}>Mountain (MST)</option>
                                    <option value="PST" {{ old('time_zone') == 'PST' ? 'selected' : '' }}>Pacific (PST)</option>
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
                                  id="notes" name="notes" rows="4"
                                  placeholder="Add any additional notes about this callback request...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-lg-4">
                    <div class="card bg-light">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold">Request Settings</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="status" class="form-label font-weight-bold">Initial Status</label>
                                <select class="form-control @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                    <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>
                                        📋 Pending
                                    </option>
                                    <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>
                                        📅 Scheduled
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="alert alert-info">
                                <h6 class="alert-heading">💡 Quick Tips</h6>
                                <ul class="mb-0">
                                    <li>Use "Pending" for new requests</li>
                                    <li>Use "Scheduled" if time is confirmed</li>
                                    <li>Consider customer's timezone</li>
                                    <li>Add detailed notes for context</li>
                                </ul>
                            </div>

                            <div class="form-group" id="customer-info" style="display: none;">
                                <h6 class="font-weight-bold text-gray-800">Customer Details</h6>
                                <div id="customer-details" class="bg-white p-2 rounded border">
                                    <!-- Customer info will be loaded here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-group mt-4">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.callback-requests.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-calendar-plus"></i> Schedule Callback
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
    const userSelect = document.getElementById('user_id');
    const customerInfo = document.getElementById('customer-info');
    const customerDetails = document.getElementById('customer-details');
    
    // Load customer details when user is selected
    userSelect.addEventListener('change', function() {
        const userId = this.value;
        
        if (userId) {
            // Show customer info section
            customerInfo.style.display = 'block';
            
            // Find selected user data from the options
            const selectedOption = this.options[this.selectedIndex];
            const userData = selectedOption.textContent.split(' - ');
            const customerName = userData[0];
            const customerEmail = userData[1];
            
            customerDetails.innerHTML = `
                <div class="mb-2">
                    <strong>Name:</strong><br>
                    <span class="text-muted">${customerName}</span>
                </div>
                <div class="mb-2">
                    <strong>Email:</strong><br>
                    <span class="text-muted">${customerEmail}</span>
                </div>
                <div class="text-center">
                    <small class="text-muted">Selected customer information</small>
                </div>
            `;
        } else {
            customerInfo.style.display = 'none';
        }
    });

    // Set default time if not set
    const timeInput = document.getElementById('call_time');
    if (!timeInput.value) {
        // Set default to next business hour (9 AM - 5 PM)
        const now = new Date();
        const currentHour = now.getHours();
        
        let defaultHour;
        if (currentHour < 9) {
            defaultHour = 9;
        } else if (currentHour >= 17) {
            defaultHour = 9; // Next day 9 AM
        } else {
            defaultHour = currentHour + 1;
        }
        
        const defaultTime = defaultHour.toString().padStart(2, '0') + ':00';
        timeInput.value = defaultTime;
    }

    // Auto-select timezone based on browser
    const timezoneSelect = document.getElementById('time_zone');
    if (!timezoneSelect.value) {
        const userTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
        
        // Map common timezones to our options
        const timezoneMap = {
            'America/New_York': 'EST',
            'America/Chicago': 'CST',
            'America/Denver': 'MST',
            'America/Los_Angeles': 'PST'
        };
        
        if (timezoneMap[userTimezone]) {
            timezoneSelect.value = timezoneMap[userTimezone];
        }
    }
});
</script>
@endsection