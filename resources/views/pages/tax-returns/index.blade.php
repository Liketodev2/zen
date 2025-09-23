@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="tax-returns-title">
        Hi {{ Auth::user()->name }}, welcome back
    </h2>

    @include('widgets.toast')

    <div class="grin_box_border tax_returns_box">
        <h3>Your {{ date('Y') }} Tax Return</h3>
        <p>
            The {{ date('Y') }} tax return is for income you received between 1 July {{ date('Y') - 1 }} and 30 June {{ date('Y') }}.
        </p>
        <p>
            Just add your income, bank interest, any more deductions, health cover and other items, then you should be done! Your return was started, but not signed.
        </p>

        @if($incompleteForm)
            <a href="{{ route('tax-returns.edit', $incompleteForm->id) }}" class="navbar_btn mt-4">
                Finish My {{ $incompleteForm->updated_at->format('Y') }} Tax Return
            </a>
        @else
            <a href="{{ route('tax-returns.create') }}" class="navbar_btn mt-4">
                Start My {{ date('Y') }} Tax Return
            </a>
        @endif
    </div>

    <div class="grin_box_border tax_returns_box">
        <h3>Previous Years' Tax Returns</h3>
        <div class="" style="border: 1px solid #15a1b780; border-radius: 16px;">
            <table class="tax_returns_table">
                <thead class="">
                    <tr>
                        <th>Year</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @if($incompleteForm)
                        <tr>
                            <td>{{ $incompleteForm->updated_at->format('Y') }}</td>
                            <td class="text-capitalize">{{ $incompleteForm->form_status }}</td>
                        </tr>
                    @endif
                    @foreach($completedForms as $form)
                        <tr>
                            <td>{{ $form->updated_at->format('Y') }}</td>
                            <td class="text-capitalize">{{ $form->form_status }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
