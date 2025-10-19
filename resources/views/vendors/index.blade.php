@extends('layouts.app')

@section('content')
<div class="container-fluid">
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="tableWrap">
        <h5>List of Vendors</h5>
        @can('create-vendor')
        <button type="button" class="btn btn-primary btn-rounded btnFixed" data-bs-toggle="modal" data-bs-target="#vendorCreateModal">
            <span>Create new vendor</span> <i class="fa-solid fa-plus"></i>
        </button>
        @endcan
        <br>

        <div class="table-responsive">
            <table id="example1" class="table">
                <thead>
                    <tr>
                        <td>#</td>
                        <td>Vendor Name</td>
                        <td>Vendor ID</td>
                        <td>Email</td>
                        <td>Contact Number</td>
                        <td>Actions</td>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($vendors as $index => $vendor)
                        <tr>
                            <td>{{ $vendors->firstItem() + $index }}</td>
                            <td>{{ $vendor->vendor_name }}</td>
                            <td>{{ $vendor->vendor_id }}</td>
                            <td>{{ $vendor->email }}</td>
                            <td>{{ $vendor->contact_number }}</td>
                            
                            <td>
                              <div class="actions">
                                  <button type="button" class="btn btn-success btn-icon btn-edit" data-bs-toggle="modal" data-bs-target="#vendorEditModal" data-id="{{ $vendor->id }}"><i class="fa-solid fa-pen-to-square"></i></button>
                                  <button type="button" class="btn btn-info btn-icon btn-show" data-bs-toggle="modal" data-bs-target="#vendorShowModal" data-id="{{ $vendor->id }}"><i class="fa-solid fa-eye"></i></button>
                                  <form action="{{ route('vendors.destroy', $vendor->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this client?')">
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
                            <td colspan="6" class="text-center text-muted">No vendors found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $vendors->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="vendorCreateModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form method="POST" id="vendorCreateForm" action="{{ route('vendors.store') }}">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add New Vendor</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body row">
            @foreach ([
                'vendor_name' => 'Vendor Name',
                'vendor_id' => 'Vendor ID',
                'email' => 'Email',
                'contact_number' => 'Contact Number',
                'completed_redirect_url' => 'Completed Redirect URL',
                'terminated_redirect_url' => 'Terminated Redirect URL',
                'quote_full_redirect_url' => 'Quote Full Redirect URL',
                'security_full_redirect_url' => 'Security Full Redirect URL'
            ] as $field => $label)
                <div class="col-sm-6 mb-3">
                    <label>{{ $label }}{{ str_contains($field, 'url') || $field === 'email' ? '' : ' *' }}</label>
                    <input type="{{ str_contains($field, 'url') ? 'url' : ($field === 'email' ? 'email' : 'text') }}"
                        class="form-control"
                        name="{{ $field }}"
                        id="create_{{ $field }}"
                        required="{{ in_array($field, ['vendor_name', 'vendor_id', 'contact_number', 'completed_redirect_url', 'terminated_redirect_url', 'quote_full_redirect_url', 'security_full_redirect_url']) ? 'true' : '' }}">
                </div>
            @endforeach
        </div>
        <div class="modal-footer">
          <button type="reset" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">Reset</button>
          <button type="submit" class="btn btn-primary btn-rounded">Save Vendor</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="vendorEditModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form method="POST" id="editVendorForm">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Vendor</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body row">
            @foreach ([
                'vendor_name' => 'Vendor Name',
                'vendor_id' => 'Vendor ID',
                'email' => 'Email',
                'contact_number' => 'Contact Number',
                'completed_redirect_url' => 'Completed Redirect URL',
                'terminated_redirect_url' => 'Terminated Redirect URL',
                'quote_full_redirect_url' => 'Quote Full Redirect URL',
                'security_full_redirect_url' => 'Security Full Redirect URL'
            ] as $field => $label)
                <div class="col-sm-6 mb-3">
                    <label>{{ $label }}{{ str_contains($field, 'url') || $field === 'email' ? '' : ' *' }}</label>
                    <input type="{{ str_contains($field, 'url') ? 'url' : ($field === 'email' ? 'email' : 'text') }}"
                        class="form-control"
                        name="{{ $field }}"
                        id="edit_{{ $field }}"
                        required="{{ in_array($field, ['vendor_name', 'vendor_id', 'contact_number', 'completed_redirect_url', 'terminated_redirect_url', 'quote_full_redirect_url', 'security_full_redirect_url']) ? 'true' : '' }}">
                </div>
            @endforeach
        </div>
        <div class="modal-footer">
          <button type="reset" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">Reset</button>
          <button type="submit" class="btn btn-primary btn-rounded">Update Vendor</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Show Vendor Modal -->
<div class="modal fade" id="vendorShowModal" tabindex="-1" aria-labelledby="showVendorModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="showVendorModalLabel">Vendor Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        @foreach ([
            'vendor_name' => 'Vendor Name',
            'vendor_id' => 'Vendor ID',
            'email' => 'Email',
            'contact_number' => 'Contact Number',
            'completed_redirect_url' => 'Completed Redirect URL',
            'terminated_redirect_url' => 'Terminated Redirect URL',
            'quote_full_redirect_url' => 'Quote Full Redirect URL',
            'security_full_redirect_url' => 'Security Full Redirect URL'
        ] as $field => $label)
        <div class="mb-3 row">
          <label class="col-md-4 col-form-label text-md-end text-start">{{ $label }}</label>
          <div class="col-md-6">
            <p id="show_{{ $field }}" class="form-control-plaintext"></p>
          </div>
        </div>
        @endforeach
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

<script>
$(document).ready(function () {
    // Populate Edit Modal with data via AJAX
    $(document).on('click', '.btn-edit', function () {
        let vendorId = $(this).data('id');
        $.ajax({
            url: '/vendors/' + vendorId,
            method: 'GET',
            dataType: 'json',
            success: function (vendor) {
                $('#edit_vendor_name').val(vendor.vendor_name);
                $('#edit_vendor_id').val(vendor.vendor_id);
                $('#edit_email').val(vendor.email);
                $('#edit_contact_number').val(vendor.contact_number);
                $('#edit_completed_redirect_url').val(vendor.completed_redirect_url);
                $('#edit_terminated_redirect_url').val(vendor.terminated_redirect_url);
                $('#edit_quote_full_redirect_url').val(vendor.quote_full_redirect_url);
                $('#edit_security_full_redirect_url').val(vendor.security_full_redirect_url);
                $('#editVendorForm').attr('action', '/vendors/' + vendorId);
                $('#vendorEditModal').modal('show');
            },
            error: function () {
                alert('Unable to fetch vendor data.');
            }
        });
    });

    // Populate Show Modal with data via AJAX
    $(document).on('click', '.btn-show', function () {
        let vendorId = $(this).data('id');

        $.ajax({
            url: '/vendors/' + vendorId,
            method: 'GET',
            dataType: 'json',
            success: function (vendor) {
                $('#show_vendor_name').text(vendor.vendor_name);
                $('#show_vendor_id').text(vendor.vendor_id);
                $('#show_email').text(vendor.email);
                $('#show_contact_number').text(vendor.contact_number);
                $('#show_completed_redirect_url').text(vendor.completed_redirect_url);
                $('#show_terminated_redirect_url').text(vendor.terminated_redirect_url);
                $('#show_quote_full_redirect_url').text(vendor.quote_full_redirect_url);
                $('#show_security_full_redirect_url').text(vendor.security_full_redirect_url);
                $('#vendorShowModal').modal('show');
            },
            error: function () {
                alert('Failed to fetch vendor details.');
            }
        });
    });

    // jQuery Validation for Create Vendor form
    $('#vendorCreateForm').validate({
        rules: {
            vendor_name: { required: true },
            vendor_id: { required: true },
            email: { required: true, email: true },
            contact_number: { required: true },
            completed_redirect_url: { required: true, url: true },
            terminated_redirect_url: { required: true, url: true },
            quote_full_redirect_url: { required: true, url: true },
            security_full_redirect_url: { required: true, url: true }
        },
        messages: {
            vendor_name: { required: "Please enter the vendor name." },
            vendor_id: { required: "Please enter the vendor ID." },
            email: { required: "Please enter an email.", email: "Please enter a valid email address." },
            contact_number: { required: "Please enter a contact number." },
            completed_redirect_url: { required: "Please enter the completed redirect URL.", url: "Please enter a valid URL." },
            terminated_redirect_url: { required: "Please enter the terminated redirect URL.", url: "Please enter a valid URL." },
            quote_full_redirect_url: { required: "Please enter the quote full redirect URL.", url: "Please enter a valid URL." },
            security_full_redirect_url: { required: "Please enter the security full redirect URL.", url: "Please enter a valid URL." }
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

    // jQuery Validation for Edit Vendor form
    $('#editVendorForm').validate({
        rules: {
            vendor_name: { required: true },
            vendor_id: { required: true },
            email: { required: true, email: true },
            contact_number: { required: true },
            completed_redirect_url: { required: true, url: true },
            terminated_redirect_url: { required: true, url: true },
            quote_full_redirect_url: { required: true, url: true },
            security_full_redirect_url: { required: true, url: true }
        },
        messages: {
            vendor_name: { required: "Please enter the vendor name." },
            vendor_id: { required: "Please enter the vendor ID." },
            email: { required: "Please enter an email.", email: "Please enter a valid email address." },
            contact_number: { required: "Please enter a contact number." },
            completed_redirect_url: { required: "Please enter the completed redirect URL.", url: "Please enter a valid URL." },
            terminated_redirect_url: { required: "Please enter the terminated redirect URL.", url: "Please enter a valid URL." },
            quote_full_redirect_url: { required: "Please enter the quote full redirect URL.", url: "Please enter a valid URL." },
            security_full_redirect_url: { required: "Please enter the security full redirect URL.", url: "Please enter a valid URL." }
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
