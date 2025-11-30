@extends('layouts.admin')

@section('title', 'Advertisement Details')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-eye text-info mr-2"></i>
            Advertisement Details
        </h1>
        <div>
            <a href="{{ route('admin.ads.edit', $ad) }}" class="btn btn-warning mr-2">
                <i class="fas fa-edit fa-sm text-white-50"></i> Edit Ad
            </a>
            <a href="{{ route('admin.ads.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Ads
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Ad Details Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bullhorn mr-1"></i>
                        {{ $ad->title }}
                    </h6>
                    <div>
                        <span class="badge badge-{{ $ad->status_color }} badge-pill">{{ $ad->status }}</span>
                        <span class="badge badge-{{ $ad->type === 'banner' ? 'primary' : ($ad->type === 'popup' ? 'info' : ($ad->type === 'inline' ? 'success' : 'warning')) }} ml-2">
                            {{ ucfirst($ad->type) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Ad Preview -->
                    @if($ad->image_url || $ad->content_html)
                        <div class="mb-4">
                            <h6 class="font-weight-bold text-dark mb-3">
                                <i class="fas fa-desktop mr-1"></i>
                                Advertisement Preview
                            </h6>
                            
                            @if($ad->content_html)
                                <!-- Custom HTML Content -->
                                <div class="border p-3 rounded bg-light">
                                    {!! $ad->content_html !!}
                                </div>
                            @elseif($ad->image_url)
                                <!-- Standard Image Display -->
                                <div class="text-center border p-4 rounded bg-light">
                                    <img src="{{ Storage::url($ad->image_url) }}" alt="{{ $ad->title }}" 
                                         class="img-fluid rounded shadow" style="max-width: 400px;">
                                    @if($ad->target_url)
                                        <div class="mt-2">
                                            <a href="{{ $ad->target_url }}" target="_blank" class="btn btn-primary btn-sm">
                                                <i class="fas fa-external-link-alt mr-1"></i>
                                                Visit Link
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Description -->
                    @if($ad->description)
                        <div class="mb-4">
                            <h6 class="font-weight-bold text-dark mb-2">
                                <i class="fas fa-align-left mr-1"></i>
                                Description
                            </h6>
                            <p class="text-muted">{{ $ad->description }}</p>
                        </div>
                    @endif

                    <!-- Details Grid -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="font-weight-bold text-dark mb-2">
                                <i class="fas fa-tag mr-1"></i>
                                Advertisement Type
                            </h6>
                            <span class="badge badge-{{ $ad->type === 'banner' ? 'primary' : ($ad->type === 'popup' ? 'info' : ($ad->type === 'inline' ? 'success' : 'warning')) }} badge-pill">
                                {{ ucfirst($ad->type) }}
                            </span>
                            <small class="d-block text-muted mt-1">
                                @switch($ad->type)
                                    @case('banner')
                                        Full-width display at top/bottom of pages
                                        @break
                                    @case('popup')
                                        Modal overlay for high visibility
                                        @break
                                    @case('inline')
                                        Integrated within page content
                                        @break
                                    @case('sidebar')
                                        Persistent side panel display
                                        @break
                                @endswitch
                            </small>
                        </div>

                        @if($ad->target_url)
                            <div class="col-md-6 mb-3">
                                <h6 class="font-weight-bold text-dark mb-2">
                                    <i class="fas fa-external-link-alt mr-1"></i>
                                    Target URL
                                </h6>
                                <a href="{{ $ad->target_url }}" target="_blank" class="text-primary">
                                    {{ Str::limit($ad->target_url, 50) }}
                                    <i class="fas fa-external-link-alt ml-1 small"></i>
                                </a>
                            </div>
                        @endif

                        @if($ad->target_audience)
                            <div class="col-md-6 mb-3">
                                <h6 class="font-weight-bold text-dark mb-2">
                                    <i class="fas fa-users mr-1"></i>
                                    Target Audience
                                </h6>
                                <p class="text-muted mb-0">{{ $ad->target_audience }}</p>
                            </div>
                        @endif

                        <div class="col-md-6 mb-3">
                            <h6 class="font-weight-bold text-dark mb-2">
                                <i class="fas fa-toggle-on mr-1"></i>
                                Status
                            </h6>
                            <span class="badge badge-{{ $ad->status_color }} badge-pill">{{ $ad->status }}</span>
                            @if($ad->status === 'Scheduled')
                                <small class="d-block text-muted">Starts: {{ $ad->start_date->format('M j, Y') }}</small>
                            @elseif($ad->status === 'Expired')
                                <small class="d-block text-muted">Ended: {{ $ad->end_date->format('M j, Y') }}</small>
                            @endif
                        </div>
                    </div>

                    <!-- Schedule Information -->
                    @if($ad->start_date || $ad->end_date)
                        <div class="mb-4">
                            <h6 class="font-weight-bold text-dark mb-3">
                                <i class="fas fa-calendar mr-1"></i>
                                Schedule
                            </h6>
                            <div class="row">
                                @if($ad->start_date)
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-play text-success mr-2"></i>
                                            <div>
                                                <small class="text-muted">Start Date</small>
                                                <div class="font-weight-bold">{{ $ad->start_date->format('M j, Y') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if($ad->end_date)
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-stop text-danger mr-2"></i>
                                            <div>
                                                <small class="text-muted">End Date</small>
                                                <div class="font-weight-bold">{{ $ad->end_date->format('M j, Y') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            @if(!$ad->start_date && !$ad->end_date)
                                <p class="text-muted mb-0">
                                    <i class="fas fa-infinity mr-1"></i>
                                    This ad runs indefinitely
                                </p>
                            @endif
                        </div>
                    @endif

                    <!-- Custom HTML Content -->
                    @if($ad->content_html)
                        <div class="mb-4">
                            <h6 class="font-weight-bold text-dark mb-2">
                                <i class="fas fa-code mr-1"></i>
                                Custom HTML Content
                            </h6>
                            <div class="bg-light p-3 rounded">
                                <pre class="mb-0 small"><code>{{ $ad->content_html }}</code></pre>
                            </div>
                        </div>
                    @endif

                    <!-- Meta Information -->
                    <div class="border-top pt-3">
                        <div class="row text-muted small">
                            <div class="col-md-6">
                                <i class="fas fa-plus mr-1"></i>
                                <strong>Created:</strong> {{ $ad->created_at->format('M j, Y g:i A') }}
                            </div>
                            <div class="col-md-6">
                                <i class="fas fa-edit mr-1"></i>
                                <strong>Last Updated:</strong> {{ $ad->updated_at->format('M j, Y g:i A') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-cogs mr-1"></i>
                        Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <a href="{{ route('admin.ads.edit', $ad) }}" class="btn btn-warning btn-block">
                                <i class="fas fa-edit mr-2"></i>Edit Advertisement
                            </a>
                        </div>
                        <div class="col-md-4 mb-2">
                            <form action="{{ route('admin.ads.toggleStatus', $ad) }}" method="POST" class="d-inline w-100">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-{{ $ad->is_active ? 'secondary' : 'success' }} btn-block">
                                    <i class="fas fa-{{ $ad->is_active ? 'pause' : 'play' }} mr-2"></i>
                                    {{ $ad->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                        </div>
                        <div class="col-md-4 mb-2">
                            <form action="{{ route('admin.ads.destroy', $ad) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this advertisement? This action cannot be undone.')"
                                  class="d-inline w-100">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-block">
                                    <i class="fas fa-trash mr-2"></i>Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analytics Sidebar -->
        <div class="col-lg-4">
            <!-- Performance Stats -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-chart-line mr-1"></i>
                        Performance Analytics
                    </h6>
                </div>
                <div class="card-body">
                    <!-- Click-Through Rate -->
                    <div class="text-center mb-4">
                        <div class="h2 mb-0">
                            <span class="badge badge-{{ $ad->click_through_rate > 5 ? 'success' : ($ad->click_through_rate > 2 ? 'warning' : 'danger') }} badge-pill p-2">
                                {{ number_format($ad->click_through_rate, 2) }}%
                            </span>
                        </div>
                        <small class="text-muted">Click-Through Rate</small>
                        <div class="mt-2">
                            @if($ad->click_through_rate > 5)
                                <i class="fas fa-thumbs-up text-success mr-1"></i>
                                <small class="text-success">Excellent performance!</small>
                            @elseif($ad->click_through_rate > 2)
                                <i class="fas fa-chart-line text-warning mr-1"></i>
                                <small class="text-warning">Good performance</small>
                            @elseif($ad->click_through_rate > 0)
                                <i class="fas fa-chart-line text-danger mr-1"></i>
                                <small class="text-danger">Needs improvement</small>
                            @else
                                <i class="fas fa-info-circle text-muted mr-1"></i>
                                <small class="text-muted">No data yet</small>
                            @endif
                        </div>
                    </div>

                    <!-- Stats Grid -->
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-right">
                                <div class="h4 font-weight-bold text-primary">
                                    {{ number_format($ad->click_count) }}
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-mouse-pointer mr-1"></i>
                                    Total Clicks
                                </small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="h4 font-weight-bold text-info">
                                {{ number_format($ad->impression_count) }}
                            </div>
                            <small class="text-muted">
                                <i class="fas fa-eye mr-1"></i>
                                Total Impressions
                            </small>
                        </div>
                    </div>

                    <hr>

                    <!-- Performance Insights -->
                    <div class="small">
                        <h6 class="font-weight-bold text-dark mb-2">Performance Insights</h6>
                        
                        @if($ad->impression_count == 0)
                            <div class="alert alert-info py-2 px-3 mb-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                No impressions recorded yet. Ad may not be active or visible.
                            </div>
                        @elseif($ad->click_count == 0)
                            <div class="alert alert-warning py-2 px-3 mb-2">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                Ad has impressions but no clicks. Consider updating content.
                            </div>
                        @elseif($ad->click_through_rate > 5)
                            <div class="alert alert-success py-2 px-3 mb-2">
                                <i class="fas fa-check-circle mr-1"></i>
                                Outstanding CTR! This ad is performing very well.
                            </div>
                        @endif
                        
                        @if($ad->status === 'Expired')
                            <div class="alert alert-secondary py-2 px-3 mb-2">
                                <i class="fas fa-clock mr-1"></i>
                                This ad has expired and is no longer showing.
                            </div>
                        @elseif($ad->status === 'Scheduled')
                            <div class="alert alert-info py-2 px-3 mb-2">
                                <i class="fas fa-calendar mr-1"></i>
                                Ad is scheduled to start {{ $ad->start_date->format('M j, Y') }}.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Ad Information Summary -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-info-circle mr-1"></i>
                        Advertisement Summary
                    </h6>
                </div>
                <div class="card-body">
                    <div class="small">
                        <div class="row mb-2">
                            <div class="col-5 text-muted">ID:</div>
                            <div class="col-7 font-weight-bold">#{{ $ad->id }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-5 text-muted">Type:</div>
                            <div class="col-7">
                                <span class="badge badge-{{ $ad->type === 'banner' ? 'primary' : ($ad->type === 'popup' ? 'info' : ($ad->type === 'inline' ? 'success' : 'warning')) }} badge-sm">
                                    {{ ucfirst($ad->type) }}
                                </span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-5 text-muted">Status:</div>
                            <div class="col-7">
                                <span class="badge badge-{{ $ad->status_color }} badge-sm">{{ $ad->status }}</span>
                            </div>
                        </div>
                        @if($ad->target_url)
                            <div class="row mb-2">
                                <div class="col-5 text-muted">Link:</div>
                                <div class="col-7">
                                    <i class="fas fa-external-link-alt text-primary"></i>
                                    <span class="text-primary">Yes</span>
                                </div>
                            </div>
                        @endif
                        @if($ad->image_url)
                            <div class="row mb-2">
                                <div class="col-5 text-muted">Image:</div>
                                <div class="col-7">
                                    <i class="fas fa-image text-success"></i>
                                    <span class="text-success">Uploaded</span>
                                </div>
                            </div>
                        @endif
                        @if($ad->content_html)
                            <div class="row mb-2">
                                <div class="col-5 text-muted">HTML:</div>
                                <div class="col-7">
                                    <i class="fas fa-code text-warning"></i>
                                    <span class="text-warning">Custom</span>
                                </div>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-5 text-muted">Duration:</div>
                            <div class="col-7">
                                @if(!$ad->start_date && !$ad->end_date)
                                    <i class="fas fa-infinity text-info"></i>
                                    <span class="text-info">Indefinite</span>
                                @else
                                    <i class="fas fa-calendar text-primary"></i>
                                    <span class="text-primary">Scheduled</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection