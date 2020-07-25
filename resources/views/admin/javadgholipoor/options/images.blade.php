@extends("admin.{$adminTheme}.master")
@section('title', 'تنظیمات تصاویر')

@section('content')

    <?php
    formValidations([
        'rules'    => [],
        'messages' => [],
    ]);
    formPermission('imagesSetting');
    ?>

    {!! uploader()->validations('png|jpg|jpeg|gif')->load() !!}

    <form action="{{ route('admin.options.update') }}" id="images-form" method="post">

        @csrf

        <input type="hidden" name="back" value="images">

        @foreach(getThemesImages() as $headTitle => $images)
            <div class="card mb-2">

                <div class="card-header">
                    {{ $headTitle }}
                </div>

                <div class="card-body">
                    @foreach($images as $img)
                        <?php
                        $key  = $img['key'];
                        $getOption = options()->where('key', $key)->first();
                        $more = [];
                        $image = '';
                        if ($getOption != null) {
                            $more = json_decode($getOption->more, true);
                            if (!empty($getOption->value)) {
                                $image = $getOption->value;
                            }
                        }
                        ?>
                        <div class="form-group form-attribute">
                            <div class="row">
                                <div class="col-md-5">
                                    <label class="col-form-label">
                                        {{ $img['title'] }}
                                    </label>
                                    <br>
                                    <small class="text-muted">{{ $img['description'] }}</small>
                                    <br>
                                    <input id="{{ $key }}" type="hidden" name="options[{{ $key }}]" value="{{ $image }}" class="form-control">
                                    <span callback="{{ $key }}Callback" classes="png jpg jpeg gif" buttonTitle="ثبت {{ $img['title'] }}" class="d-md-inline-block d-sm-block mb-sm-1 mt-2 uploader-open align-top btn btn-info">بارگذاری</span>
                                    <img style="max-width: 150px;" id="{{ $key }}Img" src="{{ (!empty($image) ? url($image) : '') }}">
                                    <script>
                                        function {{ $key }}Callback(data) {
                                            $('#{{ $key }}').val(data['result']['path']);
                                            $('#{{ $key }}Img').attr('src', data['result']['url']);
                                        }
                                    </script>
                                </div>
                                <div class="col-md-7">

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">لینک</label>
                                        <div class="col-sm-9">
                                            <input value="{{ isset($more['href']) ? $more['href'] : '' }}" type="text" class="form-control ltr text-left" attr="href">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">بازکردن در</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" attr="target">
                                                <option value="_self">همین پنجره</option>
                                                <option {{ isset($more['target']) ? ($more['target'] == '_blank' ? 'selected' : '') : '' }} value="_blank">پنجره جدید</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">alt</label>
                                        <div class="col-sm-9">
                                            <input value="{{ isset($more['alt']) ? $more['alt'] : '' }}" type="text" class="form-control ltr text-left" attr="alt">
                                        </div>
                                    </div>

                                    <input class="more" type="hidden" name="more[{{ $key }}]" value="">

                                </div>
                            </div>
                        </div>
                        <hr>
                    @endforeach

                </div>
            </div>
        @endforeach

        <div class="card">
            <span id="submit" class="btn btn-success">ذخیره</span>
        </div>

        <script>
            $(document).on('click', '#submit', function () {
                $('.form-attribute').each(function (i, obj) {
                    var data = {};
                    data['href'] = $(obj).find('[attr=href]').val();
                    data['target'] = $(obj).find('[attr=target]').val();
                    data['alt'] = $(obj).find('[attr=alt]').val();
                    $(obj).find('.more').val(JSON.stringify(data));
                })
                $('#images-form').submit();
            });
        </script>

    </form>

@endsection
