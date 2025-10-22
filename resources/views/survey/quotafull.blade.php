@extends('layouts.survey')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-info text-white text-center">
                    <h4><i class="fa fa-users"></i> Quota Full</h4>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fa fa-users text-info" style="font-size: 4rem;"></i>
                    </div>
                    <h5 class="text-info">Quota Reached</h5>
                    <p class="lead">{{ $message ?? 'Thank you so much for your participation in the survey. We regret to inform; our targeted quota has been reached as per your selection.' }}</p>
                    <p>We really appreciate your time and efforts. Apologize for the inconvenience caused. We would be happy to send another survey that match your updated profile. Have a good day!</p>
                    
                    <div class="mt-4">
                        <a href="#" class="btn btn-primary">Go to Profile</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
