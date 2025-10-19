@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Edit Vendor</span>
                <a href="{{ route('vendors.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
            </div>

            <div class="card-body">
                <form action="{{ route('vendors.update', $vendor->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3 row">
                        <label for="vendor_name" class="col-md-4 col-form-label text-md-end text-start">Vendor Name</label>
                        <div class="col-md-6">
                            <input type="text" name="vendor_name" class="form-control @error('vendor_name') is-invalid @enderror"
                                   value="{{ old('vendor_name', $vendor->vendor_name) }}">
                            @error('vendor_name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="vendor_id" class="col-md-4 col-form-label text-md-end text-start">Vendor ID</label>
                        <div class="col-md-6">
                            <input type="text" name="vendor_id" class="form-control" value="{{ $vendor->vendor_id }}" readonly>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="email" class="col-md-4 col-form-label text-md-end text-start">Email</label>
                        <div class="col-md-6">
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $vendor->email) }}">
                            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="contact_number" class="col-md-4 col-form-label text-md-end text-start">Contact Number</label>
                        <div class="col-md-6">
                            <input type="text" name="contact_number" class="form-control @error('contact_number') is-invalid @enderror"
                                   value="{{ old('contact_number', $vendor->contact_number) }}">
                            @error('contact_number') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="completed_redirect_url" class="col-md-4 col-form-label text-md-end text-start">Completed Redirect URL</label>
                        <div class="col-md-6">
                            <input type="url" name="completed_redirect_url" class="form-control @error('completed_redirect_url') is-invalid @enderror"
                                   value="{{ old('completed_redirect_url', $vendor->completed_redirect_url) }}">
                            @error('completed_redirect_url') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="terminated_redirect_url" class="col-md-4 col-form-label text-md-end text-start">Terminated Redirect URL</label>
                        <div class="col-md-6">
                            <input type="url" name="terminated_redirect_url" class="form-control @error('terminated_redirect_url') is-invalid @enderror"
                                   value="{{ old('terminated_redirect_url', $vendor->terminated_redirect_url) }}">
                            @error('terminated_redirect_url') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="quote_full_redirect_url" class="col-md-4 col-form-label text-md-end text-start">Quote Full Redirect URL</label>
                        <div class="col-md-6">
                            <input type="url" name="quote_full_redirect_url" class="form-control @error('quote_full_redirect_url') is-invalid @enderror"
                                   value="{{ old('quote_full_redirect_url', $vendor->quote_full_redirect_url) }}">
                            @error('quote_full_redirect_url') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="offset-md-4 col-md-6">
                            <button type="submit" class="btn btn-primary">Update Vendor</button>
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
@endsection
