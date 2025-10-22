@extends('layouts.survey')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-white text-center">
                    <h4><i class="fa fa-exclamation-triangle"></i> Screened Out</h4>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fa fa-exclamation-triangle text-warning" style="font-size: 4rem;"></i>
                    </div>
                    <h5 class="text-warning">Thank You for Your Participation</h5>
                    <p class="lead">{{ $message ?? 'Thank you so much for your participation in the survey. Unfortunately, you are disqualified based on your responses. Apologize for the inconvenience caused.' }}</p>
                    <p>We really appreciate your time and efforts. We will send another survey that meet the criteria of your profile. Have a good day!</p>
                    
                    <div class="mt-4">
                        <a href="#" class="btn btn-primary">Go to Profile</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
