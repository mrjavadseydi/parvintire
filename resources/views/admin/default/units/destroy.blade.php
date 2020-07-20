@extends("admin.{$adminTheme}.master")
@section('title', "حذف " . $record->title)

@section('content')


    <form action="{{ route('admin.units.destroy', $record) }}" method="post">

        @csrf
        @method('delete')
        @if(isset($_GET['url']))
            <input type="hidden" name="url" value="{{ $_GET['url'] }}">
        @endif
        <h4>آیا اطمینان دارید که میخواهید این مورد را حذف کنید؟</h4>
        <button class="btn btn-danger">بله، حذف</button>
        <a href="{{ $_GET['url'] ?? '#' }}" class="btn btn-purple">خیر، بازگشت</a>

    </form>

@endsection

