@extends("admin.{$adminTheme}.master")
@section('title', 'افزودن دسته بندی')

@section('content')

    <div class="col-lg-4">

        <form action="{{ route('admin.categories.store') }}" method="post" class="box-solid box-primary">

            <div class="box-header">
                <h3 class="box-title">@yield('title')</h3>
                <div class="box-tools">
                    <i class="box-tools-icon icon-minus"></i>
                </div>
            </div>

            <div class="box-body">

                @csrf

                <div class="input-group">
                    <i class="icon-pen"></i>
                    <label>نوع مطلب</label>
                    <select name="post_type" id="">
                        <option value=""> --- انتخاب --- </option>
                        @foreach ($postTypes as $postType)
                            <option {{ (isset($_GET['postType']) ? ($postType->type == $_GET['postType'] ? 'selected' : '') : '') }} {{ old('post_type') == $postType->type ? 'selected' : '' }} value="{{ $postType->type }}">{{ $postType->label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="input-group">
                    <i class="icon-title"></i>
                    <label>عنوان</label>
                    <input autofocus autocomplete="off" type="text" name="title" value="{{ old('title') }}">
                </div>

                <div class="pb10 input-group">
                    <i class="icon-link2"></i>
                    <label>دسته‌ ی مادر</label>
                    <select name="parent" id="parent">
                        <option value="">بدون دسته‌ ی مادر</option>
                        <?php echo selectOptions([old('parent')], \LaraBase\Categories\Models\Category::whereNull('parent')->get());?>
                    </select>
                </div>

                @if($languages->count() > 0)
                    <div class="pb10 input-group">
                        <i class="icon-link2"></i>
                        <label>زبان</label>
                        <select name="lang">
                            @foreach($languages as $lang)
                                <option {{ selected(old('lang'), $lang->lang) }} value="{{ $lang->lang }}">{{ $lang->title }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

            </div>

            <div class="box-footer tal">
                <button type="submit" class="btn-lg btn-success">ذخیره</button>
            </div>

        </form>

    </div>

@endsection
