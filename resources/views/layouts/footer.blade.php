@php
    use App\Models\SiteInfo;
    $info = SiteInfo::first();
@endphp

<footer>
    <div class="container-fluid p-0" style="background: #FAFAFA; border-top: 1px solid #EEEEEE">
        <div class="container py-5">
            <div class="row">
                
                <div class="col-md-4 mb-3">
                    <img src="{{ asset('img/logo.png?v=' . time()) }}" alt="logo" class="img-fluid">

                    @if($info && ($info->facebook || $info->instagram || $info->x))
                        <div class="soc_links mt-3">
                            @if($info->facebook)
                                <a href="{{ $info->facebook }}" target="_blank">
                                    <img src="{{ asset('img/icons/facebook.png') }}" alt="facebook">
                                </a>
                            @endif
                            @if($info->instagram)
                                <a href="{{ $info->instagram }}" target="_blank">
                                    <img src="{{ asset('img/icons/instagram.png') }}" alt="instagram">
                                </a>
                            @endif
                            @if($info->x)
                                <a href="{{ $info->x }}" target="_blank">
                                    <img src="{{ asset('img/icons/x.png') }}" alt="x">
                                </a>
                            @endif
                        </div>
                    @endif

                    <a href="https://www.tpb.gov.au" target="_blank" class="d-block mt-3">Verify at www.tpb.gov.au</a>
                </div>

                <div class="col-md-4 mb-3">
                    <ul class="nav flex-column gap-4">
                        @if($info?->phone)
                            <li>
                                <a href="tel:{{ $info->phone }}">
                                    <img src="{{ asset('img/icons/phone.png') }}" alt="phone">
                                    <span>Phone: {{ $info->phone }}</span>
                                </a>
                            </li>
                        @endif
                        @if($info?->email)
                            <li>
                                <a href="mailto:{{ $info->email }}">
                                    <img src="{{ asset('img/icons/email.png') }}" alt="email">
                                    <span>Email: {{ $info->email }}</span>
                                </a>
                            </li>
                        @endif
                        @if($info?->abn)
                            <li>
                                <a href="#">
                                    <img src="{{ asset('img/icons/link.png') }}" alt="link">
                                    <span>ABN: {{ $info->abn }}</span>
                                </a>
                            </li>
                        @endif
                        @if($info?->tax_agent)
                            <li>
                                <a href="#">
                                    <img src="{{ asset('img/icons/lock.png') }}" alt="lock">
                                    <span>Registered Tax Agent – {{ $info->tax_agent }}</span>
                                </a>
                            </li>
                        @endif
                        <li>
                            <a href="#">
                                <img src="{{ asset('img/icons/card.png') }}" alt="card">
                                <span>Payments securely processed by Stripe</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="col-md-4 mb-3">
                    <ul class="nav flex-column gap-4">
                        <li><a href="{{ route('home') }}" class="navbar_link">Home</a></li>
                        <li><a href="{{ route('services') }}" class="navbar_link">Services</a></li>
                        <li><a href="{{ route('contact') }}" class="navbar_link">Contact Us</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid p-0" style="background: #ffffff">
        <div class="container py-4">
            <div class="row">
                <div class="col-md-8 mb-3">
                    <p>© {{ date('Y') }} The Sapphire Group. All rights reserved.</p>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="d-flex gap-4">
                        <a href="{{ route('privacy-policy') }}" class="navbar_link">Privacy Policy</a>
                        <a href="{{ route('terms-service') }}" class="navbar_link">Terms of Service</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
