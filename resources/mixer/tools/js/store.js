$(document).ready(function () {

    addToCartForm = null;
    addToCartBtn  = null;

    $('.addToCart').ajaxForm({
        beforeSubmit: function(data, jqForm, options) {
            addToCartForm = $(jqForm);
            addToCartBtn = addToCartForm.find('.loading');
            addToCartBtn.text(addToCartBtn.attr('loading'));
        },
        complete: function (data) {
            var response = $.parseJSON(data.responseText);
            cartOnSuccess(response);
            var callback = eval(addToCartForm.attr('callback'));
            if (typeof callback == 'function') {
                callback(response.status, response, addToCartForm);
            }
        },
        error: function (data) {
            var response = $.parseJSON(data.responseText);
            cartOnError(response);
            var callback = eval(addToCartForm.attr('callback'));
            if (typeof callback == 'function') {
                callback('error', response, addToCartForm);
            }
        }
    });

    $('.deleteFromCart').ajaxForm({
        beforeSubmit: function(data, jqForm, options) {
            addToCartForm = $(jqForm);
            addToCartBtn = addToCartForm.find('.loading');
            addToCartBtn.text(addToCartBtn.attr('loading'));
        },
        complete: function (data) {
            var response = $.parseJSON(data.responseText);
            cartOnSuccess(response);
        },
        error: function (data) {
            cartOnError(data);
        }
    });

    cartAddressForm = null;
    $('#cart-address-form').ajaxForm({
        beforeSubmit: function(data, jqForm, options) {
            cartAddressForm = jqForm;
        },
        complete: function (data) {
            var response = $.parseJSON(data.responseText);
            cartOnSuccess(response);
            var callback = eval(cartAddressForm.attr('callback'));
            if (typeof callback == 'function') {
                callback(response.status, response);
            }
        },
        error: function (data) {
            cartOnError(data);
            var callback = eval(cartAddressForm.attr('callback'));
            if (typeof callback == 'function') {
                callback('error', []);
            }
        }
    });

});

function cartOnSuccess(response) {
    if (response.status == 'success') {
        $('.cart-count').text(response.productsSumCount)
        if (response.view1) {
            $('.cart-view-1').html(response.html1);
        }
        if (response.view2) {
            $('.cart-view-2').html(response.html2);
        }
        iziToast.success({
            title: '',
            message: response.message,
            position: 'bottomRight',
            rtl: true,
            displayMode: 'replace', // once, replace
        });
    } else {
        iziToast.error({
            title: '',
            message: response.message,
            position: 'bottomRight',
            rtl: true,
            displayMode: 'replace', // once, replace
        });
    }
    if (addToCartBtn != null) {
        addToCartBtn.text('افزودن به سبد خرید');
    }
}

function cartOnError(response) {
    if (addToCartBtn != null) {
        addToCartBtn.text('افزودن به سبد خرید');
    }
    iziToast.error({
        title: '',
        message: 'خطای سرور رخ داده است.',
        position: 'center',
        rtl: true,
    });
}

function numberFormat(nStr) {
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}
