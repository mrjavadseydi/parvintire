<div class="box box-danger">

    <div class="box-header">
        <h3 class="box-title">{{ $box['title'] }}</h3>
        <div class="box-tools">
            <i class="box-tools-icon icon-minus"></i>
        </div>
    </div>

    <?php
        $preview = $post->meta('preview');
    ?>
    <div id="preview" class="box-body tac">
        <input type="hidden" name="preview" value="{{ $preview }}">
        <input type="hidden" name="previewId" value="{{ $post->meta('preview', 'more') }}">
        <video width="100%" controls>\n' +
            <source src="{{ (empty($preview) ? '' : url($preview)) }}">
            Your browser does not support the video tag.
        </video>
    </div>

    <div class="box-footer tac">
        <a callback="preview" classes="mp4" buttonTitle="ثبت برای پیشنمایش" class="btn-lg btn-info w100 uploader-open">بارگذاری و انتخاب ویدئو</a>
    </div>
    <script>
        function preview(data) {
            var preview = $('#preview');
            preview.html(
                '<input type="hidden" name="preview" value="'+data['result']['path']+'">\n' +
                '<input type="hidden" name="previewId" value="'+data['result']['id']+'">\n' +
                '<video width="100%" controls>\n' +
                '<source src="' + data['result']['url'] + '">\n' +
                'Your browser does not support the video tag.\n' +
                '</video>'
            );
        }
    </script>

</div>
