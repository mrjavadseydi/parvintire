{{ renderTheme('uploader', 'default') }}

@if(isset($inputs))
    <div class="inputs">
        @foreach($inputs as $name => $value)
            <input type="hidden" name="{{ $name }}" value="{{ $value }}">
        @endforeach
    </div>
@endif

<input type="hidden" name="uploadUrl" value="{{ route('upload') }}">
<input type="hidden" id="buttonTitle" value="{{ config('uploader.buttonTitle') }}">

<div class="uploader">

    <div class="uploader-box">

        <div class="uploader-box-header">
            @include('uploader.default.header')
        </div>

        <div class="uploader-box-body">

            <div class="uploader-box-body-sidebar">
                <div id="newUpload" class="uploader-drag"
                     url="{{ url('upload') }}"
                     onAddFiles="uploaderOnAddFiles"
                     onProgress="uploaderOnProgress"
                     onComplete="uploaderOnComplete"
                     class="uploader-click upload-btn">
                    <div class="d-table">
                        <div class="d-table-cell">
                            <span class="db mb5">برای بارگذاری، پرونده ها را به اینجا بکشید</span>
                            <small class="db mb20">یا</small>
                            <label url="{{ url('upload') }}"
                                   onAddFiles="uploaderOnAddFiles"
                                   onProgress="uploaderOnProgress"
                                   onComplete="uploaderOnComplete"
                                   class="uploader-click upload-btn">
                                بارگذاری پرونده‌ها
                            </label>
                        </div>
                    </div>
                </div>
                <div id="image-cropper">
                    <div class="image-cropper-tools d-none">

                        <div class="group h-auto image-cropper-all-tools image-cropper-full d-none">
                            <img class="full-height fa-rotate-90" src="{{ image('expand-width.png', 'uploader') }}" alt="">
                            <img class="full-width" src="{{ image('expand-width.png', 'uploader') }}" alt="">
                        </div>

                        <div class="group h-auto image-cropper-all-tools image-cropper-scale d-none">
                            <img class="toggle-scale-y" scale="-1" src="{{ image('arrows-alt-v-solid.svg', 'uploader') }}"
                                 alt="">
                            <img class="toggle-scale-x" scale="-1" src="{{ image('arrows-alt-h-solid.svg', 'uploader') }}"
                                 alt="">
                        </div>

                        <div class="group h-auto image-cropper-all-tools image-cropper-zoom d-none">
                            <img onclick="cropper.zoom(0.1)" src="{{ image('search-plus-solid.svg', 'uploader') }}" alt="">
                            <img onclick="cropper.zoom(-0.1)" src="{{ image('search-minus-solid.svg', 'uploader') }}"
                                 alt="">
                        </div>

                        <div class="group h-auto image-cropper-all-tools image-cropper-move d-none">
                            <img onclick="cropper.move(0, 10)" src="{{ image('arrow-down-solid.svg', 'uploader') }}" alt="">
                            <img onclick="cropper.move(0, -10)" src="{{ image('arrow-up-solid.svg', 'uploader') }}" alt="">
                            <img onclick="cropper.move(10, 0)" src="{{ image('arrow-right-solid.svg', 'uploader') }}"
                                 alt="">
                            <img onclick="cropper.move(-10, 0)" src="{{ image('arrow-left-solid.svg', 'uploader') }}"
                                 alt="">
                        </div>

                        <div class="group h-auto image-cropper-all-tools image-cropper-rotate d-none">
                            <img onclick="cropper.rotate(45)" src="{{ image('redo-solid.svg', 'uploader') }}" alt="">
                            <img onclick="cropper.rotate(-45)" src="{{ image('undo-solid.svg', 'uploader') }}" alt="">
                        </div>

                    </div>
                </div>
            </div>

            <div id="uploader-tabs-content" class="uploader-box-body-content">

                @include('uploader.default.body.uploaded-files')

                @include('uploader.default.body.image-cropper')

            </div>

        </div>

        <div class="uploader-box-footer">
            @include('uploader.default.footer')
        </div>

    </div>

</div>


<script>

    el = null;
    key = null;
    uploader = null;
    uploadedBody = null;
    uploadingBody = null;
    $(document).ready(function () {
        uploader = $('.uploader');
        uploadedBody = $('.uploader-box-body-content .uploaded-body');
        uploadingBody = $('.uploader-box-body-content .uploading-body');
    })

    uploaderData = [];

    $(document).on('click', '.uploader-item .delete', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var el = $(this);
        if (el.hasClass('confirm')) {
            el.parent().remove();
            $.ajax({
                url: "{{ url('upload/delete') }}/" + el.attr('attachment'),
                type: 'post',
                data: {
                    _token: $('meta[name=csrf-token]').attr('content')
                },
                success: function (data) {
                    if (data.status == 'success') {

                    } else {

                    }
                }
            });
        } else {
            el.addClass('confirm');
            var src = el.attr('src');
            var rep = el.attr('confirm');
            el.attr('src', rep);
            el.attr('confirm', src);
            setTimeout(function () {
                var src = el.attr('src');
                var rep = el.attr('confirm');
                el.attr('src', rep);
                el.attr('confirm', src);
                el.removeClass('confirm');
            }, 3000);
        }
    });

    $(document).on('click', '#uploader-tabs li', function () {

        $('#uploader-tabs li').removeClass('active');
        $(this).addClass('active');
        $('#uploader-tabs-content > div').removeClass('active');
        var id = $(this).attr('id');
        $('#uploader-tabs-content #' + id).addClass('active');

        var btnText = $('.uploader-output-button').text();
        $('.uploader-output-button').text(btnText.replace('برش و', ''));

        if ($(this).attr('id') == 'image-cropper') {
            initImageCropper();
            $('#newUpload').hide();
            $('.image-cropper-tools').show();
        } else {
            $('#newUpload').show();
            $('.image-cropper-tools').hide();
        }

    });

    function addError(message) {
        uploaderSidebar.prepend(
            '<div class="uploader-error">\n' +
            '   <p>'+message+'</p>\n' +
            '   <div class="left">' +
            '   <span class="uploader-error-close">بستن</span>' +
            '   </div>' +
            '</div>'
        )
    }

    $(document).on('click', '.uploader-error-close', function () {
        $(this).parent().parent().remove();
    });

    $(document).on('click', '.upload-cancel', function () {
        $(this).parent().remove();
    });

    $(document).on('click', '.uploader-item', function () {

        attachmentId = $(this).attr('id');

        if (!uploader.hasClass('multiple')) {
            $('.uploader-item').removeClass('selected');

            hideToolsTab();
            showImageCropper(this);
        }

        $('.uploader-item').removeClass('active');
        $(this).toggleClass('selected').addClass('active');

        if (!$(this).hasClass('getInfo')) {
            getFileInfo($(this));
        }

    });

    function uploaderOnAddFiles(response) {
        uploadingBody.prepend(
            "<div class='uploader-item icon uploading'>\n" +
            "   <span class='upload-progress'></span>\n" +
            "   <span class='upload-percent'>در حال بارگذاری...</span>\n" +
            "   <small id='"+response.key+"' class='uploader-cancel upload-cancel'>لغو</small>\n" +
            "   <span class='upload-title'>"+response['file']['name']+"</span>\n" +
            "</div>"
        );
    }

    function uploaderOnProgress(percent, key) {
        $('#'+key).parent().find('.upload-progress').css('height', percent);
        $('#'+key).parent().find('.upload-percent').text(percent);
    }

    function uploaderOnComplete(status, data) {
        $('#'+data.key).parent().remove();

        if (status == 'success') {
            if (data.status == 'success') {
                uploadedBody.prepend(
                    '<div id="'+data.id+'" path="'+data.path+'" url="'+data.url+'" thumbnail="'+data.thumbnail+'" name="'+data.name+'" class="uploader-item '+data.imageIcon+' type-'+data.type+' subtype-'+data.subType+'">\n' +
                    '   <img class="select" src="'+data.selectIcon+'" alt="selected icon">' +
                    '   <img attachment="'+data.id+'" class="delete" confirm="'+data.checkedIcon+'" src="'+data.deleteIcon+'" alt="delete image">' +
                    '   <div class="img">\n' +
                    '       <img src="'+data.thumbnail+'" alt="'+data.name+'">\n' +
                    '   </div>\n' +
                    '   <span class="upload-title">'+data.name+'</span>\n' +
                    '</div>'
                );
            }
        }

    }

    $(document).on('click', '.uploader-open', function (event) {

        event.stopPropagation();

        el = $(this);

        $('#uploaded').click();
        hideToolsTab();
        imageCropTools(this);
        $('.uploader-item').removeClass('selected');

        if ($(this).attr('callback')) {
            uploader.attr('callback', $(this).attr('callback'));
        }

        key = null;
        var attr = $(this).attr('key');
        if (typeof attr !== typeof undefined && attr !== false) {
            key = $(this).attr('key');
        }

        $('.upload-btn').attr('key', key);

        if ($(this).hasClass('imageCropper')) {
            uploader.addClass('imageCropper');
        }

        if ($(this).hasClass('multiple')) {
            openUploader(true, $(this));
        } else {
            openUploader(false, $(this));
        }

    });

    function hideToolsTab() {
        $('.tools-tab').addClass('d-none');
    }

    function openUploader(multiple, el) {

        if (multiple) {
            uploader.addClass('multiple');
        } else {
            uploader.removeClass('multiple');
        }

        var title = el.attr('buttonTitle');
        if (title) {
            $('.uploader-output-button').text(title)
        } else {
            $('.uploader-output-button').text($('#buttonTitle').val());
        }

        uploader.addClass('active');
        uploadedBody.html('');
        uploadedBody.html($('#uploader-loading').html());
        uploaderAjax(1);

    }

    $(document).on('click', '.uploader .pagination a', function (e) {
        e.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        uploaderAjax(page);
    });

    function uploaderAjax(page) {
        $.ajax({
            url: "/upload/files?page=" + page,
            data: {
                key: key,
                view: 'body.uploaded-files',
                count: 25,
                _token: $('meta[name=csrf-token]').attr('content')
            },
            success: function (data) {
                uploadedBody.html(data);
                $('.uploader-box-body-content #uploaded').animate({scrollTop: uploadingBody.height()}, 'fast');
            }
        });
    }

    $(document).on('dragleave', '#newUpload', function (e) {
        e.stopPropagation();
        e.preventDefault();
        $(this).removeClass('drag')
    }).on('dragover', '#newUpload', function (e) {
        e.stopPropagation();
        e.preventDefault();
        $(this).addClass('drag')
    });

    function getFileInfo(el) {
        $('.uploader-item').removeClass('getInfo');

        // type code
        // addError('ajax');

        el.addClass('getInfo');
    }

    function getSelectedData() { // TODO تبدیل شود به تکی و مالتی پل
        var selected = $('.uploader-item.selected');
        return {
            'id': selected.attr('id'),
            'thumbnail': selected.attr('thumbnail'),
            'url': selected.attr('url'),
            'path': selected.attr('path'),
            'name': selected.attr('name')
        };
    }

    $(document).on('click', '.uploader-output-button', function () {
        if (uploader.hasClass('imageCropper')) {
            submitImageCropper(this);
            return;
        }
        uploaderData['status'] = 'success';
        closeUploader();
    });

    $(document).on('click', '.uploader-close', function () {
        uploaderData['status'] = 'error';
        closeUploader();
    });

    function uploaderIsSuccessStatus() {
        if (uploaderData['status'] == 'success')
            return true;

        return false;
    }

    function closeUploader() {
        uploader.removeClass('active');

        if (uploaderIsSuccessStatus()) {

            uploaderData['result'] = [];

            if (uploader.hasClass('multiple')) {

                uploaderData['multiple'] = true;
                var multipleData = [];
                $('.uploader-item.selected').each(function (i, el) {
                    var file = $(el);
                    multipleData[i] = [];
                    multipleData[i]['id'] = file.attr('id');
                    multipleData[i]['url'] = file.attr('url');
                    multipleData[i]['path'] = file.attr('path');
                    multipleData[i]['name'] = file.attr('name');
                    multipleData[i]['thumbnail'] = file.attr('thumbnail');
                });
                uploaderData['result'] = multipleData;

            } else {
                var file = $('.uploader-item.selected');
                uploaderData['multiple'] = false;
                uploaderData['result']['id'] = file.attr('id');
                uploaderData['result']['url'] = file.attr('url');
                uploaderData['result']['path'] = file.attr('path');
                uploaderData['result']['name'] = file.attr('name');
                uploaderData['result']['thumbnail'] = file.attr('thumbnail');
            }

            callback();

        }

    }

    function callback() {
        var callback = eval($('.uploader').attr('callback'));
        if (typeof callback == 'function') {
            callback(uploaderData);
        }
    }

    cropper = null;
    cropperOldInit = false;

    function showImageCropper(el) {
        var tab = $('#image-cropper');
        if (tab.length > 0) {
            $.each(tab.attr('sub-types').split(' '), function (i, val) {
                if ($(el).hasClass('subtype-'+val)) {
                    $('#image-cropper').removeClass('d-none');
                }
            });
        }
    }

    function initImageCropper() {

        $('.uploader-output-button').text('برش و ' + $('.uploader-output-button').text());
        var selectedData = getSelectedData();
        var imageUrl = selectedData['url'];

        if (cropperOldInit) {
            cropper.destroy();
        }

        $('#image-crop').attr('src', imageUrl)
        const image = document.getElementById('image-crop');


        var options = {
            center: true,
            guides: false,
            maxWidth: 640,
            maxHeight: 350,
            responsive: true,
            minContainerWidth: 640,
            minContainerHeight: 400,
            crop(event) {
                // console.log(event.detail.x);
                // console.log(event.detail.y);
                // console.log(event.detail.width);
                // console.log(event.detail.height);
                // console.log(event.detail.rotate);
                // console.log(event.detail.scaleX);
            },
            cropmove(event) {
                // var canvasData = cropper.getCanvasData();
                // var cropBoxData = cropper.getCropBoxData();
                //
                // if (
                //     cropBoxData.left < canvasData.left ||
                //     cropBoxData.top  < canvasData.top  ||
                //     cropBoxData.left + cropBoxData.width > canvasData.width + canvasData.left ||
                //     cropBoxData.top + cropBoxData.height > canvasData.height + canvasData.top
                // ) {
                //     event.preventDefault();
                // }
                // cropData = cropper.getCropBoxData();
                // canvasData = cropper.getCanvasData();
                // cropper.setCanvasData({
                //     top: cropData.top
                // });
                // cropLeft = cropData.left;
                // canvasLeft = canvasData.left;
                // if (canvasLeft < cropLeft) {
                //     cropper.setCanvasData({
                //         left: cropData.width - canvasData.width
                //     });
                //     event.preventDefault();
                // }
                // if (canvasLeft > cropData.width - canvasData.width) {
                //     cropper.setCanvasData({
                //         left: cropData.width - canvasData.width
                //     });
                // }
            },
            cropend(event) {
                // data = cropper.getCropBoxData();
                // cropper.setCanvasData({
                //     top: data.top
                // });
            },
            ready(event) {
                containerData = cropper.getContainerData();
                cropBoxData = cropper.getCropBoxData();
                cropper.setCropBoxData({
                    left: (containerData.width / 2) - (cropBoxData.width / 2),
                    top: (containerData.height / 2) - (cropBoxData.height / 2)
                })
            }
        };

        var dragMode = el.attr('dragMode');
        if (typeof dragMode !== typeof undefined && dragMode !== false) {
            options['dragMode'] = dragMode;
        }

        var viewMode = el.attr('viewMode');
        if (typeof viewMode !== typeof undefined && viewMode !== false) {
            options['viewMode'] = parseInt(viewMode);
        }

        var aspectRatio = el.attr('aspectRatio');
        if (typeof aspectRatio !== typeof undefined && aspectRatio !== false) {
            var ar = aspectRatio.split('/');
            options['aspectRatio'] = parseInt(ar[0]) / parseInt(ar[1]);
        }

        var cropBoxResizable = el.attr('cropBoxResizable');
        if (typeof cropBoxResizable !== typeof undefined && cropBoxResizable !== false) {
            options['cropBoxResizable'] = parseInt(cropBoxResizable);
        }

        var cropBoxMovable = el.attr('cropBoxMovable');
        if (typeof cropBoxMovable !== typeof undefined && cropBoxMovable !== false) {
            options['cropBoxMovable'] = parseInt(cropBoxMovable);
        }

        cropper = new Cropper(image, options);
        cropperOldInit = true;

    }

    $(document).on('click', '.toggle-scale-x', function () {
        var scale = $(this).attr('scale');
        if (scale == 1) {
            $(this).attr('scale', '-1');
        } else {
            $(this).attr('scale', '1');
        }
        cropper.scaleX(scale);
    });

    $(document).on('click', '.toggle-scale-y', function () {
        var scale = $(this).attr('scale');
        if (scale == 1) {
            $(this).attr('scale', '-1');
        } else {
            $(this).attr('scale', '1');
        }
        cropper.scaleY(scale);
    });

    $(document).on('click', '.full-height', function () {
        var cropBoxData = cropper.getCropBoxData();
        cropper.setCanvasData({
            top: cropBoxData.top,
            height: cropBoxData.height
        })
        var cropBoxData = cropper.getCropBoxData();
        var canvasData = cropper.getCanvasData();
        cropper.setCanvasData({
            left: cropBoxData.left - ((canvasData.width - cropBoxData.width) / 2),
        })
    });

    $(document).on('click', '.full-width', function () {
        var cropBoxData = cropper.getCropBoxData();
        cropper.setCanvasData({
            left: cropBoxData.left,
            width: cropBoxData.width
        })
        var cropBoxData = cropper.getCropBoxData();
        var canvasData = cropper.getCanvasData();
        cropper.setCanvasData({
            top: cropBoxData.top - ((canvasData.height - cropBoxData.height) / 2),
        })
    });

    function imageCropTools(el) {
        $('.image-cropper-all-tools').addClass('d-none');
        var attr = $(el).attr('imageCropperTools');
        if (typeof attr !== typeof undefined && attr !== false) {
            if (attr == 'all') {
                $('.image-cropper-all-tools').removeClass('d-none');
            } else {
                $.each(attr.split(' '), function( index, value ) {
                    $('.image-cropper-'+value).removeClass('d-none');
                });
            }
        }
    }

    function submitImageCropper(element) {
        if ($('#image-cropper').hasClass('active')) {
            var btnText = $(element).text();
            $(element).text('لطفا صبر کنید...')

            var data = cropper.getData();
            var width = data.width;
            var height = data.height;
            var aspectRatio = el.attr('aspectRatio');
            if (typeof aspectRatio !== typeof undefined && aspectRatio !== false) {
                var ar = aspectRatio.split('/');
                var width = parseInt(ar[0]);
                var height = parseInt(ar[1]);
            }

            uploadAjax = $.ajax({
                url: $('input[name=imageCropperUrl]').val(),
                type: 'POST',
                data: {
                    _token: $('input[name=imageCropperToken]').val(),
                    attachmentId: attachmentId,
                    x: data.x,
                    y: data.y,
                    rotate: data.rotate,
                    cropperWidth: data.width,
                    cropperHeight: data.height,
                    width: width,
                    height: height,
                },
                success: function (response) {
                    if (response.status == 'success') {
                        uploaderData['result'] = [];
                        uploaderData['multiple'] = false;
                        uploaderData['status'] = 'success';
                        uploaderData['result']['id'] = response.id;
                        uploaderData['result']['url'] = response.url;
                        uploaderData['result']['path'] = response.path;
                        uploaderData['result']['name'] = response.name;
                        uploaderData['result']['thumbnail'] = response.thumbnail;
                        uploader.removeClass('active');
                        callback();
                    } else {
                        $(element).text(btnText);
                        alert(response.message);
                    }
                },
                error: function (data) {
                    $(element).text(btnText);
                    alert('خطایی پیش آمده است. لطفا مجددا امتحان کنید');
                }
            });

        } else {
            $('#image-cropper').click();
        }
    }

</script>
