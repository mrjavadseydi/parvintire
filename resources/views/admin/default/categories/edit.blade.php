@extends("admin.{$adminTheme}.master")
@section('title', "ویرایش دسته {$category->title}")

@section('content')

    {!! uploader()->relation('category', $category->id)->validations([
        'thumbnail' => [
            'in' => 1,
            'key' => 'thumbnail',
            'validations' => 'mimes:png,jpg,jpeg,gif,PNG,JPG,JPEG,GIF|min:0|max:2048'
        ]
    ])->theme('default')->load() !!}

    <div class="col-lg-12">

        <form action="{{ route('admin.categories.update', ['id' => $category->id]) }}" method="post" class="box-solid box-primary">

            <div class="box-header">
                <h3 class="box-title">@yield('title')</h3>
                <div class="box-tools">
                    <i class="box-tools-icon icon-minus"></i>
                </div>
            </div>

            <div class="box-body">

                @csrf
                @method('patch')

                <div class="row">
                    <div class="col-md-9">

                        <div class="input-group">
                            <i class="icon-title"></i>
                            <label>عنوان</label>
                            <input autofocus autocomplete="off" type="text" name="title" value="{{ old('title') ?? $category->title }}">
                        </div>

                        <div class="input-group">
                            <i class="icon-link2"></i>
                            <label>نامک</label>
                            <input autofocus autocomplete="off" type="text" name="slug" value="{{ old('slug') ?? $category->slug }}">
                        </div>

                        <div class="pb10 input-group">
                            <i class="icon-link2"></i>
                            <label>دسته‌ ی مادر</label>
                            <select name="parent" id="parent">
                                <option value="">بدون دسته‌ ی مادر</option>
                                <?php echo selectOptions([old('parent') ?? $category->parent], $categories);?>
                            </select>
                        </div>

                        @if($languages->count() > 0)
                            <div class="pb10 input-group">
                                <i class="icon-link2"></i>
                                <label>زبان</label>
                                <select name="lang">
                                    @foreach($languages as $lang)
                                        <option {{ selected(old('lang') ?? $category->lang, $lang->lang) }} value="{{ $lang->lang }}">{{ $lang->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                    </div>
                    <div class="col-md-3 clear tac">
                        <div>
                            <input readonly type="hidden" name="image" id="image" value="{{ old('image') ?? $category->image }}">
                            <img id="category-image" height="120px" src="{{ $category->image() }}" alt="{{ $category->title }}">
                        </div>
                        <span key="thumbnail" callback="category" buttonTitle="ثبت برای دسته" class="btn-lg btn-info uploader-open">بارگذاری تصاویر</span>
                        <script>
                            function category(data) {
                                $('input[name=image]').val(data['result']['path']);
                                $('#category-image').attr('src', data['result']['thumbnail']);
                            }
                        </script>
                    </div>
                </div>

                <div class="input-group">
                    <i class="icon-description"></i>
                    <label>توضیح کوتاه</label>
                    <textarea name="excerpt" id="" cols="30" rows="10">{{ old('excerpt') ?? $category->excerpt }}</textarea>
                </div>

                @include('boxes.default.ckeditor', [
                    'box' => [
                        'title' => 'محتوا',
                    ],
                    'post' => $category,
                    'key' => 'content',
                ])

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
