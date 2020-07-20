<form action="{{ url('admin/page-builder/click') }}" method="post" id="click-modal" class="remodal" data-remodal-id="clickModal">
    <button data-remodal-action="close" class="remodal-close"></button>
    <h1>رویداد کلیک</h1>
    @csrf
    <div class="input-group tar">
        <label>انتخاب رویداد</label>
        <select name="click[type]">
            <option value="none">بدون رویداد</option>
            <option class="val openIn" val="لینک" value="link">بازکردن لینک</option>
            <option class="post openIn" post="انتخاب پست" value="post">بازکردن پست</option>
        </select>
    </div>

    <div class="all input-group val tar dn">
        <label></label>
        <input class="ltr tal w100" type="text" name="click[val]">
    </div>

    <div class="all input-group post tar dn">
        <label></label>
        <select class="select2" content="w100" name="click[post]">
            <option value="0">انتخاب پست</option>   
            @foreach($posts as $record)
                <option
                        value="{{ $record->id }}">{{ $record->title }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="all input-group openIn tar dn">
        <label></label>
        <select name="click[openIn]">
            <option value="_self">بازکردن در همین پنجره</option>
            <option value="_blank">بازکردن در پنجره جدید</option>
        </select>
    </div>

    <br>
    <button data-remodal-action="confirm" class="remodal-confirm click-modal">تایید</button>
    <button data-remodal-action="cancel" class="remodal-cancel">لغو</button>
</form>

<link rel="stylesheet" href="{{ asset('/plugins/select2/select2.css') }}">
<script src="{{ asset('/plugins/select2/select2.min.js') }}"></script>
<script>

    clickElement = null;
    window.location.replace("#");

    $(document).on('click', '.open-click-modal', function () {
        var el = $(this);
        clickElement = $(this).parent().find('.click-inputs');
        var type    = null;
        var value   = null;
        var postId  = null;
        $('#click-modal :input').each(function(){
            if ($(this).prop("name")) {
                var name = $(this).attr("name").replace('click[', '').replace(']', '');
                if (name != '_token') {
                    var e = $('[name="'+$(this).attr('name')+'"]');
                    var val = el.attr(name);
                    if (e.length > 0) {
                        if (el.attr(name) !== undefined) {
                            $('[name="'+$(this).attr('name')+'"]').val(val);
                        }
                    }
                    if (name == 'type') {
                        type = val;
                    }
                    if (name == 'val') {
                        value = val;
                    }
                    if (name == 'post') {
                        postId = val;
                    }
                }
            }
        });

        if (type == 'post') {
            $('select[name="click[post]"]').val(postId).select2().trigger('change');
        }

        setTimeout(function(){ $('select[name="click[type]"]').change(); }, 200);

    });

    $(document).ready(function () {
        $('#click-modal').ajaxForm({
            complete: function(data) {
                var response = $.parseJSON( data.responseText );
                clickElement.html(response.inputs)
                sortInputs();
            }
        });
    });

    $(document).on('click', '.click-modal', function () {
        $('#click-modal').submit();
    });

    $(document).on('change', 'select[name="click[type]"]', function () {

        $('.all').slideUp();
        var select      = $(this);
        var selected    = select.children('option:selected');
        var val         = select.val();
        if (val != 'none') {
            var classes     = selected.attr('class').split(' ');
            $.each(classes, function (i, cls) {
                $('.' + cls + ' label').text(selected.attr(cls));
                $('.' + cls).slideDown();
            });
        }

    });

    $('.select2').select2({
        dir: "rtl",
    });

</script>

<style>
    .select2-container {
        width: 100% !important;
    }
    .select2-container.select2-container--default.select2-container--open {
        z-index: 99999 !important;
    }
</style>