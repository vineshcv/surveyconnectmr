@extends('layouts.survey')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-danger text-white text-center">
                    <h4><i class="fa fa-exclamation-circle"></i> URL Error</h4>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fa fa-exclamation-circle text-danger" style="font-size: 4rem;"></i>
                    </div>
                    <h5 class="text-danger">Invalid URL</h5>
                    <p class="lead">{{ $message ?? 'Invalid URL or project not found.' }}</p>
                    <p>Please check the URL or contact support if you believe this is an error.</p>
                    
                    <div class="mt-4">
                        <a href="#" class="btn btn-primary">Go to Profile</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
