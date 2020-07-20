@extends("auth.{$authTheme}.master")
@section('title', 'تایید اکانت')

@section('content')

    <div class="container mtb10">

        @if($type == 'mobile')
            @include('auth.hoorbook.verify.mobile')
        @else
            @include('auth.hoorbook.verify.email')
        @endif

    </div>

@endsection
