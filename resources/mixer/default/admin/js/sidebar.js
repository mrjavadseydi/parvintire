$(document).on('click', '.sidebar-item', function () {
    var a  = $(this);
    var li = a.parent();
    var ul = a.next('ul');

    if (li.hasClass('active')) {
        li.removeClass('active');
        ul.slideUp();
    } else {
        li.parent().find('.submenu').slideUp();
        li.parent().find('.treeview').removeClass('active');
        li.addClass('active');
        ul.slideDown();
    }
});

$(document).on('mouseenter', '.sidebar', function () {
    if (!$('.sidebar').hasClass('sidebar-fix')) {
        $(this).addClass("sidebar-active");
    }
});

$(document).on('mouseleave', '.sidebar', function () {
    if (!$('.sidebar').hasClass('sidebar-fix')) {
        $(this).removeClass("sidebar-active");
    }
});

$(document).on('click', '.sidebar-toggle', function () {
    var sidebar = $('.sidebar');
    if (sidebar.hasClass('sidebar-close')) {
        sidebar.removeClass('sidebar-close');
        $('.content').removeClass('content-full');
    }
    if (sidebar.hasClass('sidebar-active')) {
        sidebar.removeClass('sidebar-fix');
    } else {
        sidebar.addClass('sidebar-fix');
    }
    sidebar.toggleClass('sidebar-active');
});

$(document).on('click', '.sidebar-toggle-close', function () {
    var sidebar = $('.sidebar');
    var content = $('.content');
    if (sidebar.hasClass('sidebar-close')) {
        sidebar.removeClass('sidebar-close');
        content.removeClass('content-full');
        content.removeClass('content-full');
    } else {
        sidebar.addClass('sidebar-close');
        content.addClass('content-full');
        content.addClass('content-full');
    }
});
