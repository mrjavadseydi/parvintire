@extends("auth.{$authTheme}.master")
@section('title', 'بازیابی رمز عبور')

@section('content')

    <div class="container mtb10">

        @if($type == 'emailRecovery')
            @include("auth.{$authTheme}.recovery.email")
        @else
            @include("auth.{$authTheme}.recovery.mobile")
        @endif

    </div>

@endsection
