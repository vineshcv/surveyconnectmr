@extends('layouts.app')

@section('content')
<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="tableWrap">
        <!-- Search form -->
        <!-- <form method="GET" action="{{ route('clients.index') }}" class="row my-3 g-2">
            <div class="col-md-2">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by name, email, mobile...">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">Search</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
            <div class="col-md-1">
                <a href="{{ route('clients.export.pdf') }}" class="btn btn-sm btn-secondary">Export PDF</a>
            </div>
            <div class="col-md-1">
                <a href="{{ route('clients.export.csv') }}" class="btn btn-sm btn-outline-secondary">Export CSV</a>
            </div>
        </form> -->
        <!-- Create New Client Button -->
        <h5>List of Clients</h5>
        @can('create-client')
        <button type="button" class="btn btn-primary btn-rounded btnFixed" data-bs-toggle="modal" data-bs-target="#clientCreateModal">
            <span>Create new client</span> <i class="fa-solid fa-plus"></i>
        </button>
        @endcan
        <br>

        <!-- Clients Table -->
        <div class="table-responsive">
            <table id="example1" class="table">
                <thead>
                    <tr>
                        <td>#</td>
                        <td>Client Name</td>
                        <td>Email ID</td>
                        <td>Contact Number</td>
                        <td>Company Name</td>
                        <td>State</td>
                        <td>Country</td>
                        <td>Action</td>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($clients as $index => $client)
                    <tr>
                        <td>{{ $clients->firstItem() + $index }}</td>
                        <td>{{ $client->client_name }}</td>
                        <td>{{ $client->email }}</td>
                        <td>{{ $client->mobile_number }}</td>
                        <td>{{ $client->company_name }}</td>
                        <td>{{ $client->state }}</td>
                        <td>{{ $client->country }}</td>
                        <td>
                            <div class="actions">
                                <button type="button" class="btn btn-success btn-icon btn-edit" data-bs-toggle="modal" data-bs-target="#clientEditModal" data-id="{{ $client->id }}"><i class="fa-solid fa-pen-to-square"></i></button>
                                <button type="button" class="btn btn-info btn-icon btn-show" data-bs-toggle="modal" data-bs-target="#clientShowModal" data-id="{{ $client->id }}"><i class="fa-solid fa-eye"></i></button>
                                <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this client?')">
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
                    <tr><td colspan="8" class="text-center text-muted">No clients found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-3">
            <!-- {{ $clients->appends(request()->query())->links() }} -->
        </div>

    </div>
</div>

<!-- Create Client Modal -->
<div class="modal fade" id="clientCreateModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form id="clientForm" method="POST" action="{{ route('clients.store') }}">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="formModalLabel">Add New Client</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">

          <div class="row jumbotron box8">

            <div class="col-sm-6 form-group">
              <label for="client_name">Name *</label>
              <input type="text" class="form-control" name="client_name" id="client_name" required>
              <span class="text-danger error-text client_name_error"></span>
            </div>

            <div class="col-sm-6 form-group">
              <label for="email">E-mail Id</label>
              <input type="email" class="form-control" name="email" id="email">
              <span class="text-danger error-text email_error"></span>
            </div>

            <div class="col-sm-6 form-group">
              <label for="mobile_number">Mobile Number</label>
              <input type="text" class="form-control" name="mobile_number" id="mobile_number" required>
              <span class="text-danger error-text mobile_number_error"></span>
            </div>

            <div class="col-sm-6 form-group">
              <label for="alternate_contact">Alternate Contact</label>
              <input type="text" class="form-control" name="alternate_contact" id="alternate_contact">
              <span class="text-danger error-text alternate_contact_error"></span>
            </div>

            <div class="col-sm-6 form-group">
              <label for="company_name">Company Name *</label>
              <input type="text" class="form-control" name="company_name" id="company_name" required>
              <span class="text-danger error-text company_name_error"></span>
            </div>

            <div class="col-sm-6 form-group">
              <label for="address1">Address Line One</label>
              <input type="text" class="form-control" name="address1" id="address1">
              <span class="text-danger error-text address1_error"></span>
            </div>

            <div class="col-sm-6 form-group">
              <label for="address2">Address Line Two</label>
              <input type="text" class="form-control" name="address2" id="address2">
              <span class="text-danger error-text address2_error"></span>
            </div>

            <div class="col-sm-6 form-group">
              <label for="state">State</label>
              <input type="text" class="form-control" name="state" id="state">
              <span class="text-danger error-text state_error"></span>
            </div>

            <div class="col-sm-6 form-group">
              <label for="country">Country</label>
              <input type="text" class="form-control" name="country" id="country">
              <span class="text-danger error-text country_error"></span>
            </div>

            <div class="col-sm-6 form-group">
              <label for="pincode">Pincode</label>
              <input type="text" class="form-control" name="pincode" id="pincode">
              <span class="text-danger error-text pincode_error"></span>
            </div>

            <div class="col-sm-12 form-group">
              <div class="form-check">
                <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                <label for="terms" class="form-check-label">I accept all terms and conditions.</label>
                <span class="text-danger error-text terms_error"></span>
              </div>
            </div>

          </div>

        </div>
        <div class="modal-footer">
          <button type="reset" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">Reset</button>
          <button type="submit" class="btn btn-primary btn-rounded">Save Client</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Edit Client Modal -->
<div class="modal fade" id="clientEditModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form id="editClientForm" method="POST">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Edit Client</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">

          <div class="row jumbotron box8">

            <div class="col-sm-6 form-group">
              <label for="edit_client_name">Name *</label>
              <input type="text" class="form-control" name="client_name" id="edit_client_name" required>
              <span class="text-danger error-text client_name_error"></span>
            </div>

            <div class="col-sm-6 form-group">
              <label for="edit_email">E-mail Id</label>
              <input type="email" class="form-control" name="email" id="edit_email">
              <span class="text-danger error-text email_error"></span>
            </div>

            <div class="col-sm-6 form-group">
              <label for="edit_mobile_number">Mobile Number</label>
              <input type="text" class="form-control" name="mobile_number" id="edit_mobile_number" required>
              <span class="text-danger error-text mobile_number_error"></span>
            </div>

            <div class="col-sm-6 form-group">
              <label for="edit_alternate_contact">Alternate Contact</label>
              <input type="text" class="form-control" name="alternate_contact" id="edit_alternate_contact">
              <span class="text-danger error-text alternate_contact_error"></span>
            </div>

            <div class="col-sm-6 form-group">
              <label for="edit_company_name">Company Name *</label>
              <input type="text" class="form-control" name="company_name" id="edit_company_name" required>
              <span class="text-danger error-text company_name_error"></span>
            </div>

            <div class="col-sm-6 form-group">
              <label for="edit_address1">Address Line One</label>
              <input type="text" class="form-control" name="address1" id="edit_address1">
              <span class="text-danger error-text address1_error"></span>
            </div>

            <div class="col-sm-6 form-group">
              <label for="edit_address2">Address Line Two</label>
              <input type="text" class="form-control" name="address2" id="edit_address2">
              <span class="text-danger error-text address2_error"></span>
            </div>

            <div class="col-sm-6 form-group">
              <label for="edit_state">State</label>
              <input type="text" class="form-control" name="state" id="edit_state">
              <span class="text-danger error-text state_error"></span>
            </div>

            <div class="col-sm-6 form-group">
              <label for="edit_country">Country</label>
              <input type="text" class="form-control" name="country" id="edit_country">
              <span class="text-danger error-text country_error"></span>
            </div>

            <div class="col-sm-6 form-group">
              <label for="edit_pincode">Pincode</label>
              <input type="text" class="form-control" name="pincode" id="edit_pincode">
              <span class="text-danger error-text pincode_error"></span>
            </div>

          </div>

        </div>
        <div class="modal-footer">
          <button type="reset" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">Reset</button>
          <button type="submit" class="btn btn-primary btn-rounded">Update Client</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Show Client Modal -->
<div class="modal fade" id="clientShowModal" tabindex="-1" aria-labelledby="showModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="showModalLabel">Client Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">

        <div class="mb-3 row">
          <label class="col-md-4 col-form-label text-md-end text-start">Name</label>
          <div class="col-md-6">
            <p id="show_client_name" class="form-control-plaintext"></p>
          </div>
        </div>

        <div class="mb-3 row">
          <label class="col-md-4 col-form-label text-md-end text-start">Email</label>
          <div class="col-md-6">
            <p id="show_email" class="form-control-plaintext"></p>
          </div>
        </div>

        <div class="mb-3 row">
          <label class="col-md-4 col-form-label text-md-end text-start">Mobile Number</label>
          <div class="col-md-6">
            <p id="show_mobile_number" class="form-control-plaintext"></p>
          </div>
        </div>

        <div class="mb-3 row">
          <label class="col-md-4 col-form-label text-md-end text-start">Alternate Contact</label>
          <div class="col-md-6">
            <p id="show_alternate_contact" class="form-control-plaintext"></p>
          </div>
        </div>

        <div class="mb-3 row">
          <label class="col-md-4 col-form-label text-md-end text-start">Company Name</label>
          <div class="col-md-6">
            <p id="show_company_name" class="form-control-plaintext"></p>
          </div>
        </div>

        <div class="mb-3 row">
          <label class="col-md-4 col-form-label text-md-end text-start">Address Line One</label>
          <div class="col-md-6">
            <p id="show_address1" class="form-control-plaintext"></p>
          </div>
        </div>

        <div class="mb-3 row">
          <label class="col-md-4 col-form-label text-md-end text-start">Address Line Two</label>
          <div class="col-md-6">
            <p id="show_address2" class="form-control-plaintext"></p>
          </div>
        </div>

        <div class="mb-3 row">
          <label class="col-md-4 col-form-label text-md-end text-start">State</label>
          <div class="col-md-6">
            <p id="show_state" class="form-control-plaintext"></p>
          </div>
        </div>

        <div class="mb-3 row">
          <label class="col-md-4 col-form-label text-md-end text-start">Country</label>
          <div class="col-md-6">
            <p id="show_country" class="form-control-plaintext"></p>
          </div>
        </div>

        <div class="mb-3 row">
          <label class="col-md-4 col-form-label text-md-end text-start">Pincode</label>
          <div class="col-md-6">
            <p id="show_pincode" class="form-control-plaintext"></p>
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

<script>
$(document).ready(function() {

  // Edit button click: fetch client data and populate edit modal
  $(document).on('click', '.btn-edit', function() {
    var clientId = $(this).data('id');

    $.ajax({
        url: '/clients/' + clientId,
        method: 'GET',
        dataType: 'json',
        success: function(client) {
            $('#edit_client_name').val(client.client_name);
            $('#edit_email').val(client.email);
            $('#edit_mobile_number').val(client.mobile_number);
            $('#edit_alternate_contact').val(client.alternate_contact);
            $('#edit_company_name').val(client.company_name);
            $('#edit_address1').val(client.address1);
            $('#edit_address2').val(client.address2);
            $('#edit_state').val(client.state);
            $('#edit_country').val(client.country);
            $('#edit_pincode').val(client.pincode);

            $('#editClientForm').attr('action', '/clients/' + clientId);
            $('#clientEditModal').modal('show');
        },
        error: function() {
            alert('Unable to fetch client data.');
        }
    });
  });

  // Show client modal click event
  $(document).on('click', '.btn-show', function() {
      var clientId = $(this).data('id');

      $.ajax({
          url: '/clients/' + clientId,
          method: 'GET',
          dataType: 'json',
          success: function(client) {
              $('#show_client_name').text(client.client_name);
              $('#show_email').text(client.email);
              $('#show_mobile_number').text(client.mobile_number);
              $('#show_alternate_contact').text(client.alternate_contact);
              $('#show_company_name').text(client.company_name);
              $('#show_address1').text(client.address1);
              $('#show_address2').text(client.address2);
              $('#show_state').text(client.state);
              $('#show_country').text(client.country);
              $('#show_pincode').text(client.pincode);

              $('#clientShowModal').modal('show');
          },
          error: function() {
              alert('Failed to fetch client details.');
          }
      });
  });

  // jQuery Validation for Create Client form
  $('#clientForm').validate({
    rules: {
      client_name: { required: true },
      email: { email: true },
      mobile_number: { required: true },
      company_name: { required: true },
      terms: { required: true }
    },
    messages: {
      client_name: { required: "Please enter client name." },
      email: { email: "Please enter a valid email address." },
      mobile_number: { required: "Please enter mobile number." },
      company_name: { required: "Please enter company name." },
      terms: { required: "You must accept the terms and conditions." }
    },
    errorElement: 'div',
    errorClass: 'invalid-feedback',
    highlight: function(element) {
      $(element).addClass('is-invalid').removeClass('is-valid');
    },
    unhighlight: function(element) {
      $(element).removeClass('is-invalid').addClass('is-valid');
    },
    errorPlacement: function(error, element) {
      if (element.attr("type") == "checkbox") {
        error.insertAfter(element.closest('.form-check'));
      } else {
        error.insertAfter(element);
      }
    }
  });

  // jQuery Validation for Edit Client form
  $('#editClientForm').validate({
    rules: {
      client_name: { required: true },
      email: { email: true },
      mobile_number: { required: true },
      company_name: { required: true }
    },
    messages: {
      client_name: { required: "Please enter client name." },
      email: { email: "Please enter a valid email address." },
      mobile_number: { required: "Please enter mobile number." },
      company_name: { required: "Please enter company name." }
    },
    errorElement: 'div',
    errorClass: 'invalid-feedback',
    highlight: function(element) {
      $(element).addClass('is-invalid').removeClass('is-valid');
    },
    unhighlight: function(element) {
      $(element).removeClass('is-invalid').addClass('is-valid');
    },
    errorPlacement: function(error, element) {
      error.insertAfter(element);
    }
  });

});
</script>
@endsection
