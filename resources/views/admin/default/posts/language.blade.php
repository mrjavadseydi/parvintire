@extends("admin.{$adminTheme}.master")
@section('title', 'مطالب ترجمه نشده به زبان ' . $lang->title)

@section('content')

    <div class="col-12">


        <div class="alert alert-primary">
            <p>@yield('title')</p>
        </div>

        @foreach($records as $record)

            @if($record->lang != $_GET['lang'] ?? '')

                <form action="{{ url('admin/posts/translate/store') }}" target="_blank" method="post" class="box box-info">
                    @csrf
                    <input type="hidden" name="lang" value="{{ $_GET['lang'] ?? ''}}">
                    <input type="hidden" name="id" value="{{ $record->id }}">
                    <div class="box-body row">
                        <div class="col-md-12">
                            <div class="input-group">
                                <label>عنوان</label>
                                <span style="color: #000">{{ $record->title }}</span>
                                <input class="{{ $lang->body }}" autofocus autocomplete="off" type="text" name="title" value="{{ old('title') }}">
                            </div>
                        </div>
                    </div>
                    <div class="box-footer ltr">
                        <button class="btn-lg btn-success">ذخیره</button>
                    </div>
                </form>

            @endif

        @endforeach

        <div class="tac pt15">
            {!! $records->appends(request()->input())->links() !!}
        </div>

    </div>

@endsection
