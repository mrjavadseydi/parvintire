@extends("auth.{$authTheme}.master")
@section('title', 'تایید اکانت')

@section('content')

    <div class="container mtb10">

        @if($type == 'mobile')
            @include("auth.{$authTheme}.verify.mobile")
        @else
            @include("auth.{$authTheme}.verify.email")
        @endif

    </div>

@endsection
