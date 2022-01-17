@extends("admin.{$adminTheme}.master")
@section('title', 'تنظیمات قالب')

@section('content')

    <?php
    formValidations([
        'rules'    => [],
        'messages' => [],
    ]);
    formPermission('themeValuesSetting');

    $defaults = [
        'site-title' => 'عنوان صفحه اصلی',
        'telegram-bot-token' => 'توکن ربات تلگرام',
        'telegram-group-chat-id' => 'چت آيدی گروه',
        'telegram-channel-chat-id' => 'چت آيدی کانال',
        'robots' => 'محدودیت های robots.txt',
        'admin-theme-color' => 'رنگ اکتیویتی  قالب مدیریت در موبایل',
        'template-theme-color' => 'رنگ اکتیویتی  قالب سایت در موبایل',
        'errors-theme-color' => 'رنگ اکتیویتی  قالب ارور در موبایل',
    ];

    if (file_exists(base_path('resources/views/admin/seo/theme.json'))) {
        foreach ([
             'site-brand' => 'برند سایت',
             'site-type' => 'Organization Corporation EducationalOrganization GovernmentOrganization LocalBusiness NGO PerformingGroup SportsTeam',
             'site-city' => 'Site City',
             'site-region' => 'Site Region',
             'site-country' => 'Site Country',
             'site-latitude' => 'Site Lat',
             'site-longitude' => 'Site Lng',
             'site-longitude' => 'Site Lng',
             'price-range' => 'واحد قیمت (IRR)',
             'site-openingHours' => "ساعات کاری<br>Mo 01:00-01:00 Tu 07:30-09:30 We 17:00-23:30 Th 01:00-01:00 Fr 01:00-01:00 Sa 01:00-01:00 Su 07:30-11:00",
             'site-menu' => 'آیدی منوی اصلی سایت',
             'site-sameAs-menu' => 'آیدی منوی شبکه های اجتماعی',
        ] as $k => $v) {
            $defaults[$k] = $v;
        }
    }

    ?>

    <form action="{{ route('admin.options.update') }}" id="images-form" method="post">

        @csrf

        <input type="hidden" name="back" value="themeValues">

        <h4 class="mt10 mb10">تنظیمات پیشفرض</h4>
        <div class="card mb-2">
            @foreach($defaults as $key => $value)
                <div class="card-body mb15">
                    <div class="form-group form-attribute">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">{{ $value }}
                                        <br>
                                        <small class="text-muted"></small>
                                    </label>
                                    <div class="col-sm-8">
                                        <input name="options[{{ $key }}]" value="{{ old($key) ?? getOption($key) }}" type="text">
                                        <input name="more[{{ $key }}]" value="autoload" type="hidden">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @foreach(getThemesValues() as $headTitle => $images)

            <h4 class="mt10 mb10">{{ $headTitle }}</h4>

            <div class="card mb-2">

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
                    <div class="card-body mb15">
                        <div class="form-group form-attribute">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">{{ $img['title'] }}
                                            <br>
                                            <small class="text-muted">{{ $img['description'] }}</small>
                                        </label>
                                        <div class="col-sm-8">
                                            <input class="{{ isset($img['classes']) ? $img['classes'] : '' }}" name="options[{{ $key }}]" value="{{ old($key) ?? getOption($key) }}" type="text">
                                            <input name="more[{{ $key }}]" value="{{ $img['more'] }}" type="hidden">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach

        <div class="card">
            <button id="submit" class="btn btn-success">ذخیره</button>
        </div>

    </form>

    <style>
        .card {
            background-color: white;
            padding: 10px;
            border-radius: 3px;
            margin-bottom: 10px;
        }

        input , select {
            width: 100%;
            margin-bottom: 5px;
        }
    </style>

@endsection
