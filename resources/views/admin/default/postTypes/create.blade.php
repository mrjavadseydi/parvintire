@extends("admin.{$adminTheme}.master")
@section('title', 'افزودن نوع جدید مطالب')

@section('content')

    <div class="col-lg-4">

        <form action="{{ route('admin.post-types.store') }}" method="post" class="box-solid box-primary">

            <div class="box-header">
                <h3 class="box-title">@yield('title')</h3>
                <div class="box-tools">
                    <i class="box-tools-icon icon-minus"></i>
                </div>
            </div>

            <div class="box-body">

                @csrf

                <div class="row">

                    <div class="col-md-12 mt20">

                        <div class="input-group">
                            <i class="icon-title"></i>
                            <label>عنوان</label>
                            <input autofocus autocomplete="off" type="text" name="label" value="{{ old('label') }}">
                        </div>

                    </div>

                    <div class="col-md-12 mt20">

                        <div class="input-group">
                            <i class="icon-title"></i>
                            <label>نوع (فقط حروف کوچک لاتین مجاز است)</label>
                            <input autofocus autocomplete="off" type="text" name="type" value="{{ old('type') }}">
                        </div>

                    </div>

                </div>

            </div>

            <div class="box-footer tal">
                <button type="submit" class="btn-lg btn-success">ذخیره</button>
            </div>

        </form>

    </div>

@endsection
