@extends("admin.{$adminTheme}.master")
@section('title', "صفحه ساز ({$post->title})")

@section('content')

    <form action="{{ url('admin/page-builder/update') }}" method="post">
        @include("admin.posts.pageBuilder.{$key}")
    </form>

    @include("admin.posts.pageBuilder.tools.click")

    <style>
        .rangeslider {
            box-shadow: none;
            display: inline-block;
            width: 45px !important;
            background: gainsboro;
            height: 25px;
            line-height: 25px;
            text-align: center;
            position: absolute;
            right: 10px;
            top: 2px;
        }
    </style>

    <script>
        styles = {};
        function changeStyles() {
            $.each(styles, function( index, value ) {
                $('.imageBuilder').css(index, value);
            });
        }
    </script>

    <script src="/plugins/fancybox/dist/jquery.fancybox.min.js"></script>
    <link rel="stylesheet" href="/plugins/fancybox/dist/jquery.fancybox.min.css">
    <link rel="stylesheet" href="/plugins/filemanager/css/rtl-style.css">

    <script>

        $('.file-manager').fancybox({
            'width'		: 500,
            'height'	: 400,
            'type'		: 'iframe',
            'autoScale'    	: true
        });

        slider = null;
        multiple = null;

        function responsive_filemanager_callback(field_id){
            var value = jQuery('#'+field_id).val();
            if (field_id == 'slider') {
                slider.find('.img').html('');
                slider.find('.img').append('<img height="100" src="' + '{{ uploadUrl() }}' + value + '">');
                slider.find('.img').append('<input type="hidden" value="' + value + '">');
                sortInputs();
            } else if (field_id == 'multiple') {
                multiple.find('.img').html('');
                multiple.find('.img').append('<img height="100" src="' + '{{ uploadUrl() }}' + value + '">');
                multiple.find('.img').append('<input type="hidden" value="' + value + '">');
                sortInputs();
            } else {
                $('#image-' + field_id).attr('src', '{{ uploadUrl() }}' + value);
                $('input[name="'+field_id+'"]').val(value);
            }
        }

    </script>

@endsection
