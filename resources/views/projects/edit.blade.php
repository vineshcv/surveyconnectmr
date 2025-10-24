@extends('layouts.app')

@section('content')


    <div class="wrap">
    <ul class="nav nav-pills mb-3" id="editTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="project-tab" data-bs-toggle="pill" data-bs-target="#projectDetails" type="button" role="tab">Project Details</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="question-tab" data-bs-toggle="pill" data-bs-target="#questions" type="button" role="tab">Questions</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="participant-tab" data-bs-toggle="pill" data-bs-target="#participants" type="button" role="tab">Participants Progress Report</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="vendor-tab" data-bs-toggle="pill" data-bs-target="#addVendor" type="button" role="tab">Add Vendor</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="vendorreport-tab" data-bs-toggle="pill" data-bs-target="#vendorReport" type="button" role="tab">Vendors Overall Project Report</button>
        </li>
    </ul>
    <div class="tab-content" id="editTabContent">
            <!-- Project Details Tab -->
            <div class="tab-pane fade show active" id="projectDetails" role="tabpanel" aria-labelledby="project-tab">
            <form method="POST" id="projectEditForm" enctype="multipart/form-data" action="{{ route('projects.update', $project->id) }}">
                @csrf
                @method('PUT')

                    <div class="row jumbotron box8 tab_one">
                        <!-- Column 1 -->
                        <div class="col-sm-3 form-group">
                            <label for="name-f">Project Name</label>
                            <input type="text" class="form-control" name="name" id="name-f" placeholder="Project Name." value="{{ old('name', $project->name) }}" required>
                        </div>
                        
                        <div class="col-sm-3 form-group">
                            <label for="loi">LOI</label>
                            <input type="text" class="form-control" name="loi" id="loi" placeholder="LOI" value="{{ old('loi', $project->loi) }}" required>
                        </div>
                        
                        <div class="col-sm-3 form-group">
                            <label for="project_type">Project Type</label>
                            <select id="multiSelectP" class="form-control browser-default custom-select" name="project_type">
                                <option value="single" {{ $project->project_type == 'single' ? 'selected' : '' }}>Single Link</option>
                                <option value="multiple" {{ $project->project_type == 'multiple' ? 'selected' : '' }}>Multiple Link</option>
                                <option value="unique" {{ $project->project_type == 'unique' ? 'selected' : '' }}>Unique Link</option>
                            </select>
                    </div>

                        <!-- Column 2 -->
                        <div class="col-sm-3 form-group">
                            <label for="multiSelect" class="block text-lg font-medium text-gray-700">Select Country:</label>
                            <select id="multiSelect" name="countries[]" multiple class="w-full mt-2">
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" {{ $project->countries->contains($country->id) ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
                    @endforeach
                            </select>
                    </div>

                        <div class="col-sm-3 form-group">
                            <label for="ir">IR</label>
                            <input type="text" class="form-control" name="ir" id="ir" placeholder="IR" value="{{ old('ir', $project->ir) }}" required>
                    </div>

                        <div class="col-sm-3 form-group">
                            <label for="login_type">Login Type</label>
                            <select class="form-control browser-default custom-select" name="login_type_id" id="login_type">
                                <option value="1" {{ $project->login_type_id == 1 ? 'selected' : '' }}>BDE</option>
                                <option value="2" {{ $project->login_type_id == 2 ? 'selected' : '' }}>PM</option>
                                <option value="3" {{ $project->login_type_id == 3 ? 'selected' : '' }}>Admin</option>
                            </select>
                    </div>

                        <!-- Column 3 -->
                        <div class="col-sm-3 form-group">
                            <label for="specifications">Project Specification</label>
                            <input type="text" class="form-control" name="specifications" id="specifications" placeholder="Projects Specification" value="{{ old('specifications', $project->specifications) }}" required>
                    </div>

                        <div class="col-sm-3 form-group">
                            <label for="status">Project Status</label>
                            <select class="form-control browser-default custom-select" name="status" id="status">
                            @foreach(['live', 'pause', 'invoice', 'ir', 'commission', 'cancelled'] as $status)
                                <option value="{{ $status }}" {{ $project->status == $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                        <div class="col-sm-3 form-group">
                            <label for="quota">Quota/Sample Requirements</label>
                            <input type="text" class="form-control" name="quota" id="quota" placeholder="Quota/Sample Requirements" value="{{ old('quota', $project->quota) }}" required>
                        </div>
                        
                        <div class="col-sm-3 form-group">
                            <label for="client_id">Client Name</label>
                            <select class="form-control browser-default custom-select" name="client_id" id="client_id">
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ $project->client_id == $client->id ? 'selected' : '' }}>
                                        {{ $client->client_name }}
                                    </option>
                                @endforeach
                        </select>
                    </div>

                        <!-- Single/Multiple Link URLs -->
                        <div class="col-sm-6 form-group clientLiveURL" style="{{ in_array($project->project_type, ['single', 'multiple']) ? '' : 'display:none;' }}">
                            <label for="client_live_url">Client LIVE URL/LINK</label>
                            <input type="url" class="form-control" name="client_live_url" id="normalCLiveURL" placeholder="Client LIVE URL/LINK" value="{{ old('client_live_url', $clientLiveUrl) }}" {{ in_array($project->project_type, ['single', 'multiple']) ? 'required' : '' }}>
                        </div>
                        
                        <div class="col-sm-6 form-group firstPartyURL" style="{{ in_array($project->project_type, ['single', 'multiple']) ? '' : 'display:none;' }}">
                            <label for="first_party_test_url">First Party test URL</label>
                            <input type="url" class="form-control" name="first_party_test_url" id="normalTLiveURL" placeholder="First Party test URL" value="{{ old('first_party_test_url', $firstPartyTestUrl) }}" {{ in_array($project->project_type, ['single', 'multiple']) ? 'required' : '' }}>
                        </div>
                        
                        
                        <!-- Unique Link CSV Upload -->
                        <div class="col-sm-12 form-group" id="fileUploadSection" style="{{ $project->project_type == 'unique' ? '' : 'display:none;' }}">
                            <label for="fileUpload">Upload New CSV File (optional)</label>
                            <div id="dragAndDropArea" class="border p-3">
                                <p>Drag & Drop your file here or click to upload</p>
                                <input type="file" id="fileUpload" name="csv_file" class="form-control" style="display:none;" accept=".csv">
                                <button type="button" class="btn btn-primary" id="browseBtn">Browse Files</button>
                            </div>
                            <small class="text-muted">CSV should contain 'liveURL' and 'testURL' columns</small>

                        @if(isset($urlList) && $urlList->count())
                            <label class="form-label mt-3">Previously Uploaded URLs:</label>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>URL</th>
                                            <th>Type</th>
                                            <th>Created At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($urlList as $index => $url)
                                            <tr>
                                                <td>{{ ($urlList->currentPage() - 1) * $urlList->perPage() + $index + 1 }}</td>
                                                <td class="text-break">{{ $url->url }}</td>
                                                <td>{{ ucfirst($url->type) }}</td>
                                                <td>{{ $url->created_at->format('Y-m-d H:i') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-end">
                                    {!! $urlList->withQueryString()->links() !!}
                                </div>
                            </div>
                        @endif
                    </div>

                        <!-- Table to show uploaded files -->
                        <div class="table-responsive">
                            <table id="fileTable" class="table" style="display:none;">
                                <thead>
                                    <tr>
                                        <td>File Name</td>
                                        <td>File Size</td>
                                        <td>Action</td>
                                    </tr>
                                </thead>
                                <tbody id="fileTableBody">
                                </tbody>
                            </table>
                </div>

                        <button type="submit" class="btn btn-primary btn-rounded nav_btn">Update Details</button>
                </div>
            </form>
        </div>

            <!-- Questions Tab -->
            <div class="tab-pane fade" id="questions" role="tabpanel" aria-labelledby="question-tab">
            <!-- Assign questions form -->
            <form method="POST" action="{{ route('projects.assignQuestions', $project->id) }}">
                @csrf
                    <div style="max-height: 300px; overflow-y: auto; background-color: #f8f9fa;" class="border rounded p-5 mb-3">
                        @if($questions->count() > 0)
                    @foreach($questions as $question)
                                <div class="form-check mb-2 p-2" style="border-bottom: 1px solid #e9ecef;">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="questions[]"
                                value="{{ $question->id }}"
                                id="questionCheck{{ $question->id }}"
                                {{ $project->questions->pluck('id')->contains($question->id) ? 'checked' : '' }}
                                        style="margin-top: 0.25rem;"
                            >
                                    <label class="form-check-label" for="questionCheck{{ $question->id }}" style="word-wrap: break-word; white-space: normal; margin-left: 0.5rem; font-size: 0.9rem; width: 500px;">
                                {{ $question->question }}
                            </label>
                        </div>
                    @endforeach
                        @else
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> No questions available to assign.
                            </div>
                        @endif
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success">Save Selected Questions</button>
                </div>
            </form>

            <!-- Assigned questions table -->
            @if($project->questions->count())
                <h6 class="mt-4">Assigned Questions:</h6>
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Question</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($project->questions as $index => $q)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $q->question }}</td>
                                <td>{{ ucfirst($q->type) }}</td>
                                <td>{{ $q->status ? 'Active' : 'Inactive' }}</td>
                                <td>
                                    <form method="POST" action="{{ route('projects.removeQuestion', [$project->id, $q->id]) }}" onsubmit="return confirm('Remove this question?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                    <div class="alert alert-info mt-4">
                        <i class="fa fa-info-circle"></i> No questions assigned yet.
                    </div>
            @endif
        </div>

            <!-- Participants Progress Report Tab -->
            <div class="tab-pane fade" id="participants" role="tabpanel" aria-labelledby="participant-tab">
                <div class="row">
                    <!-- Status Summary Cards -->
                    <div class="col-xl-3 col-md-6 col-12">
                        <div class="card _Pcard">
                            <h2>{{ $completeCount }}</h2>
                            <h6>Completed</h6>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 col-12">
                        <div class="card _Pcard">
                            <h2>{{ $quotaFullCount }}</h2>
                            <h6>Quota Full</h6>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 col-12">
                        <div class="card _Pcard">
                            <h2>{{ $terminateCount }}</h2>
                            <h6>Terminated</h6>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 col-12">
                        <div class="card _Pcard">
                            <h2><i class="fa fa-file-excel" aria-hidden="true"></i></h2>
                            <select id="statusFilter" class="dropdown_col form-control browser-default custom-select">
                                <option value="">Filter By Status</option>
                                <option value="1">Complete</option>
                                <option value="2">Terminate</option>
                                <option value="3">Quota Full</option>
                                <option value="5">LOI Fail</option>
                                <option value="7">IP Fail</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 col-12">
                        <div class="card _Pcard">
                            <h2><i class="fa fa-users" aria-hidden="true"></i></h2>
                            <select id="vendorFilter" class="dropdown_col form-control browser-default custom-select">
                                <option value="">Filter By Vendor</option>
                                @foreach($participantVendors as $vendor)
                                    <option value="{{ $vendor->id }}">{{ $vendor->vendor_name }}</option>
                                @endforeach
                            </select>
                            @if(config('app.debug'))
                                <small class="text-muted">Debug: {{ $participantVendors->count() }} vendors found</small>
                            @endif
                        </div>
                    </div>

                    <!-- Participants Table -->
                    <div class="tableWrap">
                        @if($participantsList->count() > 0)
                            <div class="table-responsive">
                                <table id="copy-print-csv" class="table">
                                    <thead>
                                        <tr>
                                            <td>SN</td>
                                            <td>Vendor Name</td>
                                            <td>HASH</td>
                                            <td>TODO</td>
                                            <td>USER ID</td>
                                            <td>Start Time</td>
                                            <td>End Time</td>
                                            <td>LOI</td>
                                            <td>Start IP</td>
                                            <td>End IP</td>
                                            <td>Status</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($participantsList as $index => $participant)
                                            @php
                                                $duration_in_minutes = 0;
                                                if ($participant->start_loi && $participant->end_loi) {
                                                    $duration_in_seconds = $participant->end_loi->diffInSeconds($participant->start_loi);
                                                    $duration_in_minutes = round($duration_in_seconds / 60);
                                                }
                                            @endphp
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $participant->vendor->vendor_name ?? 'N/A' }}</td>
                                                <td>{{ $participant->participant_id ?? 'N/A' }}</td>
                                                <td>{{ $participant->project->name ?? 'N/A' }}</td>
                                                <td>{{ $participant->uid ?? 'N/A' }}</td>
                                                <td>{{ $participant->start_loi ? $participant->start_loi->format('d-m-y h:i:s') : 'N/A' }}</td>
                                                <td>{{ $participant->end_loi ? $participant->end_loi->format('d-m-y h:i:s') : 'N/A' }}</td>
                                                <td>{{ $duration_in_minutes }}</td>
                                                <td>{{ $participant->participant_ip ?? 'N/A' }}</td>
                                                <td>{{ $participant->end_ip ?? 'N/A' }}</td>
                                                <td>
                                                    @switch($participant->status)
                                                        @case(1)
                                                            Completed
                                                            @break
                                                        @case(2)
                                                            Terminate
                                                            @break
                                                        @case(3)
                                                            Quotafull
                                                            @break
                                                        @case(4)
                                                            Security Issue
                                                            @break
                                                        @case(5)
                                                            LOI Fail
                                                            @break
                                                        @case(6)
                                                            IR Count
                                                            @break
                                                        @case(7)
                                                            IP Fail
                                                            @break
                                                        @case(8)
                                                            URL Error
                                                            @break
                                                        @case(9)
                                                            Unknown
                                                            @break
                                                        @case(10)
                                                            Already Participated
                                                            @break
                                                        @default
                                                            Unknown
                                                    @endswitch
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> No participants found for this project yet.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Add Vendor Tab -->
            <div class="tab-pane fade" id="addVendor" role="tabpanel" aria-labelledby="vendor-tab">
                @if(session('success') && session('active_tab') == 'addVendor')
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('error') && session('active_tab') == 'addVendor')
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                <div class="row">
                    <div class="col-xl-6 col-md-6 col-12">
                        <form method="POST" action="{{ route('projects.addQuotaToVendor', $project->id) }}">
                            @csrf
                            <div class="card _Pcard">
                                <select name="vendor_id" class="dropdown_col_full form-control browser-default custom-select" required>
                                    <option value="">Select Vendor</option>
                                    @foreach($vendors as $vendor)
                                        <option value="{{ $vendor->id }}">{{ $vendor->vendor_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                    </div>
                    <div class="col-xl-6 col-md-6 col-12">
                        <div class="card _Pcard">
                            <input type="number" name="quota" id="quotaInput" class="dropdown_col_full form-control" placeholder="Add Quota" min="0" required>
                        </div>
                        <div id="quotaError" class="text-danger mt-1" style="display: none; font-size: 0.875rem;"></div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-rounded nav_btn">Add Vendor</button>
                    </form>
                    
                    <!-- Quota Information -->
                    <div class="mt-3">
                        <small class="text-muted">
                            Project Quota: {{ $project->quota ?? 0 }} | 
                            Distributed: {{ $project->vendorQuotas()->sum('quota_allot') }} | 
                            Remaining: {{ ($project->quota ?? 0) - $project->vendorQuotas()->sum('quota_allot') }}
                        </small>
                    </div>
                    
                    <!-- Assigned Vendors Table -->
                    @if($project->vendorQuotas->count() > 0)
                        <div class="tableWrap mt-4">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <td>SID</td>
                                            <td>Vendor Name</td>
                                            <td>Quota Allotted</td>
                                            <td>Quota Used</td>
                                            <td>Remaining</td>
                                            <td>Action</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($project->vendorQuotas as $index => $quota)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $quota->vendor->vendor_name }}</td>
                                                <td>{{ $quota->quota_allot }}</td>
                                                <td>{{ $quota->quota_used }}</td>
                                                <td>{{ $quota->quota_allot - $quota->quota_used }}</td>
                                                <td>
                                                    <a href="{{ route('projects.editVendorMapping', [$project->id, $quota->vendor->id]) }}" class="btn btn-sm btn-primary">
                                                        <i class="fa fa-edit"></i> Edit
                                                    </a>
                                                    <form method="POST" action="{{ route('projects.removeVendor', [$project->id, $quota->vendor->id]) }}" style="display: inline;" onsubmit="return confirm('Remove this vendor?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="fa fa-trash"></i> Remove
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info mt-4">
                            <i class="fa fa-info-circle"></i> No vendors assigned to this project yet.
                        </div>
                    @endif
                </div>
        </div>

            <!-- Vendors Overall Project Report Tab -->
            <div class="tab-pane fade" id="vendorReport" role="tabpanel" aria-labelledby="vendorreport-tab">
                <div class="row">
                    <!-- Report Cards -->
                    <div class="col-xl-3 col-md-6 col-12">
                        <div class="card _Pcard">
                            <h3><i class="fa fa-check-square text-primary"></i>&nbsp;{{ participants_progress_report($project->id, 1) }}</h3>
                            <h6>Completes</h6>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 col-12">
                        <div class="card _Pcard">
                            <h3><i class="fa fa-level-down text-danger"></i>&nbsp;{{ participants_progress_report($project->id, 2) }}</h3>
                            <h6>Terminates</h6>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 col-12">
                        <div class="card _Pcard">
                            <h3><i class="fa fa-stack-overflow" aria-hidden="true"></i>&nbsp;{{ participants_progress_report($project->id, 3) }}</h3>
                            <h6>Quota Full</h6>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 col-12">
                        <div class="card _Pcard">
                            <h3><i class="fa fa-stack-overflow" aria-hidden="true"></i>&nbsp;{{ participants_progress_report($project->id, 5) }}</h3>
                            <h6>LOI Fail</h6>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 col-12">
                        <div class="card _Pcard">
                            <h3><i class="fa fa-clock text-primary" aria-hidden="true"></i>&nbsp;{{ get_loi($project->id) }}</h3>
                            <h6>LOI</h6>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 col-12">
                        <div class="card _Pcard">
                            <h3><i class="fa fa-clock text-primary" aria-hidden="true"></i>&nbsp;{{ participants_progress_report_avg($project->id, 1)->sumLoi ?? 0 }}</h3>
                            <h6>Average LOI</h6>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 col-12">
                        <div class="card _Pcard">
                            <h3><i class="fa fa-clock text-primary" aria-hidden="true"></i>&nbsp;{{ get_total_project_status($project->id) }}</h3>
                            <h6>IR Count</h6>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 col-12">
                        <div class="card _Pcard">
                            <h3><i class="fa fa-clock text-primary" aria-hidden="true"></i>&nbsp;{{ participants_progress_report($project->id, 7) }}</h3>
                            <h6>IP Fail</h6>
                        </div>
                    </div>
        </div>

                <!-- Vendors List -->
                <div class="row mt-4">
                    <div class="col-12">
                        <h5>Project Vendors</h5>
                        @if($project->vendorQuotas->count() > 0)
                            <div class="tableWrap mt-4">
                                <div class="table-responsive">
                                    <table class="table">
                                    <thead>
                                        <tr>
                                            <td>SID</td>
                                            <td>Vendor Name</td>
                                            <td>Quota Allotted</td>
                                            <td>Quota Used</td>
                                            <td>Remaining</td>
                                            <td>Action</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($project->vendorQuotas as $index => $quota)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <a href="#" class="vendor-link text-primary" data-vendor-id="{{ $quota->vendor->id }}" data-vendor-name="{{ $quota->vendor->vendor_name }}">
                                                        {{ $quota->vendor->vendor_name }}
                                                    </a>
                                                </td>
                                                <td>{{ $quota->quota_allot }}</td>
                                                <td>{{ $quota->quota_used }}</td>
                                                <td>{{ $quota->quota_allot - $quota->quota_used }}</td>
                                                <td>
                                                    <a href="{{ route('projects.editVendorMapping', [$project->id, $quota->vendor->id]) }}" class="btn btn-sm btn-primary">
                                                        <i class="fa fa-edit"></i> Edit Mapping
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> No vendors assigned to this project yet.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('assets/dist/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/dist/js/dataTables.js') }}"></script>
<script src="{{ asset('assets/dist/js/dataTables.bootstrap5.js') }}"></script>

<script>
$(document).ready(function () {
    // Initialize Select2
    $('#multiSelect').select2({
        placeholder: "Select Countries",
        allowClear: true,
        width: '100%'
    });


    // Handle project type changes
    $('#multiSelectP').on('change', function () {
        const selectedValue = $(this).val();
        
        if (selectedValue === 'unique') {
            // Show CSV upload section, hide URL inputs
            $('#fileUploadSection').show();
            $('#fileTable').show();
            $('.clientLiveURL').hide();
            $('.firstPartyURL').hide();
        } else if (selectedValue === 'multiple') {
            // Hide CSV upload, show URL inputs
            $('#fileUploadSection').hide();
            $('#fileTable').hide();
            $('.clientLiveURL').show();
            $('.firstPartyURL').show();
            
            // Generate URL with selected countries
            generateClientLiveURL();
        } else {
            // Single link - show URL inputs, hide CSV
            $('#fileUploadSection').hide();
            $('#fileTable').hide();
            $('.clientLiveURL').show();
            $('.firstPartyURL').show();
        }
    });

    // Function to generate URLs for multiple countries
    function generateClientLiveURL() {
        const selectedCountries = $('#multiSelect').val();
        if (selectedCountries && selectedCountries.length > 0) {
            const countryURL = selectedCountries.join('&');
            // Only set URLs if they are empty, don't override existing URLs
            if (!$('#normalCLiveURL').val()) {
                $('#normalCLiveURL').val(`https://example.com?countries=${countryURL}`);
            }
            if (!$('#normalTLiveURL').val()) {
                $('#normalTLiveURL').val(`https://example.com?countries=${countryURL}`);
            }
        }
    }

    // Handle file upload via drag and drop
    $('#dragAndDropArea').on('dragover', function (e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).css('background-color', '#f8f9fa');
    }).on('dragleave', function (e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).css('background-color', 'white');
    }).on('drop', function (e) {
        e.preventDefault();
        e.stopPropagation();
        const files = e.originalEvent.dataTransfer.files;
        handleFileUpload(files);
    });

    // Browse button click event
    $('#browseBtn').click(function () {
        $('#fileUpload').click();
    });

    // Handle file selection
    $('#fileUpload').on('change', function () {
        const files = this.files;
        handleFileUpload(files);
    });

    // Function to handle file uploads
    function handleFileUpload(files) {
        if (files.length > 0) {
            const file = files[0];
            const tableRow = `<tr>
                <td>${file.name}</td>
                <td>${(file.size / 1024).toFixed(2)} KB</td>
                <td><button class="btn btn-danger btn-sm removeFileBtn">Remove</button></td>
            </tr>`;
            $('#fileTableBody').append(tableRow);
            resetFileInput();
        }
    }

    // Remove file from table
    $(document).on('click', '.removeFileBtn', function () {
        $(this).closest('tr').remove();
    });

    // Reset file input
    function resetFileInput() {
        $('#fileUpload').val('');
        $('#dragAndDropArea').css('background-color', 'white');
    }


    // Tab persistence
    const activeTab = sessionStorage.getItem('activeTab') || 'project-tab';
    $(`#${activeTab}`).tab('show');
    
    $('button[data-bs-toggle="pill"]').on('click', function () {
        const tabId = $(this).attr('id');
        sessionStorage.setItem('activeTab', tabId);
    });

    // Participants filtering
    $('#statusFilter').on('change', function () {
        filterParticipants();
    });
    
    $('#vendorFilter').on('change', function () {
        filterParticipants();
    });
    
    // Vendor link click functionality
    $('.vendor-link').on('click', function(e) {
        e.preventDefault();
        const vendorId = $(this).data('vendor-id');
        const vendorName = $(this).data('vendor-name');
        
        // Set the vendor filter
        $('#vendorFilter').val(vendorId);
        
        // Switch to participants tab
        $('#participant-tab').tab('show');
        
        // Filter participants by vendor
        filterParticipants();
        
        // Show a toast notification
        showToast(`Filtering participants for vendor: ${vendorName}`, 'info');
    });
    
    function showToast(message, type = 'success') {
        // Create a simple toast notification
        const toast = $(`
            <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : type === 'info' ? 'info' : 'primary'} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `);
        
        // Add to body and show
        $('body').append(toast);
        const bsToast = new bootstrap.Toast(toast[0]);
        bsToast.show();
        
        // Remove after hiding
        toast.on('hidden.bs.toast', function() {
            $(this).remove();
        });
    }

    function filterParticipants() {
        const status = $('#statusFilter').val();
        const vendorId = $('#vendorFilter').val();
        
        const url = new URL('{{ route("projects.getParticipantsData", $project->id) }}', window.location.origin);
        if (status) url.searchParams.append('status', status);
        if (vendorId) url.searchParams.append('vendor_id', vendorId);
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                updateParticipantsTable(data.participants);
                updateStatusCards(data);
            })
            .catch(error => console.error('Error:', error));
    }

    function updateParticipantsTable(participants) {
        const tbody = $('#participantsTable tbody');
        tbody.empty();
        
        participants.forEach((participant, index) => {
            const row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>${participant.vendor ? participant.vendor.vendor_name : 'N/A'}</td>
                    <td>${participant.uid || 'N/A'}</td>
                    <td>${new Date(participant.created_at).toLocaleDateString('en-US', {month: 'short', day: '2-digit', year: '2-digit'})}</td>
                    <td>${participant.start_loi ? new Date(participant.start_loi).toLocaleTimeString('en-US', {hour: 'numeric', minute: '2-digit', hour12: true}) : 'N/A'}</td>
                    <td>${participant.end_loi ? new Date(participant.end_loi).toLocaleTimeString('en-US', {hour: 'numeric', minute: '2-digit', hour12: true}) : 'N/A'}</td>
                    <td>${participant.participant_ip || 'N/A'}</td>
                    <td>${participant.loi ? parseFloat(participant.loi).toFixed(2) : '0.00'}</td>
                    <td>${getStatusBadge(participant.status)}</td>
                </tr>
            `;
            tbody.append(row);
        });
    }

    function updateStatusCards(data) {
        $('.card._Pcard h2').eq(0).text(data.completeCount);
        $('.card._Pcard h2').eq(1).text(data.quotaFullCount);
        $('.card._Pcard h2').eq(2).text(data.terminateCount);
    }

    function getStatusBadge(status) {
        const badges = {
            1: '<span class="badge bg-success">Complete</span>',
            2: '<span class="badge bg-danger">Terminate</span>',
            3: '<span class="badge bg-warning">Quota Full</span>',
            5: '<span class="badge bg-secondary">LOI Fail</span>',
            7: '<span class="badge bg-dark">IP Fail</span>'
        };
        return badges[status] || '<span class="badge bg-light text-dark">Unknown</span>';
    }

    // Initialize DataTable
    new DataTable('#participantsTable');
    
    // Quota validation
    const quotaInput = document.getElementById('quotaInput');
    const quotaError = document.getElementById('quotaError');
    const projectQuota = {{ $project->quota ?? 0 }};
    const distributedQuota = {{ $project->vendorQuotas()->sum('quota_allot') }};
    const remainingQuota = projectQuota - distributedQuota;
    
    if (quotaInput) {
        quotaInput.addEventListener('input', function() {
            const inputQuota = parseInt(this.value);
            
            if (inputQuota > remainingQuota) {
                quotaError.innerHTML = `<i class="fa fa-exclamation-triangle"></i> Exceeds remaining quota! Remaining: ${remainingQuota}`;
                quotaError.style.display = 'block';
                this.style.borderColor = '#dc3545';
                this.style.backgroundColor = '#f8d7da';
            } else {
                quotaError.style.display = 'none';
                this.style.borderColor = '';
                this.style.backgroundColor = '';
            }
        });
        
        // Also validate on form submit
        document.querySelector('form[action*="addQuotaToVendor"]').addEventListener('submit', function(e) {
            const inputQuota = parseInt(quotaInput.value);
            
            if (inputQuota > remainingQuota) {
                e.preventDefault();
                quotaError.innerHTML = `<i class="fa fa-exclamation-triangle"></i> Cannot add vendor! Exceeds remaining quota: ${remainingQuota}`;
                quotaError.style.display = 'block';
                quotaInput.style.borderColor = '#dc3545';
                quotaInput.style.backgroundColor = '#f8d7da';
                quotaInput.focus();
            }
        });
    }
});
</script>
@endsection