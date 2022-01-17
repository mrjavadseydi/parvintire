<div class="row">

    <div class="col-lg-8">

        <div class="sticky-top top55 box box-info">
            @csrf

            <input type="hidden" name="id" value="{{ $id }}">
            <input type="hidden" name="key" value="{{ $key }}">

            <div class="box-header">
                <h3 class="box-title">نمایش پست</h3>
                <div class="box-tools">
                    <i class="box-tools-icon icon-minus"></i>
                </div>
            </div>

            <div class="box-body">

                <div class="row">
                    <div class="col-lg-6 mb10">
                        <div class="input-group">
                            <label>نوع پست</label>
                            <select class="w100" name="post[postType]">
                                @foreach (config('types.postTypes') as $key => $value)
                                    @if($key != 'page')
                                        <option {{ (isset($values->post) ? selected($values->post->postType, $key) : "") }} value="{{ $key }}">{{ $value['totalTitle'] }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6 mb10">
                        <div class="input-group">
                            <label>نوع</label>
                            <select class="w100" name="post[viewType]">
                                <option {{ (isset($values->post) ? selected($values->post->viewType, 'all') : "") }} value="all">همه</option>
                                <option {{ (isset($values->post) ? selected($values->post->viewType, 'slider') : "") }} value="slider">اسلایدر</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6 mb10">
                        <div class="input-group">
                            <label>تعداد</label>
                            <select class="w100" name="post[count]">
                                @for($i = 4; $i <= 20; $i = $i + 4)
                                    <option {{ (isset($values->post) ? selected($values->post->count, $i) : "") }} value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6 mb10">
                        <div class="input-group">
                            <label>مرتب سازی</label>
                            <select class="w100" name="post[sort]">
                                <option {{ (isset($values->post) ? selected($values->post->sort, 'latest') : "") }} value="latest">جدیدترین</option>
                                <option {{ (isset($values->post) ? selected($values->post->sort, 'oldest') : "") }} value="oldest">قدیمی ترین</option>
                                <option {{ (isset($values->post) ? selected($values->post->sort, 'views') : "") }} value="views">پربازدیدترین</option>
                                <option {{ (isset($values->post) ? selected($values->post->sort, 'likes') : "") }} value="likes">بیشترین لایک</option>
                                <option {{ (isset($values->post) ? selected($values->post->sort, 'comments') : "") }} value="comments">بیشترین نظر</option>
                            </select>
                        </div>
                    </div>
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