<div class="box box-teal">

    <div class="box-header">
        <h3 class="box-title">{{ $box['title'] }}</h3>
        <div class="box-tools">
            <i class="box-tools-icon icon-minus"></i>
        </div>
    </div>

    <div id="thumbnail" class="box-body tac">
        <div class="image">
            <input readonly type="hidden" name="thumbnail" id="image" value="{{ (old('thumbnail') ? url(old('thumbnail')) : $post->originalThumbnail()) }}">
            <input readonly type="hidden" name="thumbnailId" value="{{ old('thumbnailId') ?? $post->thumbnailId() }}">
            <img id="image-image"
                 class="image-thumbnail"
                 style="max-width: 100%;"
                 src="{{ $post->originalThumbnail() }}"
                 alt="{{ $post->title }}">
        </div>
    </div>

    <div class="box-footer tac">
        <a key="thumbnail" callback="thumbnail" buttonTitle="ثبت برای تصویر شاخص" class="btn-lg btn-info w100 uploader-open">بارگذاری و انتخاب تصویر</a>
    </div>
    <script>
        function thumbnail(data) {
            $('#image-image').attr('src', data['result']['url']);
            $('input[name="thumbnail"]').val(data['result']['path']);
            $('input[name="thumbnailId"]').val(data['result']['id']);
            if ($('input[name=thumbnailChanged]').length == 0) {
                $('#thumbnail').append('<input type="hidden" name="thumbnailChanged" value="true">');
            }
        }
    </script>

</div>
