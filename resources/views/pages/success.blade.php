@extends('layouts.app')

@section('content')
<div class="container d-flex align-items-center" style="height: 100vh">
    <div class="m-auto">
        <div id="success_message">
            <h3>
                Thank you for choosing Tax-Easy to complete your tax return this year. Our team will work vigilantly to complete your return.
            </h3>
            <p>
                Please keep your email address and contact phone close by over the next 48 hours. Our team may contact you for further questions regarding your income tax return in the interest of providing you the best and most accurate outcome.
            </p>
            <p>
                A copy of your completed form has been sent to your email address.
            </p>
            <p class="mb-5">
                Should you have any questions or queries, please don’t hesitate to contact us via the inbox system in your account. Or furthermore, you can email us at info@tax-easy.com.au
            </p>
            <p>All the best,</p>
            <p>The Tax-Easy Group Team.</p>
        </div>
        <div class="col text-center mt-5">
            <a href="{{ route('home') }}" class="navbar_btn">
                Back to home
            </a>
        </div>
    </div>
</div>
@endsection