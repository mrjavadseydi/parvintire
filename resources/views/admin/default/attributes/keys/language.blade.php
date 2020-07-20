@extends("admin.{$adminTheme}.master")
@section('title', 'کلید های ترجمه نشده به زبان ' . $lang->title)

@section('content')

    <form action="{{ url('admin/attribute-keys/translate/store') }}" method="post" class="col-12">

        @csrf
        <input type="hidden" name="lang" value="{{ $_GET['lang'] ?? ''}}">

        <div class="alert alert-primary">
            <p>@yield('title')</p>
        </div>

        @foreach($records as $record)

            @if($record->lang != $_GET['lang'] ?? '')

                <input type="hidden" name="ids[]" value="{{ $record->id }}">

                <div class="box box-info">

                <div class="box-body row">

                    <div class="col-md-12">
                        <div class="input-group">
                            <label>عنوان</label>
                            <span style="color: #000">{{ $record->title }}</span>
                            <input class="{{ $lang->body }}" autofocus autocomplete="off" type="text" name="data[{{ $record->id }}][title]" value="{{ old('title') }}">
                        </div>
                    </div>

                    <div class="col-md-12 mt10">
                        <div class="input-group">
                            <label>توضیح</label>
                            <br>
                            <span style="color: #000">{{ $record->description }}</span>
                            <textarea class="{{ $lang->body }}" name="data[{{ $record->id }}][description]" cols="30" rows="10">{{ old('description') }}</textarea>
                        </div>
                    </div>

                </div>

            </div>

            @endif

        @endforeach

        <div class="box box-success">
            <div class="box-body">
                <button class="btn-lg btn-success w100">ذخیره</button>
            </div>
        </div>

        <div class="tac pt15">
            {!! $records->appends(request()->input())->links() !!}
        </div>

    </form>

@endsection
