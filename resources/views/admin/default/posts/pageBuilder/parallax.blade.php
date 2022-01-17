<div class="row">

    <div class="col-lg-8">

        <div class="sticky-top top55 box box-info">
            @csrf

            <input type="hidden" name="id" value="{{ $id }}">
            <input type="hidden" name="key" value="{{ $key }}">


            <div class="box-header">
                <h3 class="box-title">پارالاکس</h3>
                <div class="box-tools">
                    <i class="box-tools-icon icon-minus"></i>
                </div>
            </div>

            <div class="box-body">

                <div class="row">

                    <div class="col-lg-12 mb10">
                        <div class="input-group tar">
                            <label for="">عنوان</label>
                                <input type="text" name="title" value="{{ (isset($values->title) ? $values->title : "") }}">
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="input-group tar">
                            <label for="">متن قبل از لینک</label>
                            <input type="text" name="prevLinkText" value="{{ (isset($values->prevLinkText) ? $values->prevLinkText : "") }}">
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="input-group tar">
                            <label for="">متن لینک</label>
                            <input type="text" name="linkText" value="{{ (isset($values->linkText) ? $values->linkText : "") }}">
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="input-group tar">
                            <label for="">متن بعد از لینک</label>
                            <input type="text" name="nextLinkText" value="{{ (isset($values->nextLinkText) ? $values->nextLinkText : "") }}">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="input-group">
                            <label for="">&nbsp;</label>
                            <input type="hidden" id="image" name="image" value="{{ (isset($values->image) ? $values->image : "") }}">
                            <a href="{{ filemanager([
                                'type'          => '1',
                                'fldr'          => 'posts',
                                'field_id'      => 'image',
                                'relative_url'  => '1',
                            ]) }}" id="image" class="file-manager btn-lg btn-info w100">انتخاب تصویر</a>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="input-group">
                            <label for="">&nbsp;</label>
                            <a {{ (isset($values->click) ? $post->pageBuilderModal($values->click) : "") }} id="imageClick" href="#clickModal" class="open-click-modal btn-lg btn-pink w100">رویداد کلیک</a>
                            <div class="click-inputs">
                            {!! (isset($values->click) ? $post->pageBuilderInputs($values->click) : "") !!}
                            </div>
                        </div>
                    </div>

                    <div class="{{ (isset($values->responsive) ? $values->responsive : "") }} mt10">
                        <img src="{{ (isset($values->image) ? uploadUrl($values->image) : "") }}"
                             id="image-image"
                             class="imageBuilder {{ (isset($values->css) ? $values->css->el : "") }}">
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
