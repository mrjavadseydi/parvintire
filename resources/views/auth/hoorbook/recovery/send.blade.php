@extends("auth.{$authTheme}.master")
@section('title', 'بازیابی رمز عبور')

@section('content')

    <div class="container mtb10">

        @if($type == 'emailRecovery')
            @include('auth.hoorbook.recovery.email')
        @else
            @include('auth.hoorbook.recovery.mobile')
        @endif

    </div>

@endsection
