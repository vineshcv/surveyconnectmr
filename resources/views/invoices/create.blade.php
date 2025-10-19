<!-- @extends('layouts.app')

@section('title', 'Client List')
@section('page-title', 'Client List')
@section('breadcrumb', 'Client List')

@section('content')
   
@endsection -->


@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create Invoice</h2>

    <form method="POST" action="{{ route('invoices.store') }}">
        @csrf

        <div class="mb-3">
            <label for="partner_id" class="form-label">Partner</label>
            <select name="partner_id" class="form-select" required>
                @foreach($partners as $partner)
                    <option value="{{ $partner->id }}">{{ $partner->vendor_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Total Projects</label>
            <input type="number" name="total_projects" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Total Amount</label>
            <input type="number" step="0.01" name="total_amount" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-select" required>
                @foreach(['invoiced', 'paid', 'rejected'] as $status)
                    <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Include GST?</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="include_gst" value="1" checked>
                <label class="form-check-label">Yes</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="include_gst" value="0">
                <label class="form-check-label">No</label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Create</button>
    </form>
</div>
@endsection