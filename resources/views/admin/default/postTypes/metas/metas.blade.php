<?php
    $types = [
        'textMetas' => [
            'title' => 'متاهای متنی',
        ],
        'contentMetas' => [
            'title' => 'متاهای محتوا'
        ],
    ];
    $key = generateUniqueToken();
?>
@foreach($types as $type => $value)
    <div type="{{ $type }}" class="meta-parent col-12 mt20 k-{{ $key }}">
        <div class="input-group">
            <i class="icon-line_style"></i>
            <label>{{ $value['title'] }}</label>
        </div>
        <div class="metas">
            @if(isset($metas[$type]))
                <?php $i = 0;?>
                @foreach($metas[$type] as $item)
                    <div class="item row mb10">
                        <div class="col-md-2">
                            <input placeholder="key" class="w100 tac key" type="text" name="metas[{{ $type }}][{{ $i }}][key]" value="{{ $item['key'] }}">
                        </div>
                        <div class="col-md-9">
                            <input placeholder="title" class="w100 value" type="text" name="metas[{{ $type }}][{{ $i }}][value]" value="{{ $item['value'] }}">
                        </div>
                        <div onclick="$(this).parent().remove();" class="col-md-1">
                            <span class="btn-lg btn-danger w100">حذف</span>
                        </div>
                    </div>
                    <?php $i++;?>
                @endforeach
            @endif
            <div class="ltr">
                <span class="append-meta btn btn-info">افزودن</span>
            </div>
        </div>
    </div>
@endforeach

<script>
    $(document).on('click', '.append-meta', function () {
        $(this).parent().parent().prepend(
            '<div class="item row mb10">' +
            '   <div class="col-md-2">\n' +
            '       <input placeholder="key" class="w100 tac key" type="text" name="" value="">\n' +
            '   </div>\n' +
            '   <div class="col-md-9">\n' +
            '       <input placeholder="title" class="w100 value" type="text" name="" value="">\n' +
            '   </div>\n' +
            '   <div onclick="$(this).parent().remove();" class="col-md-1">\n' +
            '       <span class="btn-lg btn-danger w100">حذف</span>\n' +
            '   </div>' +
            '</div>'
        );
        $('.meta-parent').each(function () {
            var type = $(this).attr('type');
            var i = 0;
            $(this).find('.metas .item').each(function () {
                $(this).find('.key').attr('name', 'metas['+type+']['+i+'][key]');
                $(this).find('.value').attr('name', 'metas['+type+']['+i+'][value]');
                i++;
            });
        });
    });
</script>
