{{--@if(is_array($value))--}}
{{--    <ul>--}}
{{--        @foreach($value as $k => $v)--}}
{{--            @if(!empty($v) || (is_array($v) && !empty(array_filter($v))))--}}
{{--                <li>--}}
{{--                    @if(is_string($k))--}}
{{--                        <strong>{{ $k }}:</strong>--}}
{{--                    @endif--}}
{{--                    @if(is_array($v))--}}
{{--                        @include('pdf.partials.value_renderer', ['value' => $v])--}}
{{--                    @else--}}
{{--                        {{ $v }}--}}
{{--                    @endif--}}
{{--                </li>--}}
{{--            @endif--}}
{{--        @endforeach--}}
{{--    </ul>--}}
{{--@else--}}
{{--    @if(!empty($value))--}}
{{--        {{ $value }}--}}
{{--    @endif--}}
{{--@endif--}}


@if(is_array($value))
    <ul>
        @foreach($value as $k => $v)
            @if(!empty($v) || (is_array($v) && !empty(array_filter($v))))
                <li>
                    @if(is_string($k))
                        <strong>{{ $k }}:</strong>
                    @endif
                    @if(is_array($v))
                        @include('pdf.partials.value_renderer', ['value' => $v])
                    @else
                        @if(Str::contains($v, '/')) {{-- crude file path detection --}}
                        <a href="{{ Storage::disk('s3')->url($v) }}" target="_blank">
                            {{ basename($v) }}
                        </a>
                        @else
                            {{ $v }}
                        @endif
                    @endif
                </li>
            @endif
        @endforeach
    </ul>
@else
    @if(!empty($value))
        {{ $value }}
    @endif
@endif

