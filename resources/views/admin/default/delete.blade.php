@extends("admin.{$adminTheme}.master")
@section('title', "آیا از حذف {$title} اطمینان دارید؟")

@section('content')

    <form action="{{ $action }}" method="post">
        @csrf
        @method('delete')
        <input type="hidden" name="referer" value="{{ $referer }}">
        <input type="hidden" name="title" value="{{ $title }}">
        <h4>آیا از حذف {{ $title }} اطمینان دارید؟</h4>
        <button class="btn btn-danger">بله، حذف</button>
        <a href="{{ $referer }}" class="btn btn-purple">خیر، بازگشت</a>

    </form>

@endsection
