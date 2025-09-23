@extends('layouts.app')

@section('content')
<section id="contact_banner" class="page_banner section_mb">
    <div class="container">
        <h2>
            We're here to help
        </h2>
        <p>
            Got questions about your tax refund, account, or filing process? Reach out and our team will respond as soon as possible.
        </p>
    </div>
</section>
<section id="contact_info" class="section_mb">
    <div class="container contact_info_box">
        <span>
            <h3>Quick Contact Info</h3>
            <ul class="nav flex-column gap-4">
                @if($info?->phone)
                    <li>
                        <a href="#">
                            <img src="{{ asset('img/icons/sm_phone.png') }}" alt="phone">
                            <span>{{ $info->phone }}</span>
                        </a>
                    </li>
                @endif
                @if($info?->email)
                    <li>
                        <a href="#">
                            <img src="{{ asset('img/icons/sm_email.png') }}" alt="clock">
                            <span>{{ $info->email }}</span>
                        </a>
                    </li>
                @endif
                <li>
                    <a href="#">
                        <img src="{{ asset('img/icons/clock.png') }}" alt="email">
                        <span>Mon–Fri, 9AM–5PM (AEST)</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <img src="{{ asset('img/icons/address.png') }}" alt="address">
                        <span>123 Tax Lane, Sydney, NSW 2000</span>
                    </a>
                </li>
            </ul>
        </span>
        <img src="{{ asset('img/contact_info.png') }}" class="img-fluid" alt="contact info">
    </div>
</section>
<section id="faq" class="section_mb py-5">
    <h2 class="title">Frequently Asked Questions</h2>
    <div class="container">
      <div class="row g-4"> 
        <div class="col-md-6">
          <div class="accordion" id="faqLeft">
            @for ($i = 1; $i <= 4; $i++)
              <div class="accordion-item border rounded">
                <h2 class="accordion-header" id="heading{{ $i }}">
                  <button class="accordion-button collapsed bg-white" type="button"
                          data-bs-toggle="collapse" data-bs-target="#collapse{{ $i }}">
                    Question {{ $i }}
                  </button>
                </h2>
                <div id="collapse{{ $i }}" class="accordion-collapse collapse"
                     data-bs-parent="#faqLeft">
                  <div class="accordion-body">
                    Answer {{ $i }}
                  </div>
                </div>
              </div>
            @endfor
          </div>
        </div>
  
        <div class="col-md-6">
          <div class="accordion" id="faqRight">
            @for ($i = 5; $i <= 8; $i++)
              <div class="accordion-item border rounded shadow-sm">
                <h2 class="accordion-header" id="heading{{ $i }}">
                  <button class="accordion-button collapsed bg-white" type="button"
                          data-bs-toggle="collapse" data-bs-target="#collapse{{ $i }}">
                    Question {{ $i }}
                  </button>
                </h2>
                <div id="collapse{{ $i }}" class="accordion-collapse collapse"
                     data-bs-parent="#faqRight">
                  <div class="accordion-body">
                    Answer {{ $i }}
                  </div>
                </div>
              </div>
            @endfor
          </div>
        </div>
      </div>
    </div>
</section>
<section id="contact_form" class="section_mb">
    <div class="container contact_form_box">
        <img src="{{ asset('img/contact_form.png') }}" class="img-fluid" alt="contact form">
        <form class="w-100">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <input type="text" name="f_name" class="form-control border-dark" placeholder="First Name">
                </div>
                <div class="col-md-6 mb-3">
                    <input type="text" name="l_name" class="form-control border-dark" placeholder="Last Name">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <input type="email" name="email" class="form-control border-dark" placeholder="Email Address">
                </div>
                <div class="col-md-6 mb-3">
                    <input type="text" name="subject" class="form-control border-dark" placeholder="Subject">
                </div>
            </div>
            <div class="mb-3">
                <textarea name="message" class="form-control border-dark" rows="8" placeholder="Message"></textarea>
            </div>
            <div class="col text-center mt-4">
                <button type="submit" class="btn navbar_btn">Submit</button>
            </div>
        </form>
    </div>
</section>
<section id="contact_help" class="section_mb">
    <div class="container text-center">
        <h2>We're here to help</h2>
        <p>Got questions about your tax refund, account, or filing process? Reach out and our team will respond as soon as possible.</p>
    </div>
</section>

@endsection