@extends("admin.{$adminTheme}.master")
@section('title', "حذف " . $user->name())

@section('content')


    <form action="{{ route('admin.users.destroy', $user) }}" method="post">

        @csrf
        @method('delete')
        <input type="hidden" name="url" value="{{ $_GET['url'] ?? route('admin.users.index') }}">
        <h4>آیا اطمینان دارید که میخواهید این کاربر را حذف کنید؟</h4>
        <button class="btn btn-danger">بله، حذف</button>
        <a href="{{ $_GET['url'] ?? '#' }}" class="btn btn-purple">خیر، بازگشت</a>

    </form>

@endsection

