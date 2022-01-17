@extends("admin.{$adminTheme}.master")
@section('title', "ویرایش {$record->title}")

@section('content')

    @include('icons.default.icons', ['type' => 'admin'])

    <div class="col-lg-12">

        <form action="{{ route('admin.attributes.update', ['id' => $record->id]) }}" method="post" class="box-solid box-primary">

            <div class="box-header">
                <h3 class="box-title">@yield('title')</h3>
                <div class="box-tools">
                    <i class="box-tools-icon icon-minus"></i>
                </div>
            </div>

            <div class="box-body">

                @csrf
                @method('patch')

                <div class="row">
                    <div class="{{ $record->parent == null ? 'col-md-4' : 'col-12' }}">
                        <div class="input-group">
                            <label>عنوان</label>
                            <input autofocus autocomplete="off" type="text" name="title" value="{{ old('title') ?? $record->title }}">
                        </div>
                    </div>

                    @if($record->parent == null)

                        <div class="col-md-4">
                            <div class="input-group">
                                <label>نوع</label>
                                <select name="type" id="">
                                    <option value=""> --- انتخاب --- </option>
                                    @foreach ($types as $key => $value)
                                        <option {{ (old('type') ?? $record->type) == $key ? 'selected' : '' }} value="{{ $key }}">{{ $value['title'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="input-group">
                                <label>استفاده در پلن</label>
                                <select name="plan">
                                    <option value="0">غیرفعال</option>
                                    <option {{ selected(old('plan') ?? $attributePlan, '1') }} value="1">فعال</option>
                                </select>
                            </div>
                        </div>

                    @endif

                </div>

                @if($record->parent == null)

                    <div class="input-group mt10">
                        <label>کلید ها</label>
                        <select id="select2-keys" name="keys[]" multiple="multiple">
                            @foreach ($attributeKeys as $key)
                                <option selected value="{{ $key->title }}">{{ $key->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="input-group">
                        <label>نوع پست</label>
                        <select class="select2" name="post_types[]" multiple>
                            @foreach($postTypes as $postType)
                                <option {{ (in_array($postType->type, (old('post_types') ?? $attributePostTypes) ?? []) ? 'selected' : '') }} value="{{ $postType->type }}">{{ $postType->label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="input-group mt10">
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
                            <i class="selected-icon {{ old('icon') ?? $record->icon }}"></i>
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
                            <input type="text" name="icon" value="{{ old('icon') ?? $record->icon }}">
                        </div>
                    </div>
                </div>

                @endif

                <div class="input-group mt10">
                    <label>توضیح</label>
                    <textarea name="description" id="" cols="30" rows="10">{{ old('description') ?? $record->description }}</textarea>
                </div>

            </div>

            <div class="box-footer tal">
                <button type="submit" class="btn-lg btn-success">ذخیره</button>
            </div>

        </form>

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

    </div>

@endsection

@section('head-content')

    <script src="/plugins/ckeditor4/ckeditor.js"></script>
    <script src="/plugins/fancybox/dist/jquery.fancybox.min.js"></script>
    <link rel="stylesheet" href="/plugins/fancybox/dist/jquery.fancybox.min.css">
    <link rel="stylesheet" href="/plugins/filemanager/css/rtl-style.css">

@endsection
