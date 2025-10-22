@extends('layouts.survey')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-dark text-white text-center">
                    <h4><i class="fa fa-shield-alt"></i> Access Blocked</h4>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fa fa-shield-alt text-dark" style="font-size: 4rem;"></i>
                    </div>
                    <h5 class="text-dark">Access Blocked for Security Reasons</h5>
                    <p class="lead">{{ $message ?? 'Thank you for attempting the survey. However, your access has been temporarily blocked due to a security policy violation or suspicious activity detected from your network.' }}</p>
                    <p>We truly appreciate your interest, time, and valuable insights. If you believe this was a mistake, please log in to your profile for more details, review your points, and choose your reimbursement options.</p>
                    <p>Thank you for your understanding. Have a great day!</p>
                    
                    <div class="mt-4">
                        <a href="#" class="btn btn-primary">Go to Profile</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
