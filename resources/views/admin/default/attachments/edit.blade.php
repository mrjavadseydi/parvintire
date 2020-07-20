@extends("admin.{$adminTheme}.master")
@section('title', "ویرایش فایل {$attachment->title}")

@section('content')

    <div class="col-lg-12">

        <form action="{{ route('admin.attachments.update', ['id' => $attachment->id]) }}" method="post" class="box-solid box-primary">

            <div class="box-header">
                <h3 class="box-title">@yield('title')</h3>
                <div class="box-tools">
                    <i class="box-tools-icon icon-minus"></i>
                </div>
            </div>

            <div class="box-body">

                @csrf

                <div class="row">

                    <div class="col-12">
                        <div class="input-group">
                            <label>عنوان</label>
                            <input autofocus autocomplete="off" type="text" name="title" value="{{ old('title') ?? $attachment->title }}">
                        </div>
                    </div>

                    <div class="col-12 mt25">
                        <div class="input-group">
                            <label>توضیح</label>
                            <textarea class="editor-ckeditor" id="editor-ckeditor" name="description">{{ old('description') ?? $attachment->description }}</textarea>
                            <script>
                                CKEDITOR.replace( 'editor-ckeditor' ,{
                                    filebrowserBrowseUrl : '/plugins/filemanager/dialog.php?type=2&akey={{ md5(date('y-m-d')) }}&editor=ckeditor&fldr=',
                                    filebrowserUploadUrl : '/plugins/filemanager/dialog.php?type=2&akey={{ md5(date('y-m-d')) }}&editor=ckeditor&fldr=',
                                    filebrowserImageBrowseUrl : '/plugins/filemanager/dialog.php?type=1&akey={{ md5(date('y-m-d')) }}&editor=ckeditor&fldr='
                                });
                            </script>
                        </div>
                    </div>

                    @if($attachment->type == 'video')

                        <div class="col-12 mt25">
                            <div class="input-group">
                                <label>زمان ویدئو (ثانیه)</label>
                                <br>
                                <input value="{{ $attachment->duration }}" style="width: 200px" name="duration" type="text">
                            </div>
                        </div>

                        {!! uploader()->relation('attachment', $attachment->id)->validations('jpeg|jpg|png')->theme('default')->load() !!}

                        <div class="col-12 mt20">

                            <div class="row">

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label>پوستر</label>
                                        <span callback="poster" classes="jpg jpeg png" class="btn btn-info uploader-open">انتخاب پوستر</span>
                                        <br>
                                        <br>
                                        <script>
                                            function poster(data) {
                                                if (data.status == "success") {
                                                    $('img.poster').attr('src', data.result.url);
                                                    $('input[name=poster]').val(data.result.path);
                                                }
                                            }
                                        </script>
                                        <img class="poster" style="max-width: 100%;" src="{{ $attachment->poster() }}" alt="poster">
                                        <input class="ltr tal" type="hidden" name="poster" value="{{ $attachment->poster }}">
                                    </div>

                                </div>

                                <div class="col-md-6">

                                    <div class="input-group">
                                        <label>نمایش</label>
                                        <video class="mt20" width="100%" controls>
                                            <source src="{{ $attachment->play() }}">
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>

                                </div>

                            </div>
                        </div>

                    @endif

                </div>

            </div>

            <div class="box-footer tal">
                <button type="submit" class="btn-lg btn-success">ذخیره</button>
            </div>

        </form>

    </div>

@endsection

@section('head-content')

    <script src="/plugins/ckeditor4/ckeditor.js"></script>
    <script src="/plugins/fancybox/dist/jquery.fancybox.min.js"></script>
    <link rel="stylesheet" href="/plugins/fancybox/dist/jquery.fancybox.min.css">
    <link rel="stylesheet" href="/plugins/filemanager/css/rtl-style.css">

@endsection
