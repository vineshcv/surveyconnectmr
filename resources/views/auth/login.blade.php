@extends('layouts.applogin')

@section('content')
<div class="container">
    <div class="topbar"></div>
    <div class="row jumbotron box8">
        <div class="logo text-center mb-4">
            <img src="{{ asset('assets/Img/logo.png') }}" alt="logo">
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="col-sm-12 form-group mb-3">
                <label for="email">Email Address *</label>
                <input id="email" type="email" 
                       class="form-control @error('email') is-invalid @enderror" 
                       name="email" value="{{ old('email') }}" 
                       required autocomplete="email" autofocus>
                
                @error('email')
                    <span class="invalid-feedback text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="col-sm-12 form-group mb-3">
                <label for="password">Password *</label>
                <input id="password" type="password" 
                       class="form-control @error('password') is-invalid @enderror" 
                       name="password" required autocomplete="current-password">
                
                @error('password')
                    <span class="invalid-feedback text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="col-sm-12 form-group mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" 
                           {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">
                        {{ __('Remember Me') }}
                    </label>
                </div>
            </div>

            <div class="btn-center mt-3">
                <button type="submit" class="btn btn-primary btn-rounded">
                    {{ __('Login') }}
                </button>
            </div>

            @if (Route::has('password.request'))
                <div class="btn-center mt-2">
                    <a class="btn btn-link text-white" href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>
                </div>
            @endif

            <div class="vr" onclick="window.location.href='{{ url('/vendor-registration') }}'">
                Vendor Registration
            </div>
        </form>
    </div>
</div>
@endsection
