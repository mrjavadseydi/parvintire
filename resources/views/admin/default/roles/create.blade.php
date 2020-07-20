@extends("admin.{$adminTheme}.master")
@section('title', 'افزودن نقش جدید')

@section('content')

    <div class="col-lg-4">

        <form action="{{ route('admin.roles.store') }}" method="post" class="box-solid box-primary">

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
                    <input autocomplete="off" type="text" name="label" value="{{ old('label') }}">
                </div>

                <div class="input-group">
                    <i class="icon-card_membership"></i>
                    <label>نقش <small>(فرمت مجاز : کاراکترهای انگلیسی)</small></label>
                    <input autocomplete="off" type="text" name="name" value="{{ old('name') }}">
                </div>

            </div>

            <div class="box-footer tal">
                <button type="submit" class="btn-lg btn-success">ذخیره</button>
            </div>

        </form>

    </div>


@endsection

@section('footer-content')
    <link rel="stylesheet" href="/plugins/select2/select2.css">
    <script src="/plugins/select2/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.js-example-basic-multiple').select2({
                dir: "rtl",
                closeOnSelect: false,
            });
        });
    </script>
@endsection
