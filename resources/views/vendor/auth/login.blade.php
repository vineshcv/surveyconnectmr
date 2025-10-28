@extends('layouts.vendor-login')

@section('content')
<div class="container">
    <div class="topbar"></div>
    <div class="row jumbotron box8">
        <div class="logo text-center mb-4">
            <img src="{{ asset('assets/Img/logo.png') }}" alt="logo">
        </div>

        <form method="POST" action="{{ route('vendor.login') }}">
            @csrf

            <div class="col-sm-12 form-group mb-3">
                <label for="username">Username or Email *</label>
                <input id="username" type="text" 
                       class="form-control @error('username') is-invalid @enderror" 
                       name="username" value="{{ old('username') }}" 
                       required autofocus>
                
                @error('username')
                    <span class="invalid-feedback text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="col-sm-12 form-group mb-3">
                <label for="password">Password *</label>
                <input id="password" type="password" 
                       class="form-control @error('password') is-invalid @enderror" 
                       name="password" required>
                
                @error('password')
                    <span class="invalid-feedback text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="btn-center mt-3">
                <button type="submit" class="btn btn-primary btn-rounded">
                    Login
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
