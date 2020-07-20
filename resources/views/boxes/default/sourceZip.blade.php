<div class="box box-primary">

    <div class="box-header">
        <h3 class="box-title">{{ $box['title'] }}</h3>
        <div class="box-tools">
            <i class="box-tools-icon icon-minus"></i>
        </div>
    </div>

    <?php
        $sourceZip = $post->meta('sourceZip');
        $attachment = attachment()->where('id', $post->meta('sourceZip', 'more'))->first();
    ?>
    <div id="sourceZip" class="box-body tac">
        @if(empty($sourceZip))
            <input class="w100" type="hidden" readonly name="sourceZip" value="{{ $sourceZip }}">
            <input class="w100" type="hidden" readonly name="sourceZipId" value="{{ $attachment->id ?? '' }}">
        @endif
        <span class="sourceTitle">{{ $attachment->title ?? 'سورس انتخاب نشده است' }}</span>
    </div>

    <div class="box-footer tac">
        <a callback="sourceZip" classes="zip rar" buttonTitle="ثبت برای سورس" class="btn-lg btn-info w100 uploader-open">بارگذاری و انتخاب سورس</a>
    </div>
    <script>
        function sourceZip(data) {
            $('input[name=sourceZip]').val(data['result']['path']);
            $('input[name=sourceZipId]').val(data['result']['id']);
            $('.sourceTitle').text(data['result']['name']);
        }
    </script>

</div>
