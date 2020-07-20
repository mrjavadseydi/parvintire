el = null;
token = null;
uploads = [];
classes = null;
uploader = null;
uploading = false;
uploadAjax = null;
getFileAjax = null;
uploaderData = [];
uploadsIndex = 0;
uploaderSidebar = null;
uploadIn = 1; // 1:public | 2:beforePublic | 3:ftp
uploaderData['status'] = 'error';

$(document).ready(function () {
    uploader = $('.uploader');
    token = $('meta[name="csrf-token"]').attr('content');
    uploaderSidebar = $('.uploader-box-body-sidebar');;
});

function hasAttr(el, attribute) {
    var attr = $(el).attr(attribute);
    if (typeof attr !== typeof undefined && attr !== false) {
        return true;
    }
    return false;
}

/*

$(document).on('click', '.uploader-remove', function (e) {
    var el = $(this);
    var id = $(this).attr('id');
    e.stopPropagation();

    swal({
        'title': 'حذف',
        'text': 'آیا از حذف این مورد اطمینان دارید؟',
        icon: "warning",
        buttons: ['لغو', 'حذف'],
        dangerMode: true,
    }).then(function (willDelete) {
        if (willDelete) {
            _url = $('input[name=removeUploadUrl]').val();
            _token = $('meta[name=csrf-token]').attr('content');

            $.ajax({
                url: _url,
                type: 'POST',
                data: {
                    '_token': _token,
                    'id': id
                },
                success: function (response, status) {
                    if (response.result === true) {
                        $(el).parent().remove();
                    } else {
                        swal({
                            'title': 'دوباره سعی کنید!',
                            icon: 'warning',
                            button: 'تایید'
                        });
                    }
                },
                error: function (xhr, status, error) {
                    swal({
                        'title': 'دوباره سعی کنید!',
                        icon: 'warning',
                        button: 'تایید'
                    });
                }
            });
        }
    });

});


 */
