@extends("admin.{$adminTheme}.master")
@section('title', "ویرایش کلید {$record->title}")

@section('content')

    @include('icons.default.icons', ['type' => 'admin'])

    <div class="col-lg-12">

        <form action="{{ route('admin.attribute-keys.update', ['id' => $record->id]) }}" method="post" class="box-solid box-primary">

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
                        <div class="col-md-8">

                            <div class="input-group">
                                <label for="">آیکون</label>
                                <div class="row">
                                    <div class="col-md-3">
                                        <span callback="icons" class="btn-lg btn-teal w100 icons-open">انتخاب آیکون</span>
                                        <script>
                                            function icons(icon) {
                                                $('.selected-icon').attr('class', 'selected-icon ' + icon);
                                                $('input[name=icon]').val(icon);
                                            }
                                        </script>
                                    </div>
                                    <div class="col-md-2">
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
                                    <div class="col-md-7">
                                        <input type="text" name="icon" value="{{ old('icon') ?? $record->icon }}">
                                    </div>
                                </div>
                            </div>

                        </div>
                    @endif

                </div>

                @if($record->parent == null)

                    <div class="input-group mt10">
                        <label>ویژگی‌ها</label>
                        <select class="select2" name="attributes[]" multiple>
                            @foreach($attributes as $attribute)
                                <option {{ (in_array($attribute->id, old('attributes') ?? $keyAttributes) ? 'selected' : '') }} value="{{ $attribute->id }}">{{ $attribute->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="input-group mt10">
                        <label>مقادیر</label>
                        <select id="select2-values" name="values[]" multiple>
                            @foreach($keyValues as $value)
                                <option selected value="{{ $value->title }}">{{ $value->title }}</option>
                            @endforeach
                        </select>
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

    </div>

    <script>
        $(document).ready(function () {

            $('.select2').select2({
                dir: "rtl",
                closeOnSelect: false,
            });

            $('#select2-values').select2({
                dir: "rtl",
                tags: true,
                ajax: {
                    url: "{{ route('searchValues') }}",
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
