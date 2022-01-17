<div class="row">

    <div class="col-lg-8">

        <div class="sticky-top top55 box box-info">
            @csrf

            <input type="hidden" name="id" value="{{ $id }}">
            <input type="hidden" name="key" value="{{ $key }}">


            <div class="box-header">
                <h3 class="box-title">اسلایدر</h3>
                <div class="box-tools">
                    <i class="box-tools-icon icon-minus"></i>
                </div>
            </div>

            <div class="box-body">

                <input type="hidden" id="image" name="image" value="{{ (isset($values->url) ? $values->url : "") }}">
                <a href="{{ filemanager([
                    'type'          => '1',
                    'fldr'          => 'posts',
                    'field_id'      => 'slider',
                    'relative_url'  => '1',
                ]) }}" id="slider" class="file-manager dn btn-lg btn-info">انتخاب تصویر</a>

                <div class="input-group">
                    <label for="">ارتفاع</label>
                    <input class="ltr" type="text" name="height" value="{{ (isset($values->height) ? $values->height : "") }}">
                </div>

                <div class="responsive-table">
                    <table class="tac">
                        <thead>
                        <tr>
                            <th>تصویر</th>
                            <th>alt</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody class="slider">

                        <script id="pageBuilderSlider" type="text/template7">
                            <tr class="slide">
                                <td class="img">

                                </td>
                                <td>
                                    <input type="text" class="w100 alt">
                                </td>
                                <td>
                                    <span class="add-image btn-icon btn-icon-info icon-image"></span>
                                    <a href="#clickModal" class="open-click-modal btn-icon btn-icon-pink icon-mouse"></a>
                                    <span class="btn-icon btn-icon-danger icon-delete"></span>
                                    <div class="click-inputs">
                                    </div>
                                </td>
                            </tr>
                        </script>

                        @php
                        $i = 0;
                        @endphp
                        @if(isset($values->slider))
                            @foreach ($values->slider as $record)
                                <tr class="slide">
                                    <td class="img">
                                        <img height="100" src="{{ uploadUrl() }}/{{ $record->url }}">
                                        <input type="hidden" value="{{ $record->url }}">
                                    </td>
                                    <td>
                                        <input type="text" class="w100 alt" value="{{ $record->alt }}">
                                    </td>
                                    <td>
                                        <span class="add-image btn-icon btn-icon-info icon-image"></span>
                                        <a {{ (isset($record->click) ? $post->pageBuilderModal($record->click) : "") }} href="#clickModal" class="open-click-modal btn-icon btn-icon-pink icon-mouse"></a>
                                        <span class="btn-icon btn-icon-danger icon-delete"></span>
                                        <div class="click-inputs">
                                            {!! (isset($record->click) ? $post->pageBuilderInputs($record->click) : "") !!}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>

            </div>

            <div class="box-footer tal">
                <span class="btn-lg btn-info add-slide">افزودن اسلاید</span>
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

    $(document).on('click', '.add-image', function () {
        slider = $(this).parent().parent();
        $('.file-manager').click();
    });

    $(document).on('click', '.add-slide', function () {
        var template = $('#pageBuilderSlider').html();
        var compiledTemplate = Template7.compile(template);
        var context = {
            lastName: 'Doe'
        };
        $('.slider').append(compiledTemplate(context))
        sortInputs();
    });

    function sortInputs() {
        if ($('#slider').length > 0) {
            $('.slider .slide').each(function (i) {
                var slide = $(this);
                slide.find('.img input').attr('name', 'slider['+i+'][url]')
                slide.find('.alt').attr('name', 'slider['+i+'][alt]')
                slide.find('.click-inputs input').each(function () {
                    var input = $(this);
                    if (input.hasClass('changed')) {
                        var name  = input.attr('name').replace('slider['+i+'][click][', '').replace(']', '');
                    } else {
                        var name  = input.attr('name').replace('click[', '').replace(']', '');
                    }
                    input.attr('name', 'slider['+i+'][click]['+name+']')
                    input.addClass('changed');
                });
            });
        }
    }

</script>