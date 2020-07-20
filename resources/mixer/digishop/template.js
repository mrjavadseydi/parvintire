require("../tools/plugins/bootstrap/4.3.1/js/dist/carousel");
require("../tools/plugins/bootstrap/4.3.1/js/dist/collapse");
require("../tools/plugins/bootstrap/4.3.1/js/dist/dropdown");

$(document).on('click', '.menu-toggle', function () {
    $('.main-menu').addClass('show');
    $('body').append('<div id="menu-close"></div>');
});

$(document).on('click', '.main-li', function (e) {
    e.stopPropagation();
    var li = $(this);
    var dropdown = $(this).find('.dropdown');
    if (li.hasClass('open')) {
        li.css('height', 45);
        li.removeClass('open');
    } else {
        li.css('height', dropdown.height() + 65);
        li.addClass('open');
    }
});

$(document).on('click', '.dropdown-icon', function (e) {
    e.stopPropagation();
    var li = $(this).parent();
    var dropdown = $(this).next();
    if (li.hasClass('open')) {
        li.css('height', 45);
        li.removeClass('open');
    } else {
        li.css('height', dropdown.height() + 65);
        li.addClass('open');
    }
});

$(document).on('click', '#menu-close', function () {
    $('.main-menu').removeClass('show');
    $('#menu-close').remove();
});

$(document).on('click', '.buttons label, .colors label', function () {
    $(this).parent().find('label').removeClass('active');
    $(this).addClass('active');
});

$(document).on('click', '.plus-count', function () {
    $('#single-add-to-cart-form').submit();
});

$(document).on('click', '.mines-count', function () {
   $('.header-cart-remove-' + $(this).attr('id')).click();
});

$(document).on('click', '.submit-get-product-form', function () {
    $('#get-product-form').submit();
});

$(document).on('click', '.digishop-tabs li', function () {
    var val = $(this).attr('id');
    $(this).parent().find('li').removeClass('active');
    $(this).addClass('active');
    $(this).parent().parent().find('.contents').addClass('d-none');
    $(this).parent().parent().find('.'+val).removeClass('d-none');
});

$(document).on('click', '.reply-comment', function () {
    var box = $(this).parent().find('.reply-box');
    if (box.hasClass('active')) {
        box.removeClass('active');
        $(this).text('پاسخ').removeClass('text-danger');
    } else {
        box.addClass('active');
        $(this).text('بستن').addClass('text-danger');
    }
});

$(document).on('click', '.reply-comment-close', function () {
    $(this).closest('.reply-box').removeClass('active');
});

