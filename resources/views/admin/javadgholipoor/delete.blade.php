@extends("admin.{$adminTheme}.master")
@section('title', "آیا از حذف {$title} اطمینان دارید؟")

@section('content')

    <form action="{{ $action }}" method="post">
        @csrf
        @method('delete')
        <input type="hidden" name="referer" value="{{ $referer }}">
        <input type="hidden" name="title" value="{{ $title }}">
        <button class="btn btn-danger">بله، حذف</button>
        <a href="{{ $referer }}" class="btn btn-info">خیر، بازگشت</a>
    </form>

@endsection
