require('../tools/js/drag-scroll')
require('../tools/js/goTop')

$(document).on('click', '.icon-minus', function () {
    var el = $(this);
    var box = el.parent().parent().parent();
    box.toggleClass('closed');
});

$(document).on('click', '.filters-open', function () {
    $('.filters').slideToggle();
});

require('../default/admin/js/header');
require('../default/admin/js/admin');
require('../default/admin/js/sidebar');
require('../default/admin/js/sidebar');
require('../default/boxes');
require('../default/icons');
