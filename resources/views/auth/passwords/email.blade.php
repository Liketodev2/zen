@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-lg-8 ps-0 pe-0 mb-lg-0 mb-5 position-relative">
                <img src="{{ asset('img/sign_bg.png') }}" class="img-fluid" alt="">
                <div class="sign_bg">
                    <h1>Welcome to Tax Easy!</h1>
                    <h2>We're here to help you finish or review your tax return whenever you're ready. Secure, fast, and always saved for you.</h2>
                </div>
            </div>
            <div class="col-lg-4 ps-0 pe-0">
                <div class="text-center w-100" style="margin-bottom: 40px;">
                    <a class="" href="{{ route('home') }}">
                        <img src="{{ asset('img/logo.png?v=' . time()) }}" class="img-fluid" alt="">
                    </a>
                </div>
                <div class="reset_password_form">
                    <h2>{{ __('Forgot Password') }}</h2>
                    <h3>Don't Worry! Just fill in your email and we'll send you a link to reset your password.</h3>
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email Address" required autocomplete="email" autofocus>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                        <button type="submit" class="btn navbar_btn">
                            {{ __('Send Password Reset Link') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

