@extends('layouts.app')

@section('content')
<section id="services_banner" class="section_mb">
    <div class="container">
        <h2>
            Our Services – Stress-Free Tax Returns for Australians
        </h2>
    </div>
</section>
<section id="accurate" class="section_mb">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2>
                    Fast, Accurate & Secure Online Tax Return Services
                </h2>
                <p>
                    We simplify your tax obligations so you can get back to what matters. Whether you're employed, self-employed, or managing investments, our online tax form covers it all.
                </p>
            </div>
            <div class="col-md-6">
                <div class="accurate_item">
                    <img src="{{ asset('img/accurate/1.png') }}" alt="">
                    <h3>
                        Lodge in as little as 15 minutes
                    </h3>
                </div>
                <div class="accurate_item">
                    <img src="{{ asset('img/accurate/2.png') }}" alt="">
                    <h3>
                        Handled by registered tax professionals
                    </h3>
                </div>
                <div class="accurate_item">
                    <img src="{{ asset('img/accurate/3.png') }}" alt="">
                    <h3>
                        Secure online forms and document uploads
                    </h3>
                </div>
                <div class="accurate_item">
                    <img src="{{ asset('img/accurate/4.png') }}" alt="">
                    <h3>
                        Appointments included if needed
                    </h3>
                </div>
            </div>
        </div>
    </div>
</section>
<section id="services_info" class="section_mb">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
               <div class="item">
                    <img src="{{ asset('img/services_info/1.png') }}" alt="">
                    <img src="{{ asset('img/icons/hr.png') }}"  class="hr">
                    <h2>Individual Tax Returns</h2>
                    <p>We help you maximize deductions and get the refund you deserve. Includes PAYG, allowances, government payments, and more.</p>
                    <p>Ideal for: employees, students, retirees</p>
               </div>
               <div class="item">
                    <img src="{{ asset('img/services_info/3.png') }}" alt="">
                    <img src="{{ asset('img/icons/hr.png') }}"  class="hr">
                    <h2>Rental Property Owners</h2>
                    <p>We guide you through rental income, expenses, depreciation, and ownership splits for accurate reporting.</p>
                    <p>Ideal for: individuals with investment properties</p>
               </div>
            </div>
            <div class="col-md-6">
               <div class="item">
                    <img src="{{ asset('img/services_info/2.png') }}" alt="">
                    <img src="{{ asset('img/icons/hr.png') }}"  class="hr">
                    <h2>Sole Traders & Freelancers</h2>
                    <p>Tailored tax returns with options to include car expenses, tools, software, and other work-related deductions.</p>
                    <p>Ideal for: ABN holders, gig economy workers</p>
               </div>
               <div class="item">
                    <img src="{{ asset('img/services_info/4.png') }}" alt="">
                    <img src="{{ asset('img/icons/hr.png') }}"  class="hr">
                    <h2>Capital Gains & Shares</h2>
                    <p>We handle reporting of asset sales, CGT offsets, and dividend incomes with precision.</p>
                    <p>Ideal for: investors, crypto traders, and share traders</p>
               </div>
            </div>
        </div>
    </div>
</section>
<section id="support" class="section_mb">
    <div class="container support_box">
        <span>
            <p>
                Need your Trust/Company Tax completed? Complete this form and we will contact you within 24 hours.
            </p>
            <a href="3" class="navbar_btn">Complete the Form</a>
        </span>
        <img src="{{ asset('img/support.png') }}" class="img-fluid" alt="support">
    </div>
</section>
<section id="plans" class="section_mb">
    <div class="title">
        Choose the Plan That Fits Your Needs
    </div>
    <div class="container plans">
        <div class="plan">
            <h2>
                Standard Plan
            </h2>
            <img src="{{ asset('img/icons/hr.png') }}"  class="hr">
            <p>
                Standard Individual Tax Return incl. GST
            </p>
            <p class="price">$ 149</p>
            <ul>
                <li>
                    <img src="{{ asset('img/icons/check-green.png') }}" alt="">
                    <p>
                        PAYG Employees
                    </p>
                </li>
                <li>
                    <img src="{{ asset('img/icons/check-green.png') }}" alt="">
                    <p>
                        Deductions & Offsets
                    </p>
                </li>
                <li>
                    <img src="{{ asset('img/icons/check-green.png') }}" alt="">
                    <p>
                        Secure Online Form
                    </p>
                </li>
                <li>
                    <img src="{{ asset('img/icons/check-green.png') }}" alt="">
                    <p>
                        Agent Review & Lodgement
                    </p>
                </li>
            </ul>
            <a href="" class="navbar_btn">Get Started</a>
        </div>
        <div class="plan white">
            <h2>
                Premium Plan
            </h2>
            <img src="{{ asset('img/icons/hr-white.png') }}"  class="hr">
            <p>
                Sole Trader / ABN Holder incl. GST
            </p>
            <p class="price">$ 199</p>
            <ul>
                <li>
                    <img src="{{ asset('img/icons/check-white.png') }}" alt="">
                    <p>
                        Includes Business Income
                    </p>
                </li>
                <li>
                    <img src="{{ asset('img/icons/check-white.png') }}" alt="">
                    <p>
                        Car/Tools/Expenses
                    </p>
                </li>
                <li>
                    <img src="{{ asset('img/icons/check-white.png') }}" alt="">
                    <p>
                        Document Uploads
                    </p>
                </li>
                <li>
                    <img src="{{ asset('img/icons/check-white.png') }}" alt="">
                    <p>
                        Professional Review
                    </p>
                </li>
            </ul>
            <a href="" class="navbar_btn white">Get Started</a>
        </div>
        <div class="plan">
            <h2>
                Investor Plan
            </h2>
            <img src="{{ asset('img/icons/hr.png') }}"  class="hr">
            <p>
                Rental Property / Investor Returns incl. GST
            </p>
            <p class="price">$ 249</p>
            <ul>
                <li>
                    <img src="{{ asset('img/icons/check-green.png') }}" alt="">
                    <p>
                        Investment Income
                    </p>
                </li>
                <li>
                    <img src="{{ asset('img/icons/check-green.png') }}" alt="">
                    <p>
                        CGT/Dividends
                    </p>
                </li>
                <li>
                    <img src="{{ asset('img/icons/check-green.png') }}" alt="">
                    <p>
                        Rental Property Reporting
                    </p>
                </li>
                <li>
                    <img src="{{ asset('img/icons/check-green.png') }}" alt="">
                    <p>
                        Asset Depreciation Support
                    </p>
                </li>
            </ul>
            <a href="" class="navbar_btn">Get Started</a>
        </div>
    </div>
</section>
<section id="what_included" class="section_mb">
    <div class="container what_included">
        <div>
            <h2>What’s Included</h2>
            <ul>
                <li>
                    <img src="{{ asset('img/icons/check-green.png') }}" alt="">
                    <p>
                        Personal review by a registered tax agent
                    </p>
                </li>
                <li>
                    <img src="{{ asset('img/icons/check-green.png') }}" alt="">
                    <p>
                        Secure document upload
                    </p>
                </li>
                <li>
                    <img src="{{ asset('img/icons/check-green.png') }}" alt="">
                    <p>
                        Phone support if needed
                    </p>
                </li>
                <li>
                    <img src="{{ asset('img/icons/check-green.png') }}" alt="">
                    <p>
                        Free re-lodgement for ATO rejected returns
                    </p>
                </li>
                <li>
                    <img src="{{ asset('img/icons/check-green.png') }}" alt="">
                    <p>
                        Lodgement to ATO within 24–48 hours after submission
                    </p>
                </li>
            </ul>
        </div>
        <img src="{{ asset('img/what_included.png') }}" class="img-fluid" alt="">
    </div>
</section>
<section id="ready" class="section_mb">
    <div class="container text-center">
        <div class="title">
            Ready to Start Your Tax Return?
        </div>
        <a href="" class="navbar_btn">Start My Tax Return</a>
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