$(document).on('click', '.message-notification-toggle', function () {
    var messagePanel = $('.message-notification');
    if (messagePanel.hasClass('open')) {
        messagePanel.removeClass('open');
    } else {
        messagePanel.addClass('open');
    }
});