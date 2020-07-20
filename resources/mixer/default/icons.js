$(document).on('click', '.icons-open', function () {
    $('.icons').attr('callback', $(this).attr('callback'));
    toggleIcons();
});

$(document).on('click', '.icons-close', function () {
    toggleIcons();
});

$(document).on('click', '.icons li', function () {
    var callback = eval($('.icons').attr('callback'));
    if (typeof callback == 'function') {
        callback($(this).attr('class'));
    }
    toggleIcons();
});

$(document).on('keyup', 'input[name=icons-search]', function () {
    var word = $(this).val();
    $('.icons li').each(function (i, obj) {
        var icon = $(obj).attr('class');
        if(icon.indexOf(word) == -1) {
            $(obj).hide();
        } else {
            $(obj).show();
        }
    });
});

function toggleIcons() {
    $('.icons').toggleClass('active');
}
