@extends("admin.{$adminTheme}.master")
@section('title', 'ویرایش نوع مطلب ' . "({$postType->label})")

@section('content')

    @include('icons.default.icons', ['type' => 'admin'])
    <div class="col-lg-12">

        <form action="{{ route('admin.post-types.update', $postType) }}" method="post" class="box-solid box-primary">

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

                    <div class="col-md-2 mt20">
                        <div class="input-group">
                            <i class="icon-title"></i>
                            <label>عنوان</label>
                            <input autofocus autocomplete="off" type="text" name="label" value="{{ old('label') ?? $postType->label }}">
                        </div>
                    </div>

                    <div class="col-md-2 mt20">
                        <div class="input-group">
                            <i class="icon-title"></i>
                            <label>جمع عنوان</label>
                            <input autofocus autocomplete="off" type="text" name="total_label" value="{{ old('total_label') ?? $postType->total_label }}">
                        </div>
                    </div>

                    <div class="col-md-2 mt20">
                        <div class="input-group">
                            <label>نوع (لاتین)</label>
                            <input autofocus autocomplete="off" type="text" name="type" value="{{ old('type') ?? $postType->type }}">
                        </div>
                    </div>

                    <div class="col-md-1 mt20">
                        <div class="input-group">
                            <label>جستجو</label>
                            <select name="search">
                                <option value="1">فعال</option>
                                <option {{ selected('0', old('search') ?? $postType->search) }} value="0">غیرفعال</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-1 mt20">
                        <div class="input-group">
                            <label>نقشه سایت</label>
                            <select name="sitemap">
                                <option value="1">فعال</option>
                                <option {{ selected('0', old('sitemap') ?? $postType->sitemap) }} value="0">غیرفعال</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-4 mt20">
                        <div class="input-group ">
                            <i class="icon-cool"></i>
                            <label for="">آیکون</label>
                            <div class="row">
                                <div class="col-md-5">
                                    <span callback="icons" class="btn-lg btn-teal w100 icons-open">انتخاب آیکون</span>
                                    <script>
                                        function icons(icon) {
                                            $('.selected-icon').attr('class', 'selected-icon ' + icon);
                                            $('input[name=icon]').val(icon);
                                        }
                                    </script>
                                </div>
                                <div class="col-md-2">
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
                                <div class="col-md-5">
                                    <input type="text" name="icon" value="{{ old('icon') ?? $postType->icon }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mt20">

                        <div class="input-group">
                            <i class="icon-card_membership"></i>
                            <label>باکس ها</label>
                        </div>
                        <ul class="checkbox row">
                            <?php
                                $radios = [];
                            ?>
                            @foreach($postBoxes as $name => $value)
                                <?php
                                if (isset($value['radio'])) {
                                    $radios[$value['radio']] = true;
                                }
                                $check = (in_array($name, old("boxes") ?? ($selectedPostboxes ?? [])) ? true : false );
                                ?>
                                <li class="col-lg-2 col-md-6">
                                    <label
                                        style="{{ ($check ? '' : 'color: #ea4b4b !important;' ) }}"
                                        for="for-{{ $name }}">
                                        <input radio="{{ $value['radio'] ?? '' }}" {{ ($check ? 'checked' : '' ) }}
                                               id="for-{{ $name }}"
                                               value="{{ $name }}"
                                               name="boxes[]"
                                               type="checkbox"> {{ $value['title'] }}</label>
                                </li>
                            @endforeach
                        </ul>

                        <script>
                            @foreach($radios as $radio => $true)
                            $(document).on('change', '[radio={{ $radio }}]', function () {
                                var checked = true;
                                if (!$(this).prop('checked')) {
                                    checked = false;
                                }
                                $('[radio={{ $radio }}]').prop('checked', false);
                                if (checked) {
                                    $(this).prop('checked', true);
                                }
                            });
                            @endforeach
                        </script>

                    </div>

                    <div class="row col-12 mt20">

                        <div class="col-md-2 mt20">
                            <div class="input-group">
                                <label>تعداد فهرست</label>
                                <input class="ltr iransans" autocomplete="off" type="text" name="metas[menus][count]" value="{{ old('metas.menus.count') ?? ($metas['menus']['count'] ?? '') }}">
                            </div>
                        </div>

                        <div class="col-md-4 mt20">
                            <div class="input-group">
                                <label>الگوی پیام تلگرام</label>
                                <textarea class="iransans" name="metas[telegram_message_pattern]">{{ old('metas.telegram_message_pattern') ?? ($metas['telegram_message_pattern'] ?? '') }}</textarea>
                            </div>
                        </div>

                    </div>

                    @include(includeAdmin('postTypes.metas.metas'))

                    <div class="col-12 mt20">

                        <div class="input-group">
                            <i class="icon-card_membership"></i>
                            <label>اعتبارسنجی آپلودر</label>
                        </div>

                        <div class="validations ltr">
                            @if(empty($validations))
                                <div class="row mb10">
                                    <div class="col-md-2">
                                        <input placeholder="key" class="w100 tac" type="text" name="validations[thumbnail][key]" value="thumbnail">
                                    </div>
                                    <div class="col-md-2">
                                        <select name="validations[thumbnail][in]" class="w100">
                                            <option value="1">upload to public_html</option>
                                            <option value="2">upload to root</option>
                                            <option value="3">upload to ftp</option>
                                        </select>
                                    </div>
                                    <div class="col-md-8">
                                        <input placeholder="validations" class="w100" type="text" name="validations[thumbnail][validations]" value="mimes:png,jpg,jpeg,gif,PNG,JPG,JPEG,GIF|min:0|max:4096">
                                    </div>
                                </div>
                                <div class="row mb10">
                                    <div class="col-md-2">
                                        <input placeholder="key" class="w100 tac" type="text" name="validations[gallery][key]" value="gallery">
                                    </div>
                                    <div class="col-md-2">
                                        <select name="validations[gallery][in]" class="w100">
                                            <option value="1">upload to public_html</option>
                                            <option value="2">upload to root</option>
                                            <option value="3">upload to ftp</option>
                                        </select>
                                    </div>
                                    <div class="col-md-8">
                                        <input placeholder="validations" class="w100" type="text" name="validations[gallery][validations]" value="mimes:png,jpg,jpeg,gif,PNG,JPG,JPEG,GIF|min:0|max:4096">
                                    </div>
                                </div>
                                <div class="row mb10">
                                    <div class="col-md-2">
                                        <input placeholder="key" class="w100 tac" type="text" name="validations[links][key]" value="links">
                                    </div>
                                    <div class="col-md-2">
                                        <select name="validations[links][in]" class="w100">
                                            <option value="1">upload to public_html</option>
                                            <option value="2">upload to root</option>
                                            <option value="3">upload to ftp</option>
                                        </select>
                                    </div>
                                    <div class="col-md-8">
                                        <input placeholder="validations" class="w100" type="text" name="validations[links][validations]" value="mimes:png,jpg,jpeg,gif,PNG,JPG,JPEG,GIF,zip,rar,pdf,mpga,mp4||min:0|max:51200">
                                    </div>
                                </div>
                            @else
                                @foreach($validations as $name => $values)
                                    <div class="row mb10">
                                        <div class="col-md-2">
                                            <input placeholder="key" class="w100 {{ in_array($name, ['thumbnail', 'gallery']) ? '' : 'name-event' }} tac" type="text" name="validations[{{ $name }}][key]" value="{{ $values->key }}">
                                        </div>
                                        <div class="col-md-2">
                                            <select name="validations[{{ $name }}][in]" class="select w100">
                                                <option value="1">upload to public_html</option>
                                                <option {{ selected('2', $values->in) }} value="2">upload to root</option>
                                                <option {{ selected('3', $values->in) }} value="3">upload to ftp</option>
                                            </select>
                                        </div>
                                        <div class="col-md-8">
                                            <input placeholder="validations" class="w100 input" type="text" name="validations[{{ $name }}][validations]" value="{{ $values->validations }}">
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="ltr">
                            <span class="btn btn-info add-validation">افزودن</span>
                            <script>
                                $(document).on('click', '.add-validation', function () {
                                    $('.validations').append(
                                        '<div class="row mb10">\n' +
                                        '    <div class="col-md-2">\n' +
                                        '        <input placeholder="key" class="w100 name-event tac" type="text" name="" value="">\n' +
                                        '    </div>\n' +
                                        '    <div class="col-md-2">\n' +
                                        '        <select name="" class="select w100">\n' +
                                        '            <option value="1">upload to public_html</option>\n' +
                                        '            <option value="2">upload to root</option>\n' +
                                        '            <option value="3">upload to ftp</option>\n' +
                                        '        </select>\n' +
                                        '    </div>\n' +
                                        '    <div class="col-md-8">\n' +
                                        '        <input placeholder="validations" class="w100 input" type="text" name="" value="">\n' +
                                        '    </div>\n' +
                                        '</div>'
                                    );
                                });
                                $(document).on('keyup', '.name-event', function () {
                                    var name = $(this).val();
                                    var parent = $(this).closest('.row');
                                    var uploadIn = parent.find('.select');
                                    var validations = parent.find('.input');
                                    if(name == '') {
                                        $(this).attr('name', '');
                                        uploadIn.attr('name', '');
                                        validations.attr('name', '');
                                    } else {
                                        $(this).attr('name', 'validations['+name+'][key]');
                                        uploadIn.attr('name', 'validations['+name+'][in]');
                                        validations.attr('name', 'validations['+name+'][validations]');
                                    }
                                });
                            </script>
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
