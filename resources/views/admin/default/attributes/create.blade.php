@extends("admin.{$adminTheme}.master")
@section('title', 'افزودن ویژگی')

@section('content')

    @include('icons.default.icons', ['type' => 'admin'])

    <div class="col-lg-12">

        <form action="{{ route('admin.attributes.store') }}" method="post" class="box-solid box-primary">

            <div class="box-header">
                <h3 class="box-title">@yield('title')</h3>
                <div class="box-tools">
                    <i class="box-tools-icon icon-minus"></i>
                </div>
            </div>

            <div class="box-body">

                @csrf

                <div class="row">
                    <div class="col-md-4">
                        <div class="input-group">
                            <label>عنوان</label>
                            <input autofocus autocomplete="off" type="text" name="title" value="{{ old('title') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group">
                            <label>نوع</label>
                            <select name="type" id="">
                                <option value=""> --- انتخاب --- </option>
                                @foreach ($types as $key => $value)
                                    <option {{ old('type') == $key ? 'selected' : '' }} value="{{ $key }}">{{ $value['title'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group">
                            <label>استفاده در پلن</label>
                            <select name="plan">
                                <option value="0">غیرفعال</option>
                                <option {{ selected(old('plan'), '1') }} value="1">فعال</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="input-group mt10">
                    <label>کلید ها</label>
                    <select id="select2-keys" name="keys[]" multiple="multiple">
                        @foreach((old('keys') ?? []) as $key)
                            <option value="{{ $key }}">{{ $key }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="input-group">
                    <label>نوع پست</label>
                    <select class="select2" name="post_types[]" multiple>
                        @foreach($postTypes as $postType)
                            <option {{ (in_array($postType->type, old('post_types') ?? []) ? 'selected' : '') }} value="{{ $postType->type }}">{{ $postType->label }}</option>
                        @endforeach
                    </select>
                </div>


                <div class="col-12 mt20">
                    <div class="input-group ">
                        <i class="icon-cool"></i>
                        <label for="">آیکون</label>
                        <div class="row">
                            <div class="col-md-2">
                                <span callback="icons" class="btn-lg btn-teal w100 icons-open">انتخاب آیکون</span>
                                <script>
                                    function icons(icon) {
                                        $('.selected-icon').attr('class', 'selected-icon ' + icon);
                                        $('input[name=icon]').val(icon);
                                    }
                                </script>
                            </div>
                            <div class="col-md-1">
                                <i class="selected-icon {{ old('icon') ?? $postType->icon }}"></i>
                                <style>
                                    .selected-icon {
                                        border: 1px solid #bfbfbf;
                                        width: 100%;
                                        display: block;
                                        height: 44px;
                                        border-radius: 3px;
                                        text-align: center;
                                        line-height: 44px;
                                        font-size: 27px !important;
                                    }
                                </style>
                            </div>
                            <div class="col-md-9">
                                <input type="text" name="icon" value="{{ old('icon') ?? $postType->icon }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="input-group mt10">
                    <label>توضیح</label>
                    <textarea name="description" id="" cols="30" rows="10">{{ old('description') }}</textarea>
                </div>

            </div>

            <div class="box-footer tal">
                <button type="submit" class="btn-lg btn-success">ذخیره</button>
            </div>

        </form>

    </div>

    <script>
        $(document).ready(function () {

            $('.select2').select2({
                dir: "rtl",
                closeOnSelect: false,
            });

            $('#select2-keys').select2({
                dir: "rtl",
                tags: true,
                ajax: {
                    url: "{{ route('searchKeys') }}",
                    data: function (params) {
                        return params;
                    },
                    processResults: function (data) {
                        return {
                            results: data.items
                        };
                    }
                }
            });

        });
    </script>


@endsection
