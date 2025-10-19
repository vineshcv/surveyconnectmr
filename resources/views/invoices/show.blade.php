@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Invoice #{{ $invoice->invoice_number }}</h2>

    <ul class="list-group mb-3">
        <li class="list-group-item"><strong>Partner:</strong> {{ $invoice->partner->name }}</li>
        <li class="list-group-item"><strong>Total Projects:</strong> {{ $invoice->total_projects }}</li>
        <li class="list-group-item"><strong>Total Amount:</strong> ₹{{ $invoice->total_amount }}</li>
        <li class="list-group-item"><strong>GST:</strong> {{ $invoice->include_gst ? 'Included' : ('Excluded (-₹' . $invoice->gst . ')') }}</li>
        <li class="list-group-item"><strong>Status:</strong> {{ ucfirst($invoice->status) }}</li>
        <li class="list-group-item"><strong>Created At:</strong> {{ $invoice->created_at->format('d M Y') }}</li>
    </ul>

    <a href="{{ route('invoices.index') }}" class="btn btn-secondary">Back to List</a>
</div>
@endsection
