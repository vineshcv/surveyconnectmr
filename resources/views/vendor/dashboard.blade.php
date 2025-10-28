@extends('layouts.vendor-app')

@section('title', 'Vendor Dashboard')

@section('page-title', 'Dashboard')

@section('content')

<!-- Statistics Cards -->
<div class="row">
    <div class="col-xl-3 col-md-6 col-12">
        <div class="card _Pcard">
            <h2>{{ $projects->count() }}</h2>
            <h6>Assigned Projects</h6>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 col-12">
        <div class="card _Pcard">
            <h2>{{ $participants->count() }}</h2>
            <h6>Total Participants</h6>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 col-12">
        <div class="card _Pcard">
            <h2>{{ $participants->where('status', 1)->count() }}</h2>
            <h6>Completed</h6>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 col-12">
        <div class="card _Pcard">
            <h2>{{ $participants->where('status', 5)->count() }}</h2>
            <h6>In Progress</h6>
        </div>
    </div>
</div>

@if($vendor)
<!-- Redirection URLs Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="tableWrap">
            <h5>Redirection URLs</h5>
            
            @if(Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ Session::get('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(Session::has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ Session::get('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('vendor.updateUrls') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="completed_redirect_url" class="form-label">Completed Redirect URL *</label>
                        <input type="url" 
                               class="form-control @error('completed_redirect_url') is-invalid @enderror" 
                               id="completed_redirect_url" 
                               name="completed_redirect_url" 
                               value="{{ old('completed_redirect_url', $vendor->completed_redirect_url) }}" 
                               required>
                        @error('completed_redirect_url')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="terminated_redirect_url" class="form-label">Terminated Redirect URL *</label>
                        <input type="url" 
                               class="form-control @error('terminated_redirect_url') is-invalid @enderror" 
                               id="terminated_redirect_url" 
                               name="terminated_redirect_url" 
                               value="{{ old('terminated_redirect_url', $vendor->terminated_redirect_url) }}" 
                               required>
                        @error('terminated_redirect_url')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="quote_full_redirect_url" class="form-label">Quota Full Redirect URL *</label>
                        <input type="url" 
                               class="form-control @error('quote_full_redirect_url') is-invalid @enderror" 
                               id="quote_full_redirect_url" 
                               name="quote_full_redirect_url" 
                               value="{{ old('quote_full_redirect_url', $vendor->quote_full_redirect_url) }}" 
                               required>
                        @error('quote_full_redirect_url')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="security_full_redirect_url" class="form-label">Security Full Redirect URL</label>
                        <input type="url" 
                               class="form-control @error('security_full_redirect_url') is-invalid @enderror" 
                               id="security_full_redirect_url" 
                               name="security_full_redirect_url" 
                               value="{{ old('security_full_redirect_url', $vendor->security_full_redirect_url) }}">
                        @error('security_full_redirect_url')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <div class="text-end">
                    <button type="submit" class="btn btn-primary btn-rounded">
                        <i class="fa fa-save"></i> Update URLs
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Projects Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="tableWrap">
            <h5>Assigned Projects</h5>
            
            @if($projects->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <td>Project Name</td>
                                <td>Client</td>
                                <td>Quota Allotted</td>
                                <td>Quota Used</td>
                                <td>Status</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($projects as $project)
                                <tr>
                                    <td>
                                        <strong>{{ $project->name }}</strong>
                                        <br><small class="text-muted">{{ $project->projectID }}</small>
                                    </td>
                                    <td>{{ $project->client->name ?? 'N/A' }}</td>
                                    <td>{{ $project->pivot->quota_allot ?? 0 }}</td>
                                    <td>{{ $project->pivot->quota_used ?? 0 }}</td>
                                    <td>
                                        @if($project->status === 'active')
                                            <span class="badge bg-success">Active</span>
                                        @elseif($project->status === 'paused')
                                            <span class="badge bg-warning">Paused</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($project->status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <p class="text-muted">No projects assigned yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Recent Participants -->
<div class="row">
    <div class="col-12">
        <div class="tableWrap">
            <h5>Recent Participants</h5>
            
            @if($participants->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <td>Participant ID</td>
                                <td>Project</td>
                                <td>UID</td>
                                <td>Status</td>
                                <td>Start Time</td>
                                <td>End Time</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($participants as $participant)
                                <tr>
                                    <td>
                                        <code>{{ $participant->participant_id }}</code>
                                    </td>
                                    <td>{{ $participant->project->name ?? 'N/A' }}</td>
                                    <td>{{ $participant->uid }}</td>
                                    <td>
                                        @switch($participant->status)
                                            @case(1)
                                                <span class="badge bg-success">Complete</span>
                                                @break
                                            @case(2)
                                                <span class="badge bg-warning">Terminate</span>
                                                @break
                                            @case(3)
                                                <span class="badge bg-info">Quota Full</span>
                                                @break
                                            @case(4)
                                                <span class="badge bg-danger">Security Full</span>
                                                @break
                                            @case(5)
                                                <span class="badge bg-primary">Started</span>
                                                @break
                                            @case(7)
                                                <span class="badge bg-secondary">IP Fail</span>
                                                @break
                                            @case(10)
                                                <span class="badge bg-dark">Already Participated</span>
                                                @break
                                            @default
                                                <span class="badge bg-light text-dark">Unknown</span>
                                        @endswitch
                                    </td>
                                    <td>{{ $participant->start_loi ? $participant->start_loi->format('M d, Y H:i') : 'N/A' }}</td>
                                    <td>{{ $participant->end_loi ? $participant->end_loi->format('M d, Y H:i') : 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <p class="text-muted">No participants yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
