<!doctype html>
<html lang="en">
<head>
    @include('errors.head')
    @if(!empty(getOption('defaultAuthMainColor')))
        <style>
            html:before, html:after, body:before, body:after {
                background: linear-gradient({{ getOption('defaultAuthMainColor') }}, {{ getOption('defaultAuthSecondColor') }}) !important;
            }
            html:before, body:before {
                background: linear-gradient({{ getOption('defaultAuthSecondColor') }}, {{ getOption('defaultAuthMainColor') }}) !important;
            }
            body {
                color: {{ getOption('defaultAuthTextColor') }};
                background: linear-gradient({{ getOption('defaultAuthMainColor') }}, {{ getOption('defaultAuthSecondColor') }}) !important;
            }
            a {
                background: linear-gradient({{ getOption('defaultAuthButtonMainColor') }}, {{ getOption('defaultAuthButtonSecondColor') }}) !important;
                color: {{ getOption('defaultAuthButtonTextColor') }} !important;
            }
            a:hover {
                background: linear-gradient({{ getOption('defaultAuthButtonHoverMainColor') }}, {{ getOption('defaultAuthButtonHoverSecondColor') }}) !important;
                color: {{ getOption('defaultAuthButtonHoverTextColor') }} !important;
            }
            .bubble {
                background: linear-gradient({{ getOption('defaultAuthBubbleMainColor') }}, {{ getOption('defaultAuthBubbleSecondColor') }}) !important;
            }
            .bubble:before, .bubble:after {
                background: linear-gradient({{ getOption('defaultAuthBubbleSecondColor') }}, {{ getOption('defaultAuthBubbleMainColor') }}) !important;
            }
        </style>
    @endif
</head>
<body>
<div class="bubble"></div>
<div class="bubble"></div>
<div class="bubble"></div>
<div class="bubble"></div>
<div class="bubble"></div>
<div class="main">
    <h1>@yield('code')</h1>
    <p>@yield('message')</p>
    <a href="{{ url('') }}">بازگشت به صفحه اصلی</a>
</div>
@yield('content')
</body>
</html>
