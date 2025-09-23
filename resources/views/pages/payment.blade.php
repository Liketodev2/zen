@extends('layouts.app')
@push('css')
    <script src="https://js.stripe.com/v3/"></script>
@endpush

@section('content')
    <div class="choosing-business-type">
        <div class="container">
            <a href="{{ route('choosing-business-type') }}" class="back-home">
                <img src="{{ asset('img/icons/back.png') }}" alt="back">
                <p>Back</p>
            </a>

            <div class="col">
                <h2 class="paymant_title">Payment Method</h2>
                <p class="paymant_description">
                    Now let’s find some tax deductions to help improve your refund.
                </p>
            </div>

            <form id="payment-form" method="POST" action="{{ route('payment.make') }}">
                @csrf
                <input type="hidden" name="tax_id" value="{{ $tax->id }}">
                <input type="hidden" name="stripeToken" id="stripeToken">

                <div class="payment_details mt-4">
                    <div class="info">
                        <p>
                            <span>${{ $amount }}</span>
                            Amount Due
                        </p>
                    </div>

                    <div class="form">
                        <h3>Your Credit Card Payment Details</h3>
                        <div class="mb-3" id="card-element" style="padding: 10px; border: 1px solid #ccc; border-radius: 4px;"></div>
                        <div id="card-errors" class="text-danger mt-2" role="alert"></div>
                    </div>
                </div>

                <div class="paymant_agree mt-4">
                    <input type="checkbox" name="agree" id="agree" value="1">
                    <label for="agree">
                        I agree to the terms of the Tax Easy Accountants client agreement and for Tax Easy to receive ATO correspondence for me. The information I have provided is true and correct.
                    </label>
                </div>

                <div class="col text-center mt-5">
                    <button class="btn navbar_btn" id="submit-button" type="submit">Click to Sign Your Tax Return</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script>
        const stripe = Stripe('{{ env('STRIPE_KEY') }}'); // Your Publishable Key
        const elements = stripe.elements({locale: 'en'});

        const card = elements.create('card', {
            hidePostalCode: true,
            classes: {
                base: 'no-autofill',
            },
            autocomplete: 'off',
            style: {
                base: {
                    fontSize: '16px',
                    color: '#32325d',
                    '::placeholder': {
                        color: '#aab7c4'
                    }
                },
                invalid: {
                    color: '#ec052f',
                    iconColor: '#ec052f'
                }
            }
        });

        card.mount('#card-element');

        const form = document.getElementById('payment-form');
        const submitButton = document.getElementById('submit-button');
        const errorDisplay = document.getElementById('card-errors');

        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            submitButton.disabled = true;
            submitButton.innerText = 'Processing...';

            const { token, error } = await stripe.createToken(card);

            if (error) {
                errorDisplay.textContent = error.message;
                submitButton.disabled = false;
                submitButton.innerText = 'Click to Sign Your Tax Return';
            } else {
                document.getElementById('stripeToken').value = token.id;
                form.submit();
            }
        });
    </script>
@endpush
