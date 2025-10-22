@extends('layouts.survey')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-danger text-white text-center">
                    <h4><i class="fa fa-ban"></i> Already Participated</h4>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fa fa-ban text-danger" style="font-size: 4rem;"></i>
                    </div>
                    <h5 class="text-danger">Already Participated</h5>
                    <p class="lead">{{ $message ?? 'You have already participated in this survey.' }}</p>
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
