$(document).on('click', '.toggle-class', function () {

    var el = $(this);
    var cls = el.attr('toggle-class');

    var element = el;
    var slideDownUp = false;

    if (hasAttri(el, 'find')) {
        element = el.find(el.attr('find'));
    }

    if (hasAttri(el, 'slideDownUp')) {
        slideDownUp = true;
    }

    if (el.hasClass(cls)) {

        el.removeClass(cls);

        if (slideDownUp) {
            element.slideUp('fast');
        }

    } else {
        el.addClass(cls);

        if (slideDownUp) {
            element.slideDown('fast');
        }

    }

});

function hasAttri(el, attr) {
    var check = $(el).attr(attr);

    if (typeof check !== typeof undefined && check !== false) {
        return true;
    }

    return false;
}
