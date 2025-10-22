@extends('layouts.survey')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white text-center">
                    <h4><i class="fa fa-check"></i> Project Completed</h4>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fa fa-check text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h5 class="text-success">Survey Completed</h5>
                    <p class="lead">{{ $message ?? 'This survey has been completed.' }}</p>
                    <p>Thank you for your interest. Please check your profile for other available surveys.</p>
                    
                    <div class="mt-4">
                        <a href="#" class="btn btn-primary">Go to Profile</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
