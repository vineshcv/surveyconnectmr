@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-10">

        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    Add New Vendor
                </div>
                <div class="float-end">
                    <a href="{{ route('vendors.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('vendors.store') }}" method="post">
                    @csrf

                    <div class="mb-3 row">
                        <label for="vendor_name" class="col-md-4 col-form-label text-md-end text-start">Vendor Name</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control @error('vendor_name') is-invalid @enderror" id="vendor_name" name="vendor_name" value="{{ old('vendor_name') }}">
                            @error('vendor_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="email" class="col-md-4 col-form-label text-md-end text-start">Email</label>
                        <div class="col-md-6">
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="contact_number" class="col-md-4 col-form-label text-md-end text-start">Contact Number</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control @error('contact_number') is-invalid @enderror" id="contact_number" name="contact_number" value="{{ old('contact_number') }}">
                            @error('contact_number')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="completed_redirect_url" class="col-md-4 col-form-label text-md-end text-start">Completed Redirect URL</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control @error('completed_redirect_url') is-invalid @enderror" id="completed_redirect_url" name="completed_redirect_url" value="{{ old('completed_redirect_url') }}">
                            @error('completed_redirect_url')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="terminated_redirect_url" class="col-md-4 col-form-label text-md-end text-start">Terminated Redirect URL</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control @error('terminated_redirect_url') is-invalid @enderror" id="terminated_redirect_url" name="terminated_redirect_url" value="{{ old('terminated_redirect_url') }}">
                            @error('terminated_redirect_url')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="quote_full_redirect_url" class="col-md-4 col-form-label text-md-end text-start">Quote Full Redirect URL</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control @error('quote_full_redirect_url') is-invalid @enderror" id="quote_full_redirect_url" name="quote_full_redirect_url" value="{{ old('quote_full_redirect_url') }}">
                            @error('quote_full_redirect_url')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="Add Vendor">
                    </div>

                </form>
            </div>
        </div>
    </div>    
</div>

@endsection
