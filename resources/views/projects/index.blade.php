
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="tableWrap">
        <h5>List of Projects</h5>

        @can('create-project')
        <button type="button" class="btn btn-primary btn-rounded btnFixed" data-bs-toggle="modal" data-bs-target="#formModal">
            <span>Create new project</span> <i class="fa-solid fa-plus"></i>
        </button>
        @endcan
        <br>

        <div class="table-responsive">
            <table id="example1" class="table">
                <thead>
                    <tr>
                        <td>#</td>
                        <td>Project ID</td>
                        <td>Project Name</td>
                        <td>Client</td>
                        <td>Country</td>
                        <td>Status</td>
                        <td>Quota</td>
                        <td>IR</td>
                        <td>LOI</td>
                        <td>Actions</td>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($projects as $index => $project)
                        <tr>
                            <td>{{ $projects->firstItem() + $index }}</td>
                            <td>{{ $project->projectID }}</td>
                            <td>{{ $project->name }}</td>
                            <td>{{ $project->client->client_name ?? '-' }}</td>
                            <td>{{ $project->countries->pluck('name')->join(', ') }}</td>
                            <td>{{ ucfirst($project->status) }}</td>
                            <td>{{ $project->quota ?? '-' }}</td>
                            <td>{{ $project->ir ?? '-' }}</td>
                            <td>{{ $project->loi ?? '-' }}</td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-warning btn-icon btn-edit"><i class="fa-solid fa-pen-to-square"></i></a>
                                    <form action="{{ route('projects.cloneProject', $project->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Clone this project?')">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-icon">
                                            <i class="fa-solid fa-clone"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('projects.destroy', $project->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this project?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-icon" type="submit">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                    <tr>
                        <td class="text-center text-muted"></td>
                        <td class="text-center text-muted"></td>
                        <td class="text-center text-muted"></td>
                        <td class="text-center text-muted"></td>
                        <td class="text-center text-muted">No projects found.</td>
                        <td class="text-center text-muted"></td>
                        <td class="text-center text-muted"></td>
                        <td class="text-center text-muted"></td>
                        <td class="text-center text-muted"></td>
                        <td class="text-center text-muted"></td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $projects->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<!-- Project Creation Modal -->
<!-- Project Creation Modal -->
<div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" id="projectCreateForm" enctype="multipart/form-data" action="{{ route('projects.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModalLabel">Add New Project</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Project Name -->
                        <div class="col-md-6">
                            <label for="name" class="form-label">Project Name</label>
                            <input type="text" class="form-control" name="name" id="name" required>
                        </div>

                        <!-- Country selection (Single) -->
                        <div class="col-md-6" id="singleCountrySelection">
                            <label for="country" class="form-label">Select Country</label>
                            <select id="country" name="countries[]" class="form-select" required>
                                <option value="">Select Country</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Country selection (Multiple) -->
                        <div class="col-md-6" id="multipleCountrySelection" style="display: none;">
                            <label class="form-label">Select Countries</label>
                            <div class="row">
                                @foreach($countries as $country)
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="countries[]" value="{{ $country->id }}" id="country_{{ $country->id }}">
                                            <label class="form-check-label" for="country_{{ $country->id }}">{{ $country->name }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Project Specification -->
                        <div class="col-md-6">
                            <label for="specifications" class="form-label">Project Specifications</label>
                            <input type="text" class="form-control" name="specifications" id="specifications" required>
                        </div>

                        <!-- Quota -->
                        <div class="col-md-6">
                            <label for="quota" class="form-label">Quota / Sample Requirements</label>
                            <input type="text" class="form-control" name="quota" id="quota" required>
                        </div>

                        <!-- LOI -->
                        <div class="col-md-4">
                            <label for="loi" class="form-label">LOI</label>
                            <input type="text" class="form-control" name="loi" id="loi" required>
                        </div>

                        <!-- IR -->
                        <div class="col-md-4">
                            <label for="ir" class="form-label">IR</label>
                            <input type="text" class="form-control" name="ir" id="ir" required>
                        </div>

                        <!-- Status -->
                        <div class="col-md-4">
                            <label for="status" class="form-label">Project Status</label>
                            <select class="form-select" name="status" id="status" required>
                                <option value="">Choose Project Status</option>
                                <option value="live">Live</option>
                                <option value="pause">Pause</option>
                                <option value="invoice">Invoice</option>
                                <option value="ir">IR</option>
                                <option value="commission">Commission</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>

                        <!-- Client -->
                        <div class="col-md-4">
                            <label for="client_id" class="form-label">Client Name</label>
                            <select class="form-select" name="client_id" id="client_id" required>
                                <option value="">Choose Client</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->client_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Project Type -->
                        <div class="col-md-4">
                            <label for="project_type" class="form-label">Project Type</label>
                            <select class="form-select" name="project_type" id="project_type" required>
                                <option value="">Choose Project Type</option>
                                <option value="single">Single Link</option>
                                <option value="multiple">Multiple Link</option>
                                <option value="unique">Unique Link</option>
                            </select>
                        </div>

                        <!-- Login Type -->
                        <div class="col-md-4">
                            <label for="login_type" class="form-label">Login Type</label>
                            <select class="form-select" name="login_type_id" id="login_type" required>
                                <option value="">Choose Login</option>
                                <option value="1">BDE</option>
                                <option value="2">PM</option>
                                <option value="3">Admin</option>
                            </select>
                        </div>

                        <!-- Client Live URL -->
                        <div class="col-md-12">
                            <label for="client_live_url" class="form-label">Client Live URL / Link</label>
                            <input type="url" class="form-control" name="client_live_url" id="client_live_url" required>
                        </div>

                        <!-- First Party Test URL -->
                        <div class="col-md-12">
                            <label for="first_party_test_url" class="form-label">First Party Test URL</label>
                            <input type="url" class="form-control" name="first_party_test_url" id="first_party_test_url" required>
                        </div>

                        <!-- CSV Upload -->
                        <div class="col-md-12" id="csvUploadSection" style="display: none;">
                            <label for="csv_file" class="form-label">Upload CSV File (for Unique Link)</label>
                            <input type="file" class="form-control" name="csv_file" id="csv_file" accept=".csv">
                            <small class="text-muted">Upload CSV file for unique link projects</small>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">Reset</button>
                    <button type="submit" class="btn btn-primary btn-rounded">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>


@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script>
    $(document).ready(function () {
        // Toggle country selection method based on project type
        $('#project_type').on('change', function () {
            var selectedType = $(this).val();

            if (selectedType === 'multiple') {
                $('#singleCountrySelection').hide();
                $('#multipleCountrySelection').show();
                $('#csvUploadSection').hide();

                $('#client_live_url').closest('.col-md-12').show();
                $('#first_party_test_url').closest('.col-md-12').show();

            } else if (selectedType === 'unique') {
                $('#singleCountrySelection').show();
                $('#multipleCountrySelection').hide();
                $('#csvUploadSection').show();

                $('#client_live_url').closest('.col-md-12').hide();
                $('#first_party_test_url').closest('.col-md-12').hide();

            } else {
                $('#singleCountrySelection').show();
                $('#multipleCountrySelection').hide();
                $('#csvUploadSection').hide();

                $('#client_live_url').closest('.col-md-12').show();
                $('#first_party_test_url').closest('.col-md-12').show();
            }
        });


        // Trigger change on page load to set correct visibility
        $('#project_type').trigger('change');

        

        // jQuery Validation
        $("#projectCreateForm").validate({
            rules: {
                name: {
                    required: true,
                },
                client_id: {
                    required: true,
                },
                project_type: {
                    required: true,
                },
                status: {
                    required: true,
                },
                client_live_url: {
                    required: true,
                    url: true,
                },
                first_party_test_url: {
                    required: true,
                    url: true,
                },
                csv_file: {
                    required: function () {
                        return $('#project_type').val() === 'unique';
                    },
                    extension: "csv"
                }
            },
            messages: {
                name: {
                    required: "Please enter the project name.",
                },
                client_id: {
                    required: "Please select a client.",
                },
                project_type: {
                    required: "Please select the project type.",
                },
                status: {
                    required: "Please select the project status.",
                },
                client_live_url: {
                    required: "Please enter a live URL.",
                    url: "Please enter a valid URL.",
                },
                first_party_test_url: {
                    required: "Please enter a test URL.",
                    url: "Please enter a valid URL.",
                },
                csv_file: {
                    required: "Please upload a CSV file for unique link projects.",
                    extension: "Please upload a valid CSV file.",
                }
            },
            errorElement: 'div',
            errorClass: 'invalid-feedback',
            highlight: function (element) {
                $(element).addClass('is-invalid').removeClass('is-valid');
            },
            unhighlight: function (element) {
                $(element).removeClass('is-invalid').addClass('is-valid');
            },
            errorPlacement: function (error, element) {
                error.insertAfter(element);
            }
        });
    });
</script>
@endsection
