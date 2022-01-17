@extends("admin.{$adminTheme}.master")
@section('title', 'تنظیمات فروشگاه')

@section('content')

    <?php
    formValidations([
        'rules'    => [
            'options.storeCurrency' => 'required',
        ],
        'messages' => [],
    ]);
    ?>

    <div class="row">

        <div class="col-12">

            <form action="{{ route('admin.options.update') }}" method="post" class="box box-info">
                @csrf

                <div class="box-header">
                    <h3 class="box-title">@yield('title')</h3>
                    <div class="box-tools">
                        <i class="box-tools-icon icon-minus"></i>
                    </div>
                </div>

                <div class="box-body">

                    <div class="row">

                        <div class="col-md-2">
                            <div class="input-group">
                                <label>واحد پولی فروشگاه</label>
                                <input type="hidden" name="more[storeCurrency]" value="autoload">
                                <select name="options[storeCurrency]" id="">
                                    @foreach ($currencies as $currency)
                                        <option {{ getOption('storeCurrency') == $currency->code ? 'selected' : '' }} value="{{ $currency->code }}">{{ $currency->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12 mt10"></div>

                        <div class="col-md-2">
                            <div class="input-group">
                                <label>ارسال سریع</label>
                                <input type="hidden" name="more[storeFastSend]" value="autoload">
                                <select name="options[storeFastSend]">
                                    <option {{ selected('1', getOption('storeFastSend')) }} value="1">فعال</option>
                                    <option {{ selected('0', getOption('storeFastSend')) }} value="0">غیر فعال</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="input-group">
                                <label>ارسال عادی</label>
                                <input type="hidden" name="more[storeNormalSend]" value="autoload">
                                <select name="options[storeNormalSend]">
                                    <option {{ selected('1', getOption('storeNormalSend')) }} value="1">فعال</option>
                                    <option {{ selected('0', getOption('storeNormalSend')) }} value="0">غیر فعال</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="input-group">
                                <label>پیش فر</label>
                                <input type="hidden" name="more[storeNormalSend]" value="autoload">
                                <select name="options[storeNormalSend]">
                                    <option {{ selected('1', getOption('storeNormalSend')) }} value="1">فعال</option>
                                    <option {{ selected('0', getOption('storeNormalSend')) }} value="0">غیر فعال</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 mt10"></div>

                        <div class="col-md-2">
                            <div class="input-group">
                                <label>پرداخت اینترنتی</label>
                                <input type="hidden" name="more[storeInternetPayment]" value="autoload">
                                <select name="options[storeInternetPayment]">
                                    <option {{ selected('1', getOption('storeInternetPayment')) }} value="1">فعال</option>
                                    <option {{ selected('0', getOption('storeInternetPayment')) }} value="0">غیر فعال</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="input-group">
                                <label>پرداخت کیف پول</label>
                                <input type="hidden" name="more[storeWalletPayment]" value="autoload">
                                <select name="options[storeWalletPayment]">
                                    <option {{ selected('1', getOption('storeWalletPayment')) }} value="1">فعال</option>
                                    <option {{ selected('0', getOption('storeWalletPayment')) }} value="0">غیر فعال</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="input-group">
                                <label>پرداخت در محل</label>
                                <input type="hidden" name="more[storeLocallyPayment]" value="autoload">
                                <select name="options[storeLocallyPayment]">
                                    <option {{ selected('1', getOption('storeLocallyPayment')) }} value="1">فعال</option>
                                    <option {{ selected('0', getOption('storeLocallyPayment')) }} value="0">غیر فعال</option>
                                </select>
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

@endsection

@section('head-content')

    <script src="/plugins/fancybox/dist/jquery.fancybox.min.js"></script>
    <link rel="stylesheet" href="/plugins/fancybox/dist/jquery.fancybox.min.css">
    <link rel="stylesheet" href="/plugins/filemanager/css/rtl-style.css">

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
