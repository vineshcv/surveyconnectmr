@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Vendor Details</span>
                <a href="{{ route('vendors.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
            </div>

            <div class="card-body">

                <div class="mb-3 row">
                    <label class="col-md-4 fw-bold">Vendor Name:</label>
                    <div class="col-md-8">{{ $vendor->vendor_name }}</div>
                </div>

                <div class="mb-3 row">
                    <label class="col-md-4 fw-bold">Vendor ID:</label>
                    <div class="col-md-8">{{ $vendor->vendor_id }}</div>
                </div>

                <div class="mb-3 row">
                    <label class="col-md-4 fw-bold">Email:</label>
                    <div class="col-md-8">{{ $vendor->email }}</div>
                </div>

                <div class="mb-3 row">
                    <label class="col-md-4 fw-bold">Contact Number:</label>
                    <div class="col-md-8">{{ $vendor->contact_number }}</div>
                </div>

                <div class="mb-3 row">
                    <label class="col-md-4 fw-bold">Completed Redirect URL:</label>
                    <div class="col-md-8">
                        <a href="{{ $vendor->completed_redirect_url }}" target="_blank">
                            {{ $vendor->completed_redirect_url }}
                        </a>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-md-4 fw-bold">Terminated Redirect URL:</label>
                    <div class="col-md-8">
                        <a href="{{ $vendor->terminated_redirect_url }}" target="_blank">
                            {{ $vendor->terminated_redirect_url }}
                        </a>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-md-4 fw-bold">Quote Full Redirect URL:</label>
                    <div class="col-md-8">
                        <a href="{{ $vendor->quote_full_redirect_url }}" target="_blank">
                            {{ $vendor->quote_full_redirect_url }}
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
