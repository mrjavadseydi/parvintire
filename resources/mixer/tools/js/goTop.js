$(document).on('click', '#goTop', function () {
    $("html, body").animate({ scrollTop: 0 }, "slow");
})

$(document).scroll(function() {
    if ($(document).scrollTop() > 500) {
        $('#goTop').addClass('active')
    } else {
        $('#goTop').removeClass('active')
    }
});