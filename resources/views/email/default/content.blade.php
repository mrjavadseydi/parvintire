@extends("email.{$emailTheme}.master")

@section('content')

<h1>{{ $title }}</h1>
<br>
<p>{{ $description }}</p>
<br>
@if(isset($parameters))
    @foreach ($parameters as $key => $value)
        <span>{{ $key }}: <b>{{ $value }}</b></span><br>
    @endforeach
@endif
@endsection
