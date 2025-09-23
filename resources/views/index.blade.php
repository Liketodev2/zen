@extends('layouts.app')

@section('content')
<section id="home_banner" class="section_mb">
    <div class="container">
        <div class="col-md-6">
            <h1>Lodge Your Income Tax Return the Smart, Easy Way</h1>
            <p>Trusted by individuals and families across Australia. Secure. Simple. Fast.</p>
            <a href="{{ route('tax-returns.index') }}" class="navbar_btn">Start Your Income Tax Return Now</a>
        </div>
    </div>
</section>
<section id="how_it_works" class="section_mb">
    <h2 class="title">How It Works</h2>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h2>STEP 1</h2>
                <img src="{{ asset('img/how_it_works/1.png') }}" class="img-fluid mb-3" alt="how_it_works">
                <h2>Upload or Enter Your Info</h2>
                <p>Provide only what’s necessary — securely and at your own pace.</p>
            </div>
            <div class="col-md-4">
                <h2>STEP 2</h2>
                <img src="{{ asset('img/how_it_works/2.png') }}" class="img-fluid mb-3" alt="how_it_works">
                <h2>Answer Simple Questions</h2>
                <p>We’ve broken down the ATO forms into an easy-to-follow questionnaire.</p>
            </div>
            <div class="col-md-4">
                <h2>STEP 3</h2>
                <img src="{{ asset('img/how_it_works/3.png') }}" class="img-fluid mb-3" alt="how_it_works">
                <h2>We Lodge Your Return</h2>
                <p>You sit back. We lodge and notify you once it's done.</p>
            </div>
        </div>
    </div>
</section>
<section id="happens" class="section_mb">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
            <h2 class="title">What Happens After You Submit</h2>
                <div class="row">
                    <div class="col-md-6">
                        <div class="happens_box">
                            <span>1</span>
                            <div class="">
                                <h3>We Double-Check Everything</h3>
                                <p>A registered Tax Agent reviews your return for accuracy and compliance.</p>
                            </div>
                        </div>
                        <div class="happens_box">
                            <span>3</span>
                            <div class="">
                                <h3>We Lodge with the ATO</h3>
                                <p>We lodge your return with the ATO securely and on time.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="happens_box">
                            <span>2</span>
                            <div class="">
                                <h3>We Maximise Your Refund</h3>
                                <p>We scan for extra deductions to help you claim everything you’re owed.</p>
                            </div>
                        </div>
                        <div class="happens_box">
                            <span>4</span>
                            <div class="">
                                <h3>You Get Paid</h3>
                                <p>our refund (if any) is on its way — usually within 7–14 days.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <img src="{{ asset('img/happens.png') }}" class="img-fluid" alt="happens">
            </div>
        </div>
    </div>
</section>
<section id="testimonials" class="section_mb">
    <h2 class="title">What Our Clients Are Saying</h2>
    <div class="container-fluid">
        <div class="owl-carousel owl-theme">
            @for ($i = 0; $i < 10; $i++)
                <div class="testimonials_box">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <p class="testimonials_author">Sarah M.</p>
                        <div class="d-flex gap-1">
                            @for ($j = 0; $j < 5; $j++)
                                <img src="{{ asset('img/icons/star.png') }}" alt="star">
                            @endfor
                        </div>
                    </div>
                    <p class="testimonials_text">
                        Lorem ipsum dolor sit amet consectetur. At odio vitae congue at in gravida pretium. Elit dolor viverra eget commodo velit. Feugiat faucibus felis tellus sit.
                    </p>
                    <p class="testimonials_loc">
                        Freelancer, Brisbane
                    </p>
                </div>
            @endfor
        </div>        
    </div>
</section>
<section id="home_info" class="section_mb">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="home_info_box">
                    <p class="title">TPB</p>
                    <p>Registered</p>
                </div>
                <div class="home_info_box">
                    <img src="{{ asset('img/icons/secure.png') }}" alt="secure">
                    <img src="{{ asset('img/icons/hr.png') }}"  class="hr">
                    <h2>Secure & Private</h2>
                    <p>Your financial data is encrypted and handled by registered professionals.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="home_info_box">
                    <img src="{{ asset('img/icons/fast.png') }}" alt="fast">
                    <img src="{{ asset('img/icons/hr.png') }}"  class="hr">
                    <h2>Fast Lodgement</h2>
                    <p>Complete your tax return in as little as 15 minutes.</p>
                </div>
                <div class="home_info_box">
                    <p class="title">1000+</p>
                    <p>Returns Lodged</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="home_info_box">
                    <p class="title">Stripe</p>
                    <p>Secure Payments</p>
                </div>
                <div class="home_info_box">
                    <img src="{{ asset('img/icons/expert.png') }}" alt="expert">
                    <img src="{{ asset('img/icons/hr.png') }}"  class="hr">
                    <h2>Expert Help</h2>
                    <p>Need support? Our licensed tax agents are ready to help.</p>
                </div>
            </div>
        </div>
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
  
  
@endsection