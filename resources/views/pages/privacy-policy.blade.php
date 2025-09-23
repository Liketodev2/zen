@extends('layouts.app')

@section('content')
<section id="header" class="section_mb">
  <h2>Privacy Policy</h2>
  <p>
    At Tax Easy, we are committed to protecting your privacy. This privacy policy explains how we collect, use, disclose and protect your personal information when you use our website and services.
  </p>
  <span>Effective Date: 25.07.2025</span>
</section>
<section id="content" class="section_mb">
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <div class="item">
          <h3>Information We Collect</h3>
          <p>We may collect the following types of personal information:</p>
          <ul>
            <li>
              <p>Name, address, email, and phone number</p>
            </li>
            <li>
              <p>Date of birth and Tax File Number (TFN)</p>
            </li>
            <li>
              <p>Identification documents (e.g. driver’s license, passport)</p>
            </li>
            <li>
              <p>Income and financial data related to tax filings</p>
            </li>
            <li>
              <p>Bank account details for refund processing</p>
            </li>
            <li>
              <p>Employment and business details (if applicable)</p>
            </li>
          </ul>
        </div>
        <div class="item">
          <h3>Sharing Your Information</h3>
          <p>We do not sell your information. We may share your data only with:</p>
          <ul>
            <li>
              <p>The Australian Taxation Office (ATO) or relevant government authorities</p>
            </li>
            <li>
              <p>Our internal tax advisors and compliance staff</p>
            </li>
            <li>
              <p>Trusted service providers (e.g. secure payment gateways, cloud hosting) — under confidentiality agreements</p>
            </li>
          </ul>
        </div>
        <div class="item">
          <h3>How Long We Keep Your Data</h3>
          <p>We retain your personal information only as long as necessary for tax filing, compliance, and recordkeeping — typically for 5–7 years, or as required by law.</p>
        </div>
        <div class="item">
          <h3>Cookies & Website Analytics</h3>
          <p>We use cookies and analytics tools to understand how visitors use our site. This helps us improve performance and usability. You can disable cookies in your browser settings.</p>
        </div>
      </div>
      <div class="col-md-6">
        <div class="item">
          <h3>How We Use Your Information</h3>
          <p>We use the information we collect to:</p>
          <ul>
            <li>
              <p>Prepare and lodge your tax return</p>
            </li>
            <li>
              <p>Verify your identity and comply with legal obligations</p>
            </li>
            <li>
              <p>Communicate with you about your refund or application</p>
            </li>
            <li>
              <p>Improve our services and customer experience</p>
            </li>
            <li>
              <p>Maintain accurate financial and legal records</p>
            </li>
          </ul>
        </div>
        <div class="item">
          <h3>How We Protect Your Information</h3>
          <p>We use secure servers, SSL encryption, and controlled access protocols to safeguard your data. We comply with the Australian Privacy Principles (APPs) and any other relevant data protection laws.</p>
        </div>
        <div class="item">
          <h3>Your Rights</h3>
          <p>You have the right to:</p>
          <ul>
            <li>
              <p>Access the personal data we hold about you</p>
            </li>
            <li>
              <p>Request corrections or updates</p>
            </li>
            <li>
              <p>Withdraw your consent (where applicable)</p>
            </li>
            <li>
              <p>Lodge a complaint with the Office of the Australian Information Commissioner (OAIC)</p>
            </li>
          </ul>
          <p>To request access or make changes, please contact us at:</p>
          <a href="mailto:privacy@taxeasy.com.au">privacy@taxeasy.com.au</a>
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