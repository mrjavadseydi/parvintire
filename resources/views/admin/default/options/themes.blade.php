@extends("admin.{$adminTheme}.master")
@section('title', 'قالب')

@section('content')

    <?php
    formValidations([
        'rules'    => [],
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

                        @foreach ($typeThemes as $type => $themes)

                            <div class="col-4 mt10">
                                <div class="input-group">
                                    <label>{{ $type }}</label>
                                    <input type="hidden" name="more[{{ $type }}Theme]" value="autoload">
                                    <select name="options[{{ $type }}Theme]">
                                        @foreach ($themes as $theme)
                                            <option {{ (getOption("{$type}Theme") == $theme ? 'selected' : '') }} value="{{ $theme }}">{{ $theme }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        @endforeach

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
