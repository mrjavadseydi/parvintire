$(document).on('click', '.add-col', function () {
    var select  = $('select[name="builder"]');
    var key     = select.val();
    if (key == 'none') {
        swal("ابتدا نوع صفحه ساز را انتخاب کنید")
    } else {
        var icon  = select.children('option:selected').attr('icon');
        var color = select.children('option:selected').attr('lightColor');
        var title = select.children('option:selected').attr('title');
        var key   = select.val();

        var template = $('#pageBuilderItem').html();
        var compiledTemplate = Template7.compile(template);
        var context = {
            title: title,
            color: color,
            icon: icon,
            key: key
        };
        $('.pageBuilderGrid').append(compiledTemplate(context));

        sortPageBuilderInput();

    }

});

$(document).on('click', '.pageBuilder a', function () {
    var attr = $(this).attr('href');
    if (typeof attr === typeof undefined || attr === false) {
        swal({
            'title': 'ابتدا ساختار را ذخیره کنید',
            'text': 'ابتدا ساختار را ذخیره کنید و مجددا روی بخش مورد نظر خود کلیک کنید',
            className: 'swal-tar',
            icon: "warning",
            buttons: ['لغو', 'ذخیره'],
        }).then(function (willDelete) {
            if (willDelete) {
                $('button[type="submit"]').click();
            }
        });
    }
});

$( function() {
    if ($( "#builder-sortable-active" ).length > 0) {
        $( "#builder-sortable-active" ).sortable({
            placeholder: "ui-state-highlight",
            connectWith: "div",
            stop: function (event, ui) {
                sortPageBuilderInput();
            }
        });
        $( "#builder-sortable-deactive" ).sortable({
            placeholder: "ui-state-highlight",
            connectWith: "div",
            stop: function (event, ui) {
                sortPageBuilderInput();
            }
        });
        $( "#builder-sortable-delete" ).sortable({
            placeholder: "ui-state-highlight",
            connectWith: "div",
            stop: function (event, ui) {
                sortPageBuilderInput();
            }
        });
        $( "#builder-sortable-active, #builder-sortable-deactive, #builder-sortable-delete" ).disableSelection();
    }
});

function sortPageBuilderInput() {

    $( ".pageBuilderGrid .item" ).each(function( index, obj ) {
        $(obj).find('.key').attr('name', 'pageBuilder['+index+'][key]');
        $(obj).find('.id').attr('name', 'pageBuilder['+index+'][id]');
    });

    $( ".pageBuilderDeActiveGrid .item" ).each(function( index, obj ) {
        $(obj).find('.key').attr('name', 'pageBuilderDeActive['+index+'][key]');
        $(obj).find('.id').attr('name', 'pageBuilderDeActive['+index+'][id]');
    });

    $( ".pageBuilderDeleteGrid .item" ).each(function( index, obj ) {
        $(obj).find('.key').attr('name', 'pageBuilderDelete['+index+'][key]');
        $(obj).find('.id').attr('name', 'pageBuilderDelete['+index+'][id]');
    });

}