@extends('layouts.app')

@section('content')
<div class="container-fluid">
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="tableWrap">
        <!-- Registrations Table -->
        <div class="table-responsive">
            <table id="example1" class="table">
                <thead>
                    <tr>
                        <td>#</td>
                        <td>Vendor Name</td>
                        <td>Company</td>
                        <td>Email</td>
                        <td>Contact</td>
                        <td>Status</td>
                        <td>Submitted</td>
                        <td>Actions</td>
                    </tr>
                </thead>
                <tbody>
                    @forelse($registrations as $index => $registration)
                        <tr>
                            <td>{{ $registrations->firstItem() + $index }}</td>
                            <td>
                                <strong>{{ $registration->vendor_name }}</strong>
                                @if($registration->username)
                                    <br><small class="text-muted">Username: {{ $registration->username }}</small>
                                @endif
                            </td>
                            <td>{{ $registration->company_name }}</td>
                            <td>{{ $registration->email ?: 'N/A' }}</td>
                            <td>{{ $registration->contact_number }}</td>
                            <td>{!! $registration->status_badge !!}</td>
                            <td>{{ $registration->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                <div class="actions">
                                    <button type="button" class="btn btn-info btn-icon btn-show" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#vendorRegistrationShowModal" 
                                            data-id="{{ $registration->id }}">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                    
                                    @if($registration->status === 'pending')
                                        @can('approve-vendor-registrations')
                                            <button type="button" class="btn btn-success btn-icon btn-approve" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#approveModal{{ $registration->id }}">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                        @endcan
                                        @can('reject-vendor-registrations')
                                            <button type="button" class="btn btn-danger btn-icon btn-reject" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#rejectModal{{ $registration->id }}">
                                                <i class="fa-solid fa-times"></i>
                                            </button>
                                        @endcan
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">No vendor registrations found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $registrations->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<!-- Show Registration Modal -->
<div class="modal fade" id="vendorRegistrationShowModal" tabindex="-1" aria-labelledby="showVendorRegistrationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showVendorRegistrationModalLabel">Vendor Registration Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary mb-3">Basic Information</h6>
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label text-md-end text-start">Vendor Name</label>
                            <div class="col-md-8">
                                <p id="show_vendor_name" class="form-control-plaintext"></p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label text-md-end text-start">Company Name</label>
                            <div class="col-md-8">
                                <p id="show_company_name" class="form-control-plaintext"></p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label text-md-end text-start">Email</label>
                            <div class="col-md-8">
                                <p id="show_email" class="form-control-plaintext"></p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label text-md-end text-start">Contact Number</label>
                            <div class="col-md-8">
                                <p id="show_contact_number" class="form-control-plaintext"></p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label text-md-end text-start">Alternative Contact</label>
                            <div class="col-md-8">
                                <p id="show_alternative_contact" class="form-control-plaintext"></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary mb-3">Address Information</h6>
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label text-md-end text-start">Address Line 1</label>
                            <div class="col-md-8">
                                <p id="show_address_line_one" class="form-control-plaintext"></p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label text-md-end text-start">Address Line 2</label>
                            <div class="col-md-8">
                                <p id="show_address_line_two" class="form-control-plaintext"></p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label text-md-end text-start">State</label>
                            <div class="col-md-8">
                                <p id="show_state" class="form-control-plaintext"></p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label text-md-end text-start">Country</label>
                            <div class="col-md-8">
                                <p id="show_country" class="form-control-plaintext"></p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label text-md-end text-start">Pincode</label>
                            <div class="col-md-8">
                                <p id="show_pincode" class="form-control-plaintext"></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-12">
                        <h6 class="text-primary mb-3">Status Information</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <i class="fa fa-calendar-plus fa-2x text-primary mb-2"></i>
                                        <h6>Submitted</h6>
                                        <p class="mb-0" id="show_created_at"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <i class="fa fa-user-check fa-2x text-success mb-2"></i>
                                        <h6>Status</h6>
                                        <p class="mb-0" id="show_status_badge"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <i class="fa fa-clock fa-2x text-info mb-2"></i>
                                        <h6>Processed At</h6>
                                        <p class="mb-0" id="show_approved_at"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="rejection_reason_section" class="row mt-4" style="display: none;">
                    <div class="col-12">
                        <div class="alert alert-danger">
                            <h6><i class="fa fa-exclamation-triangle"></i> Rejection Reason</h6>
                            <p class="mb-0" id="show_rejected_reason"></p>
                        </div>
                    </div>
                </div>

                <div id="credentials_section" class="row mt-4" style="display: none;">
                    <div class="col-12">
                        <div class="alert alert-success">
                            <h6><i class="fa fa-key"></i> Login Credentials</h6>
                            <p class="mb-1"><strong>Username:</strong> <span id="show_username"></span></p>
                            <p class="mb-0"><strong>Login URL:</strong> <a href="{{ route('vendor.login') }}" target="_blank">{{ route('vendor.login') }}</a></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
@foreach($registrations as $registration)
    @if($registration->status === 'pending')
        <div class="modal fade" id="approveModal{{ $registration->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fa fa-check-circle text-success"></i> Approve Vendor Registration
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('admin.vendor-registrations.approve', $registration) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i>
                                <strong>{{ $registration->vendor_name }}</strong> will be approved and credentials will be sent via email.
                            </div>
                            <div class="mb-3">
                                <label for="username{{ $registration->id }}" class="form-label">Username *</label>
                                <input type="text" class="form-control" id="username{{ $registration->id }}" 
                                       name="username" required 
                                       value="{{ old('username', strtolower(str_replace(' ', '', $registration->vendor_name))) }}">
                                <small class="form-text text-muted">This will be used for vendor login</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success btn-rounded">
                                <i class="fa fa-check"></i> Approve & Send Credentials
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Reject Modal -->
        <div class="modal fade" id="rejectModal{{ $registration->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fa fa-times-circle text-danger"></i> Reject Vendor Registration
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('admin.vendor-registrations.reject', $registration) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="alert alert-warning">
                                <i class="fa fa-exclamation-triangle"></i>
                                <strong>{{ $registration->vendor_name }}</strong> will be rejected.
                            </div>
                            <div class="mb-3">
                                <label for="rejected_reason{{ $registration->id }}" class="form-label">Rejection Reason *</label>
                                <textarea class="form-control" id="rejected_reason{{ $registration->id }}" 
                                          name="rejected_reason" rows="3" required 
                                          placeholder="Please provide a reason for rejection...">{{ old('rejected_reason') }}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger btn-rounded">
                                <i class="fa fa-times"></i> Reject Registration
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

<script>
$(document).ready(function () {
    // Append modals to body to prevent scrolling issues
    $(function() {
        $("#vendorRegistrationShowModal").appendTo("body");
    });
    
    @foreach($registrations as $registration)
        @if($registration->status === 'pending')
            $(function() {
                $("#approveModal{{ $registration->id }}").appendTo("body");
            });
            $(function() {
                $("#rejectModal{{ $registration->id }}").appendTo("body");
            });
        @endif
    @endforeach

    // Populate Show Modal with data via AJAX
    $(document).on('click', '.btn-show', function () {
        let registrationId = $(this).data('id');

        $.ajax({
            url: '/admin/vendor-registrations/' + registrationId,
            method: 'GET',
            dataType: 'json',
            success: function (registration) {
                $('#show_vendor_name').text(registration.vendor_name);
                $('#show_company_name').text(registration.company_name);
                $('#show_email').text(registration.email || 'Not provided');
                $('#show_contact_number').text(registration.contact_number);
                $('#show_alternative_contact').text(registration.alternative_contact || 'Not provided');
                $('#show_address_line_one').text(registration.address_line_one || 'Not provided');
                $('#show_address_line_two').text(registration.address_line_two || 'Not provided');
                $('#show_state').text(registration.state || 'Not provided');
                $('#show_country').text(registration.country || 'Not provided');
                $('#show_pincode').text(registration.pincode || 'Not provided');
                $('#show_created_at').text(new Date(registration.created_at).toLocaleDateString());
                $('#show_status_badge').html(registration.status_badge);
                $('#show_approved_at').text(registration.approved_at ? new Date(registration.approved_at).toLocaleDateString() : 'N/A');
                
                // Show/hide sections based on status
                if (registration.status === 'rejected' && registration.rejected_reason) {
                    $('#show_rejected_reason').text(registration.rejected_reason);
                    $('#rejection_reason_section').show();
                    $('#credentials_section').hide();
                } else if (registration.status === 'approved' && registration.username) {
                    $('#show_username').text(registration.username);
                    $('#credentials_section').show();
                    $('#rejection_reason_section').hide();
                } else {
                    $('#credentials_section').hide();
                    $('#rejection_reason_section').hide();
                }
                
                $('#vendorRegistrationShowModal').modal('show');
            },
            error: function () {
                alert('Failed to fetch vendor registration details.');
            }
        });
    });
});
</script>
@endsection