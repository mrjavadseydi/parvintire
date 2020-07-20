<div class="box box-purple">
    <div class="box-header">
        <h3 class="box-title">{{ $box['title'] }}</h3>
        <div class="box-tools">
            <i class="box-tools-icon icon-minus"></i>
        </div>
    </div>

    <?php
        $sourceZip = $post->meta('scriptJs');
        $attachment = attachment()->where('id', $post->meta('scriptJs', 'more'))->first();
    ?>
    <div id="sourceZip" class="box-body tac">
        @if(empty($sourceZip))
            <input class="w100" type="hidden" readonly name="scriptJs" value="{{ $sourceZip }}">
            <input class="w100" type="hidden" readonly name="scriptJsId" value="{{ $attachment->id ?? '' }}">
        @endif
        <span class="sourceTitle">{{ $attachment->title ?? 'اسکریپت انتخاب نشده است' }}</span>
    </div>

    <div class="box-footer tac">
        <a callback="scriptZip" classes="js" buttonTitle="ثبت برای سورس" class="btn-lg btn-info w100 uploader-open">بارگذاری و انتخاب سورس</a>
    </div>
    <script>
        function scriptZip(data) {
            $('input[name=scriptJs]').val(data['result']['path']);
            $('input[name=scriptJsId]').val(data['result']['id']);
            $('.scriptTitle').text(data['result']['name']);
        }
    </script>
</div>
