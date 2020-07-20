@extends("admin.{$adminTheme}.master")
@section('title', 'تنظیمات پروتکل')

@section('content')

    <?php
    formValidations([
        'rules'    => [],
        'messages' => [],
    ]);
    ?>

    <div class="row">

        <div class="col-12">

            <form id="httpProtocolForm" action="{{ route('admin.options.update') }}" method="post" class="box box-info">
                @csrf
                <input type="hidden" name="back" value="general">
                <div class="box-header">
                    <h3 class="box-title">@yield('title')</h3>
                    <div class="box-tools">
                        <i class="box-tools-icon icon-minus"></i>
                    </div>
                </div>

                <div class="box-body">
                    <div class="row">
                        @php
                        $domains = [];
                        foreach (json_decode(getOption('httpProtocol'), true) ?? [] as $key => $value) {
                            $domains[$key] = $value;
                        }
                        @endphp
                        @foreach (config('domains') as $domain)
                            <div class="col-md-6 mt20">
                                <div class="input-group form-domain">
                                    <label>از <b style="color: deeppink">{{ $domain }}</b> به</label>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <input class="from" type="hidden" value="{{ $domain }}">
                                            <select class="to">
                                                <option value="">انتخاب</option>
                                                @foreach (config('domains') as $d)
                                                    <option {{ isset($domains[$domain]) ? ($domains[$domain]['to'] == $d ? 'selected' : '') : '' }} value="{{ $d }}">{{ $d }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <select class="http">
                                                <option value="toHttp">toHttp</option>
                                                <option {{ isset($domains[$domain]) ? ($domains[$domain]['http'] == 'toHttps' ? 'selected' : '') : '' }} value="toHttps">toHttps</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <select class="www">
                                                <option value="toWWW">toWWW</option>
                                                <option {{ isset($domains[$domain]) ? ($domains[$domain]['www'] == 'toNonWWW' ? 'selected' : '') : '' }} value="toNonWWW">toNonWWW</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="box-footer tal">
                    <input id="httpProtocolInput" type="hidden" name="options[httpProtocol]" value="{{ getOption('httpProtocol') }}">
                    <input type="hidden" name="more[httpProtocol]" value="autoload">
                    <span id="submit" class="btn-lg btn-success">ذخیره</span>
                </div>

                <script>
                    $(document).on('click', '#submit', function () {
                        var data = {};
                        $('.form-domain').each(function (i, obj) {
                            var from = $(obj).find('.from').val();
                            var to = $(obj).find('.to').val();
                            if (to != '') {
                                data[from] = {};
                                data[from]['to'] = to;
                                data[from]['http'] = $(obj).find('.http').val();
                                data[from]['www'] = $(obj).find('.www').val();
                            }
                        })
                        $('#httpProtocolInput').val(JSON.stringify(data));
                        $('#httpProtocolForm').submit();
                    });
                </script>

            </form>

        </div>

    </div>

@endsection
