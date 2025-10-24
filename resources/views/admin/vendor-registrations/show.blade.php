@extends('layouts.app')

@section('content')
<div class="container-fluid">
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="tableWrap">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5>Vendor Registration Details</h5>
            <div class="d-flex align-items-center">
                <span class="badge bg-secondary fs-6 me-3">{!! $vendorRegistration->status_badge !!}</span>
                <a href="{{ route('admin.vendor-registrations.index') }}" class="btn btn-secondary btn-rounded">
                    <i class="fa fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Basic Information -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="text-primary mb-0">
                            <i class="fa fa-info-circle"></i> Basic Information
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label text-md-end text-start">Vendor Name</label>
                            <div class="col-md-8">
                                <p class="form-control-plaintext">{{ $vendorRegistration->vendor_name }}</p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label text-md-end text-start">Company Name</label>
                            <div class="col-md-8">
                                <p class="form-control-plaintext">{{ $vendorRegistration->company_name }}</p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label text-md-end text-start">Email</label>
                            <div class="col-md-8">
                                <p class="form-control-plaintext">{{ $vendorRegistration->email ?: 'Not provided' }}</p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label text-md-end text-start">Contact Number</label>
                            <div class="col-md-8">
                                <p class="form-control-plaintext">{{ $vendorRegistration->contact_number }}</p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label text-md-end text-start">Alternative Contact</label>
                            <div class="col-md-8">
                                <p class="form-control-plaintext">{{ $vendorRegistration->alternative_contact ?: 'Not provided' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="text-primary mb-0">
                            <i class="fa fa-map-marker-alt"></i> Address Information
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label text-md-end text-start">Address Line 1</label>
                            <div class="col-md-8">
                                <p class="form-control-plaintext">{{ $vendorRegistration->address_line_one ?: 'Not provided' }}</p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label text-md-end text-start">Address Line 2</label>
                            <div class="col-md-8">
                                <p class="form-control-plaintext">{{ $vendorRegistration->address_line_two ?: 'Not provided' }}</p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label text-md-end text-start">State</label>
                            <div class="col-md-8">
                                <p class="form-control-plaintext">{{ $vendorRegistration->state ?: 'Not provided' }}</p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label text-md-end text-start">Country</label>
                            <div class="col-md-8">
                                <p class="form-control-plaintext">{{ $vendorRegistration->country ?: 'Not provided' }}</p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-md-4 col-form-label text-md-end text-start">Pincode</label>
                            <div class="col-md-8">
                                <p class="form-control-plaintext">{{ $vendorRegistration->pincode ?: 'Not provided' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Information -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="text-primary mb-0">
                            <i class="fa fa-history"></i> Status Information
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <i class="fa fa-calendar-plus fa-2x text-primary mb-2"></i>
                                        <h6>Submitted</h6>
                                        <p class="mb-0">{{ $vendorRegistration->created_at->format('M d, Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            @if($vendorRegistration->status !== 'pending')
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <i class="fa fa-user-check fa-2x text-success mb-2"></i>
                                            <h6>Processed By</h6>
                                            <p class="mb-0">{{ $vendorRegistration->approved_by ? \App\Models\User::find($vendorRegistration->approved_by)->name : 'System' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <i class="fa fa-clock fa-2x text-info mb-2"></i>
                                            <h6>Processed At</h6>
                                            <p class="mb-0">{{ $vendorRegistration->approved_at ? $vendorRegistration->approved_at->format('M d, Y H:i') : 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rejection Reason -->
        @if($vendorRegistration->status === 'rejected' && $vendorRegistration->rejected_reason)
            <div class="row mt-4">
                <div class="col-12">
                    <div class="alert alert-danger">
                        <h6><i class="fa fa-exclamation-triangle"></i> Rejection Reason</h6>
                        <p class="mb-0">{{ $vendorRegistration->rejected_reason }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Login Credentials (if approved) -->
        @if($vendorRegistration->status === 'approved' && $vendorRegistration->username)
            <div class="row mt-4">
                <div class="col-12">
                    <div class="alert alert-success">
                        <h6><i class="fa fa-key"></i> Login Credentials</h6>
                        <p class="mb-1"><strong>Username:</strong> {{ $vendorRegistration->username }}</p>
                        <p class="mb-0"><strong>Login URL:</strong> <a href="{{ route('vendor.login') }}" target="_blank">{{ route('vendor.login') }}</a></p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="d-flex justify-content-end">
                    @if($vendorRegistration->status === 'pending')
                        <div class="btn-group">
                            @can('approve-vendor-registrations')
                                <button type="button" class="btn btn-success btn-rounded" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#approveModal">
                                    <i class="fa fa-check"></i> Approve
                                </button>
                            @endcan
                            @can('reject-vendor-registrations')
                                <button type="button" class="btn btn-danger btn-rounded" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#rejectModal">
                                    <i class="fa fa-times"></i> Reject
                                </button>
                            @endcan
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa fa-check-circle text-success"></i> Approve Vendor Registration
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.vendor-registrations.approve', $vendorRegistration) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i>
                        <strong>{{ $vendorRegistration->vendor_name }}</strong> will be approved and credentials will be sent via email.
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username *</label>
                        <input type="text" class="form-control" id="username" 
                               name="username" required 
                               value="{{ old('username', strtolower(str_replace(' ', '', $vendorRegistration->vendor_name))) }}">
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
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa fa-times-circle text-danger"></i> Reject Vendor Registration
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.vendor-registrations.reject', $vendorRegistration) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fa fa-exclamation-triangle"></i>
                        <strong>{{ $vendorRegistration->vendor_name }}</strong> will be rejected.
                    </div>
                    <div class="mb-3">
                        <label for="rejected_reason" class="form-label">Rejection Reason *</label>
                        <textarea class="form-control" id="rejected_reason" 
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

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

<script>
$(document).ready(function () {
    // Append modals to body to prevent scrolling issues
    $(function() {
        $("#approveModal").appendTo("body");
    });
    $(function() {
        $("#rejectModal").appendTo("body");
    });
});
</script>
@endsection