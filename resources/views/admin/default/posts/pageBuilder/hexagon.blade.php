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
                <h3 class="box-title">شش ضلعی</h3>
                <div class="box-tools">
                    <i class="box-tools-icon icon-minus"></i>
                </div>
            </div>

            <div class="box-body">

                <input type="hidden" id="multiple" name="multiple" value="{{ (isset($values->url) ? $values->url : "") }}">
                <a href="{{ filemanager([
                    'type'          => '1',
                    'fldr'          => 'posts',
                    'field_id'      => 'multiple',
                    'relative_url'  => '1',
                ]) }}" id="multiple"
               class="file-manager w100 dn btn-lg btn-info">انتخاب تصویر</a>

                <div class="row">
                    <input type="hidden" class="index">
                    @for($i = 0; $i < 10; $i++)
                        <div class="col-6 indexes index-{{ $i }}">
                            <div class="divider">
                                <span>{{ ($i+1) }}</span>
                            </div>
                            <div class="input-group tac img">
                                {!! (isset($values->hexagon[$i]->url) ? "<img height='100px' width='100px' src='".uploadUrl()."{$values->hexagon[$i]->url}'><input value='{$values->hexagon[$i]->url}' name='hexagon[{$i}][url]' type='hidden'>" : "") !!}
                            </div>
                            <div class="input-group">
                                <label for="">عنوان</label>
                                <input type="text" name="hexagon[{{ $i }}][title]" value="{{ (isset($values->hexagon) ? $values->hexagon[$i]->title : "") }}">
                            </div>
                            <div class="input-group">
                                <label for="">توضیحات</label>
                                <input type="text" name="hexagon[{{ $i }}][description]" value="{{ (isset($values->hexagon) ? $values->hexagon[$i]->description : "") }}">
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <span class="add-image btn-lg btn-info w100">انتخاب تصویر</span>
                                </div>
                                <div class="col-6">
                                    <div class="click-inputs">
                                        {!! (isset($values->hexagon[$i]->click) ? $post->pageBuilderInputs($values->hexagon[$i]->click) : "") !!}
                                    </div>
                                    <a {{ (isset($values->hexagon[$i]->click) ? $post->pageBuilderModal($values->hexagon[$i]->click) : "") }} onclick="$('.index').val({{ $i }})" id="imageClick" href="#clickModal" class="open-click-modal btn-lg btn-pink w100">رویداد کلیک</a>
                                </div>
                            </div>
                        </div>
                    @endfor
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
        sortInputs();
    });

    function sortInputs() {

        $('.indexes').each(function (i) {
            var el = $(this);

            var imgInput = el.find('.img input');
            imgInput.attr('name', 'hexagon['+i+'][url]')

            el.find('.click-inputs :input').each(function () {
                var input = $(this);
                if (input.hasClass('changed')) {
                    var name  = input.attr('name').replace('hexagon['+i+'][click][', '').replace(']', '');
                } else {
                    var name  = input.attr('name').replace('click[', '').replace(']', '');
                }
                $(this).attr('name', 'hexagon['+i+'][click]['+name+']');
                input.addClass('changed');
            });

        });

    }

    $(document).on('click', '.add-image', function () {
        multiple = $(this).parent().parent().parent();
        $('.file-manager').click();
    });

</script>