@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">

        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <span>Vendor Details</span>
                <a href="{{ route('vendors.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
            </div>

            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-4">Vendor Name</dt>
                    <dd class="col-sm-8">{{ $vendor->vendor_name }}</dd>

                    <dt class="col-sm-4">Vendor ID</dt>
                    <dd class="col-sm-8">{{ $vendor->vendor_id }}</dd>

                    <dt class="col-sm-4">Email</dt>
                    <dd class="col-sm-8">{{ $vendor->email ?? '—' }}</dd>

                    <dt class="col-sm-4">Contact Number</dt>
                    <dd class="col-sm-8">{{ $vendor->contact_number }}</dd>

                    <dt class="col-sm-4">Completed Redirect URL</dt>
                    <dd class="col-sm-8">{{ $vendor->completed_redirect_url }}</dd>

                    <dt class="col-sm-4">Terminated Redirect URL</dt>
                    <dd class="col-sm-8">{{ $vendor->terminated_redirect_url }}</dd>

                    <dt class="col-sm-4">Quote Full Redirect URL</dt>
                    <dd class="col-sm-8">{{ $vendor->quote_full_redirect_url }}</dd>
                </dl>
            </div>
        </div>

    </div>
</div>

@endsection
