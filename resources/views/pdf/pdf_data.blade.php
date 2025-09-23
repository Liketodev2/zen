<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $name }} - Tax #{{ $tax->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
        h2 { font-size: 16px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 6px; text-align: left; vertical-align: top; }
        ul { margin: 0; padding-left: 20px; }
    </style>
</head>
<body>

<h2>{{ $name }}</h2>
<p><strong>Tax Return ID:</strong> {{ $tax->id }}</p>

{{-- Display form data --}}
@if(!empty($formData))
    <table>
        @foreach($formData as $row)
            @foreach($row as $label => $value)
                @php
                    // Check if value is meaningful (not empty, not null, not empty array)
                    $hasValue = !empty($value) && !(is_array($value) && empty(array_filter($value)));
                @endphp

                @if($hasValue)
                    <tr>
                        <th>{{ $label }}</th>
                        <td>
                            @include('pdf.partials.value_renderer', ['value' => $value])
                        </td>
                    </tr>
                @endif
            @endforeach
        @endforeach
    </table>
@endif

{{-- Uploaded file links --}}
@if(!empty($links))
    <h3>Uploaded File Links</h3>
    <ul>
        @foreach($links as $link)
            <li>
                <a href="{{ $link['url'] }}" target="_blank">{{ $link['label'] }}</a>
            </li>
        @endforeach
    </ul>
@endif

</body>
</html>
