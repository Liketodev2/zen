@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row align-items-center">
        <div class="col-lg-8 ps-0 pe-0 mb-lg-0 mb-5 position-relative">
            <img src="{{ asset('img/sign_bg.png') }}" class="img-fluid" alt="">
            <div class="sign_bg">
                <h1>Welcome to Tax Easy!</h1>
                <h2>We’re excited to help make your tax return simple, secure, and stress-free. Just create your account to get started — you can save your progress anytime and come back whenever you're ready.</h2>
            </div>
        </div>
        <div class="col-lg-4 ps-0 pe-0">
            <div class="text-center w-100" style="margin-bottom: 40px;">
                <a class="" href="{{ route('home') }}">
                    <img src="{{ asset('img/logo.png?v=' . time()) }}" class="img-fluid" alt="">
                </a>
            </div>
            <div class="sign_form">
                <h2>Create Your Tax Easy Account</h2>
                <h3>Start, save, and complete your tax return securely.</h3>
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="Full Name" required autofocus>
                    @error('name')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror

                    <input id="email" type="email" class="mb-0 form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email Address" required autocomplete="email">
                    <small>Used for login and to send confirmation</small>
                    @error('email')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror

                    <div class="position-relative">
                        <input placeholder="Password" id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                        <button type="button" class="btn position-absolute end-0 top-0 h-100 mt-0 d-flex align-items-center justify-content-center toggle-password" style="background: none; border: none;">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    
                    <div class="position-relative">
                        <input placeholder="Confirm Password" id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                        <button type="button" class="btn position-absolute end-0 top-0 h-100 mt-0 d-flex align-items-center justify-content-center toggle-password" style="background: none; border: none;">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>                    <div class="form-check">
                        <input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox" name="terms" id="terms" {{ old('terms') ? 'checked' : '' }} required>
                        <label class="form-check-label" for="terms">
                            <p>
                                I agree to the  
                                <a href="#" class="btn" target="_blank">Terms of Service</a>
                                and 
                                <a href="#" class="btn" target="_blank">Privacy Policy</a>    
                            </p>                        
                        </label>
                        @error('terms')
                            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    <button type="submit" class="btn navbar_btn">
                        {{ __('Get Started') }}
                    </button>
                </form>
            </div>
            
            <div class="sign_account">
                @if (Route::has('login'))
                    <p>Already have an account? <a class="btn" href="{{ route('login') }}">{{ __('Log In') }}</a></p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
