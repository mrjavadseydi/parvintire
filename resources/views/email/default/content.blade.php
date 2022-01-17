@extends(includeEmail('master'))
@section('content')
<h1 style="font-size: 14px;">{!! $title !!}</h1>
<p style="font-size: 12px; color: #282828;">{!! $description !!}</p>
@if(isset($parameters))
    @foreach ($parameters as $key => $value)
        <span style="display: block;">{!! $key !!}: <b style="color: #047f00">{!! $value !!}</b></span>
    @endforeach
@endif
@endsection
