

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
                    <h2>{{ __('Reset Password') }}</h2>
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <input placeholder="Email Address"  id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                        <div class="position-relative">
                            <input id="password" placeholder="Password" type="password"
                                   class="form-control @error('password') is-invalid @enderror" name="password"
                                   required autocomplete="current-password">
                            <button type="button" class="btn position-absolute end-0 top-0 mt-0 h-100 d-flex align-items-center justify-content-center toggle-password" style="background: none; border: none;">
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
                            <button type="button" class="btn position-absolute end-0 top-0 mt-0 h-100 d-flex align-items-center justify-content-center toggle-password" style="background: none; border: none;">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>

                        <button type="submit" class="btn navbar_btn">
                            {{ __('Reset Password') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

