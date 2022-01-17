@extends(includeEmail('master'))
@section('content')
    <div style="text-align: center; padding: 10px;">
        <a style="text-decoration: none; padding: 10px 15px; background: #027c00; color: white; border-radius: 5px;" href="{{ $url }}">{{ $title }}</a>
    </div>
@endsection
