@extends("admin.{$adminTheme}.master")
@section('title', 'افزودن مطلب جدید')

@section('content')

    <div class="col-lg-4">

        <form action="{{ route('admin.posts.store') }}" method="post" class="box-solid box-primary">

            <div class="box-header">
                <h3 class="box-title">@yield('title')</h3>
                <div class="box-tools">
                    <i class="box-tools-icon icon-minus"></i>
                </div>
            </div>

            <div class="box-body">

                @csrf

                <div class="row">

                    <div class="col-md-12">

                        <div class="input-group">
                            <i class="icon-pen"></i>
                            <label>نوع مطلب</label>
                            <select name="type" id="">
                                <option value=""> --- انتخاب --- </option>
                                @foreach ($postTypes as $postType)
                                    <option {{ (isset($_GET['type']) ? ($postType->type == $_GET['type'] ? 'selected' : '') : '') }} {{ old('post_type') == $postType->type ? 'selected' : '' }} value="{{ $postType->type }}">{{ $postType->label }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="col-md-12 mt20">

                        <div class="input-group">
                            <i class="icon-title"></i>
                            <label>عنوان</label>
                            <input autofocus autocomplete="off" type="text" name="title" value="{{ old('title') }}">
                        </div>

                    </div>

                </div>

            </div>

            <div class="box-footer tal">
                <button type="submit" class="btn-lg btn-success">ذخیره</button>
            </div>

        </form>

    </div>

@endsection
