@extends("admin.{$adminTheme}.master")
@section('title', 'مطالب ترجمه نشده به زبان ' . $lang->title)

@section('content')

    <div class="col-12">

        <div class="alert alert-primary">
            <p>@yield('title')</p>
        </div>

        <div class="box box-success p10">
            <form action="" method="get">
                <input type="hidden" name="lang" value="{{ $_GET['lang'] }}">
                <div class="row filters">
                    <div class="col-lg-12">
                        @include('filters.default.search', ['placeholder' => 'عنوان'])
                    </div>
                    <div class="col-12 mt5 tal">
                        <button class="btn-lg btn-success">اعمال فیلتر</button>
                    </div>
                </div>
            </form>
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
                        <a target="_blank" class="btn-lg btn-info" href="{{ url("admin/posts/{$record->id}/edit") }}">نسخه اصلی</a>
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
