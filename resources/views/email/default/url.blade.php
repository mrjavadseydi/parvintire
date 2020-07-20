@extends("email.{$emailTheme}.master")

@section('content')

    <a href="{{ $url }}">{{ $title }}</a>

@endsection
