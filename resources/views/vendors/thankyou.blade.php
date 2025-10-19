@extends('layouts.register')
<style>
.topbar h2 {
    color: #3C4A58;
    font-size: 20px;
    font-weight: 800;
    display: none !important;
}

.breadcrumb-item.active {
    color: #6c757d;
    display: none !important;
}
</style>

@section('content')
<div class="container text-center mt-5">
    <h2>🎉 Congratulations!</h2>
    <p>Your vendor registration has been successfully submitted.</p>
    <a href="/login" class="btn btn-primary mt-3">Back to Login</a>
</div>
@endsection
