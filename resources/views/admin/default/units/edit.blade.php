@extends("admin.{$adminTheme}.master")
@section('title', "ویرایش مشخصات {$record->title}")

@section('content')

    <div class="col-lg-12">

        <form action="{{ route('admin.units.update', ['id' => $record->id]) }}" method="post" class="box-solid box-primary">

            <div class="box-header">
                <h3 class="box-title">@yield('title')</h3>
                <div class="box-tools">
                    <i class="box-tools-icon icon-minus"></i>
                </div>
            </div>

            <div class="box-body">

                @csrf
                @method('patch')

                <div class="input-group">
                    <i class="icon-title"></i>
                    <label>عنوان</label>
                    <input autofocus autocomplete="off" type="text" name="title" value="{{ old('title') ?? $record->title }}">
                </div>

                <div class="input-group">
                    <i class="icon-ac_unit"></i>
                    <label>ضریب</label>
                    <input autofocus autocomplete="off" type="text" name="coefficient" value="{{ old('coefficient')?? $record->coefficient }}">
                </div>

            </div>

            <div class="box-footer tal">
                <button type="submit" class="btn-lg btn-success">ذخیره</button>
            </div>

        </form>

    </div>

@endsection

@section('head-content')

    <script src="/plugins/ckeditor4/ckeditor.js"></script>
    <script src="/plugins/fancybox/dist/jquery.fancybox.min.js"></script>
    <link rel="stylesheet" href="/plugins/fancybox/dist/jquery.fancybox.min.css">
    <link rel="stylesheet" href="/plugins/filemanager/css/rtl-style.css">

@endsection
