@extends('layouts.app')

@section('content')
<section id="header" class="section_mb">
  <h2>Terms of Service</h2>
  <p>
    Welcome to Tax Easy. By accessing or using our website and services, you agree to be bound by the following Terms of Service (“Terms”). Please read them carefully.
  </p>
  <span>Effective Date: 25.07.2025</span>
</section>
<section id="content" class="section_mb">
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <div class="item">
          <h3>Introduction</h3>
          <p>These Terms govern your use of the Tax Easy website, mobile services, and tax filing platform (“Services”).</p>
          <p>Tax Easy is operated by [Your Company Name or Legal Entity], based in Australia, and complies with local tax laws and data regulations.</p>
        </div>
        <div class="item">
          <h3>Our Services</h3>
          <p>Tax Easy provides:</p>
          <ul>
            <li>
              <p>Digital tools for preparing and submitting tax returns</p>
            </li>
            <li>
              <p>Guidance for filing refunds</p>
            </li>
            <li>
              <p>Optional review by registered tax professionals</p>
            </li>
          </ul>
          <p>
            We do not guarantee the success or speed of your tax refund. Refund amounts depend on individual circumstances and government approval.
          </p>
        </div>
        <div class="item">
          <h3>Fees & Payments</h3>
          <p>Some services may require payment before processing. By using our paid services, you agree to:</p>
          <ul>
            <li>
              <p>Pay applicable fees displayed at checkout</p>
            </li>
            <li>
              <p>Allow us to process payments securely through our third-party provider</p>
            </li>
          </ul>
          <p>
            All prices are listed in AUD and may be subject to GST.
          </p>
        </div>
        <div class="item">
          <h3>Intellectual Property</h3>
          <p>
            All content on our website — including logos, text, designs, and tools — are the property of Tax Easy and protected by copyright laws. You may not reproduce or reuse any part without written permission.
          </p>
        </div>
        <div class="item">
          <h3>Limitation of Liability</h3>
          <p>Tax Easy is not responsible for:</p>
          <ul>
            <li>
              <p>Delays or denials of tax refunds by the ATO or other authorities</p>
            </li>
            <li>
              <p>Inaccuracies in user-submitted data</p>
            </li>
            <li>
              <p>Technical issues beyond our control (e.g., outages, third-party platform failures)</p>
            </li>
          </ul>
          <p>
            Our liability is limited to the amount paid for the service.
          </p>
        </div>
        <div class="item">
          <h3>Governing Law</h3>
          <p>
            These Terms are governed by the laws of New South Wales, Australia. Any disputes will be handled through Australian legal channels.
          </p>
        </div>
      </div>
      <div class="col-md-6">
        <div class="item">
          <h3>Eligibility</h3>
          <p>To use our Services, you must:</p>
          <ul>
            <li>
              <p>Be at least 18 years old</p>
            </li>
            <li>
              <p>Provide accurate and complete information</p>
            </li>
            <li>
              <p>Have the legal right to file taxes or act on behalf of someone who does</p>
            </li>
          </ul>
          <p>
            You agree not to use the Services for any unlawful or fraudulent purpose.
          </p>
        </div>
        <div class="item">
          <h3>User Responsibilities</h3>
          <p>You are responsible for:</p>
          <ul>
            <li>
              <p>Providing true, accurate, and complete information</p>
            </li>
            <li>
              <p>Reviewing the accuracy of your tax return before submission</p>
            </li>
            <li>
              <p>Maintaining confidentiality of your account credentials</p>
            </li>
          </ul>
          <p>
            Tax Easy is not liable for errors caused by incorrect or incomplete user information.
          </p>
        </div>
        <div class="item">
          <h3>Cancellations & Refunds</h3>
          <p>
            If you cancel a service before submission to the tax office, you may request a refund. Once your return has been submitted or reviewed by our team, refunds are no longer guaranteed.
          </p>
        </div>
        <div class="item">
          <h3>Privacy</h3>
          <p>
            Your use of our Services is also governed by our
            <a href="{{ route('privacy-policy') }}" class="ps-4">Privacy Policy</a>
          </p>
          <p>
            We take your data security seriously and comply with the Australian Privacy Principles (APPs).
          </p>
        </div>
        <div class="item">
          <h3>Modifications to These Terms</h3>
          <p>
            We may update these Terms from time to time. When we do, we will post the revised version on our site with the updated date.
          </p>
          <p>
            Your continued use of the Services means you accept the revised Terms.
          </p>
        </div>
      </div>
    </div>
  </div>
</section>
<section id="contact_info" class="section_mb">
    <div class="container contact_info_box">
        <span>
            <h3>Contact Us</h3>
            <p class="mb-4">If you have any questions about this Privacy Policy, please contact:</p>
            <ul class="nav flex-column gap-3">
              <li>
                <p class="text-start">
                  Tax Easy Privacy Officer
                </p>
              </li>
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
                        <img src="{{ asset('img/icons/address.png') }}" alt="address">
                        <span>123 Tax Lane, Sydney, NSW 2000</span>
                    </a>
                </li>
            </ul>
        </span>
        <img src="{{ asset('img/contact_info.png') }}" class="img-fluid" alt="contact info">
    </div>
</section>

@endsection