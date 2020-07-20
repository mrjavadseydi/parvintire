@php
    $titleBoxColors = [
        "title-box-pink",
        "title-box-orange",
        "title-box-success",
        "title-box-gray",
        "title-box-indigo",
        "title-box-purple",
        "title-box-primary",
        "title-box-teal",
    ];
@endphp
<div class="row">

    <div class="col-lg-8">

        <div class="sticky-top top55 box box-info">
            @csrf

            <input type="hidden" name="id" value="{{ $id }}">
            <input type="hidden" name="key" value="{{ $key }}">

            <div class="box-header">
                <h3 class="box-title">تایتل باکس</h3>
                <div class="box-tools">
                    <i class="box-tools-icon icon-minus"></i>
                </div>
            </div>

            <div class="box-body">

                <div class="row">

                    <input type="hidden" class="index">

                    <div class="col-3 index-0">
                        <div class="input-group">
                            <label for="">عنوان</label>
                            <input type="text" name="titleBox[0][title]" value="{{ (isset($values->titleBox) ? $values->titleBox[0]->title : "") }}">
                        </div>
                        <div class="input-group">
                            <label for="">رنگ</label>
                            <select name="titleBox[0][color]">
                                @foreach ($titleBoxColors as $color)
                                    <option {{ (isset($values->titleBox) ? selected($values->titleBox[0]->color, $color) : "") }} value="{{ $color }}">{{ $color }}</option>
                                @endforeach
                            </select>
                        </div>
                        <a {{ (isset($values->titleBox[0]->click) ? $post->pageBuilderModal($values->titleBox[0]->click) : "") }} onclick="$('.index').val(0)" id="imageClick" href="#clickModal" class="open-click-modal btn-lg btn-pink w100">رویداد کلیک</a>
                        <div class="click-inputs">
                            {!! (isset($values->titleBox[0]->click) ? $post->pageBuilderInputs($values->titleBox[0]->click) : "") !!}
                        </div>
                    </div>

                    <div class="col-3 index-1">
                        <div class="input-group">
                            <label for="">عنوان</label>
                            <input type="text" name="titleBox[1][title]" value="{{ (isset($values->titleBox) ? $values->titleBox[1]->title : "") }}">
                        </div>
                        <div class="input-group">
                            <label for="">رنگ</label>
                            <select name="titleBox[1][color]">
                                @foreach ($titleBoxColors as $color)
                                    <option {{ (isset($values->titleBox) ? selected($values->titleBox[1]->color, $color) : "") }} value="{{ $color }}">{{ $color }}</option>
                                @endforeach
                            </select>
                        </div>
                        <a {{ (isset($values->titleBox[1]->click) ? $post->pageBuilderModal($values->titleBox[1]->click) : "") }} onclick="$('.index').val(1)" id="imageClick" href="#clickModal" class="open-click-modal btn-lg btn-pink w100">رویداد کلیک</a>
                        <div class="click-inputs">
                            {!! (isset($values->titleBox[1]->click) ? $post->pageBuilderInputs($values->titleBox[1]->click) : "") !!}
                        </div>
                    </div>

                    <div class="col-3 index-2">
                        <div class="input-group">
                            <label for="">عنوان</label>
                            <input type="text" name="titleBox[2][title]" value="{{ (isset($values->titleBox) ? $values->titleBox[2]->title : "") }}">
                        </div>
                        <div class="input-group">
                            <label for="">رنگ</label>
                            <select name="titleBox[2][color]">
                                @foreach ($titleBoxColors as $color)
                                    <option {{ (isset($values->titleBox) ? selected($values->titleBox[2]->color, $color) : "") }} value="{{ $color }}">{{ $color }}</option>
                                @endforeach
                            </select>
                        </div>
                        <a {{ (isset($values->titleBox[2]->click) ? $post->pageBuilderModal($values->titleBox[2]->click) : "") }} onclick="$('.index').val(2)" id="imageClick" href="#clickModal" class="open-click-modal btn-lg btn-pink w100">رویداد کلیک</a>
                        <div class="click-inputs">
                            {!! (isset($values->titleBox[2]->click) ? $post->pageBuilderInputs($values->titleBox[2]->click) : "") !!}
                        </div>
                    </div>

                    <div class="col-3 index-3">
                        <div class="input-group">
                            <label for="">عنوان</label>
                            <input type="text" name="titleBox[3][title]" value="{{ (isset($values->titleBox) ? $values->titleBox[3]->title : "") }}">
                        </div>
                        <div class="input-group">
                            <label for="">رنگ</label>
                            <select name="titleBox[3][color]">
                                @foreach ($titleBoxColors as $color)
                                    <option {{ (isset($values->titleBox) ? selected($values->titleBox[3]->color, $color) : "") }} value="{{ $color }}">{{ $color }}</option>
                                @endforeach
                            </select>
                        </div>
                        <a {{ (isset($values->titleBox[3]->click) ? $post->pageBuilderModal($values->titleBox[3]->click) : "") }} onclick="$('.index').val(3)" id="imageClick" href="#clickModal" class="open-click-modal btn-lg btn-pink w100">رویداد کلیک</a>
                        <div class="click-inputs">
                            {!! (isset($values->titleBox[3]->click) ? $post->pageBuilderInputs($values->titleBox[3]->click) : "") !!}
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

<script>
    $(document).ready(function () {
        for (var i = 0; i < 4; i++) {
            $('.index').val(i);
            sortInputs();
        } 
    });
    
    function sortInputs() {
        var index = $('.index').val();
        $('.index-'+index).find('.click-inputs input').each(function () {
            var name = $(this).attr('name').replace('click[', '').replace(']', '');
            var index = $('.index').val();
            $(this).attr('name', 'titleBox['+index+'][click]['+name+']');
        });
    }
</script>