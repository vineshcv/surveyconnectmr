@extends('layouts.app')

@section('content')
<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="tableWrap">

        <!-- Create New Invoice Button -->
        <h5>List of Invoices</h5>
        @can('create-invoice')
        <button type="button" class="btn btn-primary btn-rounded btnFixed" data-bs-toggle="modal" data-bs-target="#invoiceCreateModal">
            <span>Create new invoice</span> <i class="fa-solid fa-plus"></i>
        </button>
        @endcan
        <br>
        <!-- Filters & Search -->
        <!-- <form method="GET" action="{{ route('invoices.index') }}" class="row my-3 g-2 align-items-center">

            <div class="col-md-3">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search Invoice Number">
            </div>

            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">-- Status --</option>
                    <option value="invoiced" {{ request('status') == 'invoiced' ? 'selected' : '' }}>Invoiced</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <div class="col-md-2">
                <select name="year" class="form-select">
                    <option value="">-- Year --</option>
                    @foreach(range(date('Y'), 2010) as $year)
                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <select name="partner_id" class="form-select">
                    <option value="">-- Partner --</option>
                    @foreach($partners as $partner)
                        <option value="{{ $partner->id }}" {{ request('partner_id') == $partner->id ? 'selected' : '' }}>
                            {{ $partner->vendor_name ?? $partner->vendor_name ?? 'Partner ' . $partner->id }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
            </div>
        </form> -->

        <!-- Invoices Table -->
        <div class="table-responsive">
            <table id="invoicesTable" class="table">
                <thead>
                    <tr>
                        <td>#</td>
                        <td>Invoice Number</td>
                        <td>Partner</td>
                        <td>Total Projects</td>
                        <td>Total Amount</td>
                        <td>Include GST</td>
                        <td>GST Amount</td>
                        <td>Status</td>
                        <td>Created At</td>
                        <td>Action</td>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($invoices as $index => $invoice)
                    <tr>
                        <td>{{ $invoices->firstItem() + $index }}</td>
                        <td>{{ $invoice->invoice_number }}</td>
                        <td>{{ $invoice->partner->vendor_name ?? $invoice->partner->vendor_name ?? 'N/A' }}</td>
                        <td>{{ $invoice->total_projects }}</td>
                        <td>{{ number_format($invoice->total_amount, 2) }}</td>
                        <td>{{ $invoice->include_gst ? 'Yes' : 'No' }}</td>
                        <td>{{ number_format($invoice->gst, 2) }}</td>
                        <td>{{ ucfirst($invoice->status) }}</td>
                        <td>{{ $invoice->created_at->format('d-m-Y') }}</td>
                        <td>
                            <div class="actions">
                                <button type="button" class="btn btn-success btn-icon btn-edit" data-id="{{ $invoice->id }}" data-bs-toggle="modal" data-bs-target="#invoiceEditModal"><i class="fa-solid fa-pen-to-square"></i></button>
                                <button type="button" class="btn btn-info btn-icon btn-show" data-id="{{ $invoice->id }}" data-bs-toggle="modal" data-bs-target="#invoiceShowModal"><i class="fa-solid fa-eye"></i></button>
                                <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this invoice?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-icon" type="submit"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="10" class="text-center text-muted">No invoices found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-3">
            {{ $invoices->appends(request()->query())->links() }}
        </div>

    </div>
</div>

<!-- Create Invoice Modal -->
<div class="modal fade" id="invoiceCreateModal" tabindex="-1" aria-labelledby="createInvoiceLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form id="invoiceCreateForm" method="POST" action="{{ route('invoices.store') }}">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="createInvoiceLabel">Add New Invoice</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">

          <div class="row g-3">
            <div class="col-md-6">
              <label for="partner_id" class="form-label">Choose Partner *</label>
              <select class="form-select" name="partner_id" id="partner_id" required>
                <option value="">-- Select Partner --</option>
                @foreach($partners as $partner)
                  <option value="{{ $partner->id }}">{{ $partner->vendor_name ?? $partner->vendor_name ?? 'Partner ' . $partner->id }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-6">
              <label for="total_projects" class="form-label">Total Projects *</label>
              <input type="text" class="form-control" name="total_projects" id="total_projects" required min="1">
            </div>

            <div class="col-md-6">
              <label for="total_amount" class="form-label">Total Amount *</label>
              <input type="text" step="0.01" class="form-control" name="total_amount" id="total_amount" required min="0">
            </div>

            <div class="col-md-6">
              <label for="include_gst" class="form-label">Include GST *</label>
              <select name="include_gst" id="include_gst" class="form-select" required>
                <option value="1" selected>Yes</option>
                <option value="0">No</option>
              </select>
            </div>

            <div class="col-md-6">
              <label for="status" class="form-label">Status *</label>
              <select name="status" id="status" class="form-select" required>
                <option value="invoiced">Invoiced</option>
                <option value="paid">Paid</option>
                <option value="rejected">Rejected</option>
              </select>
            </div>

          </div>

        </div>
        <div class="modal-footer">
          <button type="reset" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">Reset</button>
          <button type="submit" class="btn btn-primary btn-rounded">Save Invoice</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Edit Invoice Modal -->
<div class="modal fade" id="invoiceEditModal" tabindex="-1" aria-labelledby="editInvoiceLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form id="invoiceEditForm" method="POST">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editInvoiceLabel">Edit Invoice</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">

          <div class="row g-3">

            <div class="col-md-6">
              <label for="edit_partner_id" class="form-label">Choose Partner *</label>
              <select class="form-select" name="partner_id" id="edit_partner_id" required>
                <option value="">-- Select Partner --</option>
                @foreach($partners as $partner)
                  <option value="{{ $partner->id }}">{{ $partner->vendor_name ?? $partner->vendor_name ?? 'Partner ' . $partner->id }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-6">
              <label for="edit_total_projects" class="form-label">Total Projects *</label>
              <input type="text" class="form-control" name="total_projects" id="edit_total_projects" required min="1">
            </div>

            <div class="col-md-6">
              <label for="edit_total_amount" class="form-label">Total Amount *</label>
              <input type="text" step="0.01" class="form-control" name="total_amount" id="edit_total_amount" required min="0">
            </div>

            <div class="col-md-6">
              <label for="edit_include_gst" class="form-label">Include GST *</label>
              <select name="include_gst" id="edit_include_gst" class="form-select" required>
                <option value="1">Yes</option>
                <option value="0">No</option>
              </select>
            </div>

            <div class="col-md-6">
              <label for="edit_status" class="form-label">Status *</label>
              <select name="status" id="edit_status" class="form-select" required>
                <option value="invoiced">Invoiced</option>
                <option value="paid">Paid</option>
                <option value="rejected">Rejected</option>
              </select>
            </div>

          </div>

        </div>
        <div class="modal-footer">
          <button type="reset" class="btn btn-secondary btn-rounded" data-bs-dismiss="modal">Reset</button>
          <button type="submit" class="btn btn-primary btn-rounded">Update Invoice</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Show Invoice Modal -->
<div class="modal fade" id="invoiceShowModal" tabindex="-1" aria-labelledby="showInvoiceLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="showInvoiceLabel">Invoice Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">

        <div class="mb-3 row">
          <label class="col-md-4 col-form-label text-md-end text-start">Invoice Number</label>
          <div class="col-md-6">
            <p id="show_invoice_number" class="form-control-plaintext"></p>
          </div>
        </div>

        <div class="mb-3 row">
          <label class="col-md-4 col-form-label text-md-end text-start">Partner</label>
          <div class="col-md-6">
            <p id="show_partner" class="form-control-plaintext"></p>
          </div>
        </div>

        <div class="mb-3 row">
          <label class="col-md-4 col-form-label text-md-end text-start">Total Projects</label>
          <div class="col-md-6">
            <p id="show_total_projects" class="form-control-plaintext"></p>
          </div>
        </div>

        <div class="mb-3 row">
          <label class="col-md-4 col-form-label text-md-end text-start">Total Amount</label>
          <div class="col-md-6">
            <p id="show_total_amount" class="form-control-plaintext"></p>
          </div>
        </div>

        <div class="mb-3 row">
          <label class="col-md-4 col-form-label text-md-end text-start">Include GST</label>
          <div class="col-md-6">
            <p id="show_include_gst" class="form-control-plaintext"></p>
          </div>
        </div>

        <div class="mb-3 row">
          <label class="col-md-4 col-form-label text-md-end text-start">GST Amount</label>
          <div class="col-md-6">
            <p id="show_gst" class="form-control-plaintext"></p>
          </div>
        </div>

        <div class="mb-3 row">
          <label class="col-md-4 col-form-label text-md-end text-start">Status</label>
          <div class="col-md-6">
            <p id="show_status" class="form-control-plaintext"></p>
          </div>
        </div>

        <div class="mb-3 row">
          <label class="col-md-4 col-form-label text-md-end text-start">Created At</label>
          <div class="col-md-6">
            <p id="show_created_at" class="form-control-plaintext"></p>
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
<!-- jQuery Validate (CDN) - jQuery already loaded in main layout -->
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable for invoices table
    new DataTable('#invoicesTable');

  // Edit button click: fetch invoice data and populate edit modal
  $(document).on('click', '.btn-edit', function() {
    var invoiceId = $(this).data('id');

    $.ajax({
        url: '/invoices/' + invoiceId,
        method: 'GET',
        dataType: 'json',
        success: function(invoice) {
            $('#edit_partner_id').val(invoice.partner_id);
            $('#edit_total_projects').val(invoice.total_projects);
            $('#edit_total_amount').val(invoice.total_amount);
            $('#edit_include_gst').val(invoice.include_gst ? '1' : '0');
            $('#edit_status').val(invoice.status);

            $('#invoiceEditForm').attr('action', '/invoices/' + invoiceId);
            $('#invoiceEditModal').modal('show');
        },
        error: function() {
            alert('Unable to fetch invoice data.');
        }
    });
  });

  // Show invoice modal click event
  $(document).on('click', '.btn-show', function() {
      var invoiceId = $(this).data('id');

      $.ajax({
          url: '/invoices/' + invoiceId,
          method: 'GET',
          dataType: 'json',
          success: function(invoice) {
              $('#show_invoice_number').text(invoice.invoice_number);
              $('#show_partner').text(invoice.partner_name ?? invoice.partner?.name ?? 'N/A');
              $('#show_total_projects').text(invoice.total_projects);
              $('#show_total_amount').text(parseFloat(invoice.total_amount).toFixed(2));
              $('#show_include_gst').text(invoice.include_gst ? 'Yes' : 'No');
              $('#show_gst').text(parseFloat(invoice.gst).toFixed(2));
              $('#show_status').text(invoice.status.charAt(0).toUpperCase() + invoice.status.slice(1));
              $('#show_created_at').text(new Date(invoice.created_at).toLocaleDateString());

              $('#invoiceShowModal').modal('show');
          },
          error: function() {
              alert('Failed to fetch invoice details.');
          }
      });
  });

  // jQuery Validation for Create Form
  $('#invoiceCreateForm').validate({
    rules: {
      partner_id: { required: true },
      total_projects: { required: true, digits: true, min: 1 },
      total_amount: { required: true, number: true, min: 0 },
      include_gst: { required: true },
      status: { required: true }
    },
    messages: {
      partner_id: { required: "Please select a partner." },
      total_projects: {
        required: "Please enter total projects.",
        digits: "Please enter a valid number.",
        min: "Total projects must be at least 1."
      },
      total_amount: {
        required: "Please enter total amount.",
        number: "Please enter a valid amount.",
        min: "Total amount cannot be negative."
      },
      include_gst: { required: "Please select if GST is included." },
      status: { required: "Please select a status." }
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

  // jQuery Validation for Edit Form
  $('#invoiceEditForm').validate({
    rules: {
      partner_id: { required: true },
      total_projects: { required: true, digits: true, min: 1 },
      total_amount: { required: true, number: true, min: 0 },
      include_gst: { required: true },
      status: { required: true }
    },
    messages: {
      partner_id: { required: "Please select a partner." },
      total_projects: {
        required: "Please enter total projects.",
        digits: "Please enter a valid number.",
        min: "Total projects must be at least 1."
      },
      total_amount: {
        required: "Please enter total amount.",
        number: "Please enter a valid amount.",
        min: "Total amount cannot be negative."
      },
      include_gst: { required: "Please select if GST is included." },
      status: { required: "Please select a status." }
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
