@extends("admin.{$adminTheme}.master")
@section('title', "ویرایش مقدار")

@section('content')

    @include('icons.default.icons', ['type' => 'admin'])

    <div class="col-lg-12">

        <form action="{{ route('admin.attribute-values.update', ['id' => $record->id]) }}" method="post" class="box-solid box-primary">

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
                    <label>کلید‌ها</label>
                    <select class="select2" name="keys[]" multiple>
                        @foreach($keys as $key)
                            <option {{ (in_array($key->id, (old('keys') ?? $valueKeys)) ? 'selected' : '') }} value="{{ $key->id }}">{{ $key->title }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="input-group mt10">
                    <label>توضیح</label>
                    <textarea name="description" id="" cols="30" rows="10">{{ old('description') ?? $record->description }}</textarea>
                </div>



                @if($record->parent == null)

                    <div class="input-group">
                        <label>کلاس ها</label>
                        <input class="ltr tal" type="text" name="attributes[class]" value="{{ old('attributes')['class'] ?? ($valueAttributes['class'] ?? '') }}">
                    </div>

                    <div class="input-group">
                        <label>کد رنگ - مثال :‌ <b>red</b> یا <b>blue</b> یا <b>ff8800</b></label>
                        <input class="ltr tal" type="text" name="attributes[color]" value="{{ old('attributes')['color'] ?? ($valueAttributes['color'] ?? '') }}">
                    </div>

                    @foreach(getProjectValueMetas() as $key => $value)
                        <div class="input-group">
                            <label>{{ $value['title'] }}</label>
                            <input class="ltr tal" type="text" name="attributes[{{ $key }}]" value="{{ old('attributes')[$key] ?? ($valueAttributes[$key] ?? '') }}">
                        </div>
                    @endforeach

                @endif

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

        });
    </script>

@endsection
