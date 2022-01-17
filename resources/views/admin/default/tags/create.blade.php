@extends("admin.{$adminTheme}.master")
@section('title', 'افزودن برچسب')

@section('content')

    <div class="col-lg-4">

        <form action="{{ route('admin.tags.store') }}" method="post" class="box-solid box-primary">

            <div class="box-header">
                <h3 class="box-title">@yield('title')</h3>
                <div class="box-tools">
                    <i class="box-tools-icon icon-minus"></i>
                </div>
            </div>

            <div class="box-body">

                @csrf

                <div class="input-group">
                    <i class="icon-title"></i>
                    <label>برچسب</label>
                    <input autofocus autocomplete="off" type="text" name="tag" value="{{ old('tag') }}">
                </div>

            </div>

            <div class="box-footer tal">
                <button type="submit" class="btn-lg btn-success">ذخیره</button>
            </div>

        </form>

    </div>

@endsection
