@extends('layouts.survey')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-success text-white text-center">
                <h4><i class="fa fa-check-circle"></i> Survey Completed</h4>
            </div>
            <div class="card-body text-center">
                <div class="mb-4">
                    <i class="fa fa-check-circle text-success" style="font-size: 4rem;"></i>
                </div>
                <h5 class="text-success">Thank You!</h5>
                <p class="lead">{{ $message ?? 'Thank you so much for completing the survey. We are really thankful to you for your valuable insights, time and efforts.' }}</p>
                <p>Please login to your profile to check out the points and select your reimbursement options. Have a good day!</p>
                
                <div class="mt-4">
                    <a href="#" class="btn btn-primary">Go to Profile</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
