@extends("admin.{$adminTheme}.master")
@section('title', 'افزودن واحد جدید')

@section('content')

    <div class="col-lg-12">

        <form action="{{ route('admin.units.store') }}" method="post" class="box-solid box-primary">

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
                    <label>عنوان</label>
                    <input autofocus autocomplete="off" type="text" name="title" value="{{ old('title') }}">
                </div>

                <div class="input-group">
                    <i class="icon-ac_unit"></i>
                    <label>ضریب</label>
                    <input autofocus autocomplete="off" type="text" name="coefficient" value="{{ old('coefficient') }}">
                </div>

            </div>

            <div class="box-footer tal">
                <button type="submit" class="btn-lg btn-success">ذخیره</button>
            </div>

        </form>

    </div>

@endsection
