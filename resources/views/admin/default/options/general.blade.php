@extends("admin.{$adminTheme}.master")
@section('title', 'تنظیمات عمومی')

@section('content')

    <?php
    formValidations([
        'rules'    => [],
        'messages' => [],
    ]);
    ?>

    <?php
        $langs = [
            'fa' => 'فارسی'
        ];

        if ($languages->count() > 0) {
            foreach ($languages as $language) {
                $langs[$language->lang] = $language->title;
            }
        }
    ?>

    @foreach($langs as $lang => $langTitle)
        <div class="row">

            <div class="col-12">

                <form action="{{ route('admin.options.update') }}" method="post" class="box box-info">
                    @csrf
                    <input type="hidden" name="back" value="general">
                    <input type="hidden" name="lang" value="{{ $lang }}">
                    <div class="box-header">
                        <h3 class="box-title">@yield('title') {{ count($langs) > 1 ? '('.$langTitle.')' : '' }}</h3>
                        <div class="box-tools">
                            <i class="box-tools-icon icon-minus"></i>
                        </div>
                    </div>

                    <div class="box-body">

                        <div class="row">

                            <div class="col-md-3">
                                <div class="input-group">
                                    <i class="icon-title"></i>
                                    <label>نام سایت</label>
                                    <input type="hidden" name="more[name]" value="autoload">
                                    <input type="text" name="options[name]" value="{{ siteName($lang) }}">
                                </div>
                            </div>

                            <div class="col-md-9">
                                <div class="input-group">
                                    <i class="icon-title"></i>
                                    <label>کلمات کلیدی سایت</label>
                                    <input type="hidden" name="more[keywords]" value="autoload">
                                    <input type="text" name="options[keywords]" value="{{ siteKeywords($lang) }}">
                                </div>
                            </div>

                            <div class="col-md-12 mt10">
                                <div class="input-group">
                                    <i class="icon-description"></i>
                                    <label>توضیحات سایت</label>
                                    <input type="hidden" name="more[description]" value="autoload">
                                    <textarea name="options[description]" id="" cols="30" rows="10">{{ siteDescription($lang) }}</textarea>
                                </div>
                            </div>

                            <div class="col-md-12 mt10">

                                <div class="row">

                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <i class="icon-user-tie"></i>
                                            <label>نام مدیر سایت</label>
                                            <input type="hidden" name="more[adminName]" value="autoload">
                                            <input type="text" name="options[adminName]" value="{{ siteAdminName($lang) }}">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <i class="icon-user-tie"></i>
                                            <label>نام خانوادگی مدیر سایت</label>
                                            <input type="hidden" name="more[adminFamily]" value="autoload">
                                            <input type="text" name="options[adminFamily]" value="{{ siteAdminFamily($lang) }}">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <i class="icon-mobile"></i>
                                            <label>موبایل</label>
                                            <input type="hidden" name="more[mobile]" value="autoload">
                                            <input class="ltr" type="text" name="options[mobile]" value="{{ siteMobile() }}">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <i class="icon-phone2"></i>
                                            <label>تلفن تماس</label>
                                            <input type="hidden" name="more[phone]" value="autoload">
                                            <input class="ltr" type="text" name="options[phone]" value="{{ sitePhone($lang) }}">
                                        </div>
                                    </div>

                                </div>

                            </div>

                            <div class="col-md-12 mt10">

                                <div class="row">

                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <i class="icon-perm_phone_msg"></i>
                                            <label>فکس</label>
                                            <input type="hidden" name="more[fax]" value="autoload">
                                            <input class="ltr" type="text" name="options[fax]" value="{{ siteFax($lang) }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <i class="icon-mail_outline"></i>
                                            <label>ایمیل</label>
                                            <input type="hidden" name="more[email]" value="autoload">
                                            <input class="ltr" type="text" name="options[email]" value="{{ siteEmail() }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <i class="icon-markunread_mailbox"></i>
                                            <label>کد پستی</label>
                                            <input type="hidden" name="more[postalCode]" value="autoload">
                                            <input class="ltr" type="text" name="options[postalCode]" value="{{ sitePostalCode($lang) }}">
                                        </div>
                                    </div>

                                </div>

                            </div>

                            <div class="col-md-12 mt10">
                                <div class="input-group">
                                    <i class="icon-directions"></i>
                                    <label>آدرس</label>
                                    <input type="hidden" name="more[address]" value="autoload">
                                    <textarea name="options[address]" id="" cols="30" rows="10">{{ siteAddress($lang) }}</textarea>
                                </div>
                            </div>

                            <div class="col-md-12 mt10">
                                <div class="input-group">
                                    <i class="icon-copyright"></i>
                                    <label>متن کپی رایت</label>
                                    <input type="hidden" name="more[copyright]" value="autoload">
                                    <input type="text" name="options[copyright]" value="{{ siteCopyright($lang) }}">
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="box-footer tal">
                        <button class="btn-lg btn-success">ذخیره</button>
                    </div>

                </form>

            </div>

        </div>
    @endforeach


@endsection

@section('head-content')

{{--    <script src="/plugins/fancybox/dist/jquery.fancybox.min.js"></script>--}}
{{--    <link rel="stylesheet" href="/plugins/fancybox/dist/jquery.fancybox.min.css">--}}
{{--    <link rel="stylesheet" href="/plugins/filemanager/css/rtl-style.css">--}}

@endsection

@section('footer-content')

    <script>

        $('.file-manager').fancybox({
            'width'		: 500,
            'height'	: 400,
            'type'		: 'iframe',
            'autoScale'    	: true
        });

        function responsive_filemanager_callback(field_id){
             var value = $('#'+field_id).val();
            $('#image-' + field_id).attr('src', '{{ uploadUrl() }}' + value)
        }

    </script>

@endsection
