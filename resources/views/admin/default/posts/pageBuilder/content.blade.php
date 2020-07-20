<div class="row">

    <div class="col-lg-8">

        <div class="sticky-top top55 box box-info">
            @csrf

            <input type="hidden" name="id" value="{{ $id }}">
            <input type="hidden" name="key" value="{{ $key }}">

            <div class="box-header">
                <h3 class="box-title">محتوا</h3>
                <div class="box-tools">
                    <i class="box-tools-icon icon-minus"></i>
                </div>
            </div>

            <div class="box-body">

                @php
                    $boxes = [
                        'box',
                        'box-solid',
                    ];
                    $boxColors = [
                        'box-success',
                        'box-warning',
                        'box-danger',
                        'box-info',
                        'box-purple',
                        'box-pink',
                        'box-teal',
                        'box-primary',
                    ];
                @endphp

                <div class="input-group">
                    <label>قالب</label>
                    <select class="w100" name="template">
                        <option value="none">بدون قالب</option>
                        @foreach ($boxes as $box)
                            <option {{ (isset($values->template) ? selected($values->template, $box) : "") }} value="{{ $box }}">{{ $box }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="input-group">
                    <label>رنگ باکس</label>
                    <select class="w100" name="boxColor">
                        @foreach ($boxColors as $box)
                            <option {{ (isset($values->boxColor) ? selected($values->boxColor, $box) : "") }} value="{{ $box }}">{{ $box }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="input-group">
                    <label>محتوا</label>
                    <textarea class="editor-ckeditor" id="editor-ckeditor" name="content">{{ (isset($values->content) ? $values->content : "") }}</textarea>
                </div>

            </div>

            <div class="box-footer tal">
                <button class="btn-lg btn-success">ذخیره</button>
            </div>

        </form>

        <style>
            .imageBuilder {
                max-width: 100%;
            }
        </style>

    </div>

    </div>

    <div class="col-lg-4">

        @include("admin.posts.pageBuilder.tools.responsive")
        @include("admin.posts.pageBuilder.tools.borderRadius")
        @include("admin.posts.pageBuilder.tools.margin")
        @include("admin.posts.pageBuilder.tools.padding")

    </div>

</div>

<script src="{{ asset('/plugins/ckeditor4/ckeditor.js') }}"></script>
<script>
    CKEDITOR.replace( 'editor-ckeditor' ,{
        height: '250px',
        filebrowserBrowseUrl : '{{ filemanager(["type" => "2", "fldr" => "posts", "key" => md5("postContent")]) }}',
        filebrowserUploadUrl : '{{ filemanager(["type" => "2", "fldr" => "posts", "key" => md5("postContent")]) }}',
        filebrowserImageBrowseUrl : '{{ filemanager(["type" => "1", "fldr" => "posts", "key" => md5("postContent")]) }}'
    });
</script>

