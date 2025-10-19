@extends('layouts.app')
<style>
    .form-check-label{
        width: 1200px;
    }
</style>
@section('content')
<div class="container-fluid">
    <h4>Edit Project</h4>

    <!-- Bootstrap Nav Tabs -->
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

        <!-- Project Details Form Tab -->
        <div class="tab-pane fade show active" id="projectDetails" role="tabpanel">
            <form method="POST" id="projectEditForm" enctype="multipart/form-data" action="{{ route('projects.update', $project->id) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    <!-- Project Name -->
                    <div class="col-md-4">
                        <label class="form-label">Project Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $project->name) }}" required>
                    </div>

                    <!-- Hidden fields for disabled selects -->
                    <input type="hidden" name="client_id" value="{{ $project->client_id }}">
                    <input type="hidden" name="project_type" value="{{ $project->project_type }}">
                    @foreach($project->countries as $country)
                        <input type="hidden" name="countries[]" value="{{ $country->id }}">
                    @endforeach

                    <!-- Specification -->
                    <div class="col-md-4">
                        <label class="form-label">Project Specifications</label>
                        <input type="text" name="specifications" class="form-control" value="{{ old('specifications', $project->specifications) }}" required>
                    </div>

                    <!-- Quota -->
                    <div class="col-md-4">
                        <label class="form-label">Quota / Sample Requirements</label>
                        <input type="text" name="quota" class="form-control" value="{{ old('quota', $project->quota) }}" required>
                    </div>

                    <!-- LOI -->
                    <div class="col-md-4">
                        <label class="form-label">LOI</label>
                        <input type="text" name="loi" class="form-control" value="{{ old('loi', $project->loi) }}" required>
                    </div>

                    <!-- IR -->
                    <div class="col-md-4">
                        <label class="form-label">IR</label>
                        <input type="text" name="ir" class="form-control" value="{{ old('ir', $project->ir) }}" required>
                    </div>

                    <!-- Status -->
                    <div class="col-md-4">
                        <label class="form-label">Project Status</label>
                        <select name="status" class="form-select" required>
                            @foreach(['live', 'pause', 'invoice', 'ir', 'commission', 'cancelled'] as $status)
                                <option value="{{ $status }}" {{ $project->status == $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Login Type -->
                    <div class="col-md-4">
                        <label class="form-label">Login Type</label>
                        <select name="login_type_id" class="form-select" required>
                            <option value="1" {{ $project->login_type_id == 1 ? 'selected' : '' }}>BDE</option>
                            <option value="2" {{ $project->login_type_id == 2 ? 'selected' : '' }}>PM</option>
                            <option value="3" {{ $project->login_type_id == 3 ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>

                    <!-- CSV Upload Section -->
                    <div class="col-md-12">
                        <label class="form-label">Upload New CSV File (optional)</label>
                        <input type="file" name="csv_file" class="form-control mb-3" accept=".csv">

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

                </div>

                <div class="mt-3 text-end">
                    <button type="submit" class="btn btn-primary btn-rounded">Update</button>
                </div>
            </form>
        </div>

        <div class="tab-pane fade" id="questions" role="tabpanel">
            

            <!-- Assign questions form -->
            <form method="POST" action="{{ route('projects.assignQuestions', $project->id) }}">
                @csrf
                <div style="max-height: 300px; overflow-y: auto;" class="border rounded p-3 mb-3">
                    @foreach($questions as $question)
                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="questions[]"
                                value="{{ $question->id }}"
                                id="questionCheck{{ $question->id }}"
                                {{ $project->questions->pluck('id')->contains($question->id) ? 'checked' : '' }}
                            >
                            <label class="form-check-label" for="questionCheck{{ $question->id }}">
                                {{ $question->question }}
                            </label>
                        </div>
                    @endforeach
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
                <p class="text-muted mt-2">No questions assigned yet.</p>
            @endif
        </div>



        <div class="tab-pane fade" id="participants" role="tabpanel">
            <h5>Participants Progress Report (Placeholder)</h5>
        </div>

        <div class="tab-pane fade" id="addVendor" role="tabpanel">
            <h5>Add Vendor (Placeholder)</h5>
        </div>

        <div class="tab-pane fade" id="vendorReport" role="tabpanel">
            <h5>Vendors Overall Project Report (Placeholder)</h5>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- No scripts needed for toggle -->
@endsection
