@extends('layouts.survey')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-white text-center">
                    <h4><i class="fa fa-pause"></i> Project Paused</h4>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fa fa-pause text-warning" style="font-size: 4rem;"></i>
                    </div>
                    <h5 class="text-warning">Survey Paused</h5>
                    <p class="lead">{{ $message ?? 'The survey is currently paused.' }}</p>
                    <p>Thank you for your interest. Please check back later or look for other available surveys.</p>
                    
                    <div class="mt-4">
                        <a href="#" class="btn btn-primary">Go to Profile</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
