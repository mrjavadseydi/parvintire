uploader = null;

(function ( $ ) {

    var input = null;

    var options = {
        url: null,
        data: null,
        accept: null,
        multiple: true,
        onAddFiles: null,
        onProgress: null,
        onComplete: null,
        onCompleteAddFiles: null,
        uploadIn: 1, // 1:public | 2:root, 3:ftp
    };

    var ajax = null;
    var uploads = [];
    var uploadKeys = [];
    var uploading = false;

    $.fn.uploader = function() {
        return this;
    };

    $.fn.options = function (setOptions) {
        options = $.extend(options, setOptions);
    };

    $.fn.load = function () {

        $('#javadgholipoor-uploader-input').remove();

        input = $('<input/>')
            .css('display', 'none').attr('type', 'file')
            .attr('id', 'javadgholipoor-uploader-input')
            .attr('accept', options.accept)
            .attr('name', 'files');

        if (options.multiple)
            input.attr('multiple', 'multiple')

        $('body').append(input);

        input[0].addEventListener('change', function () {
            uploader.addFiles($(this)[0].files);
        }, false);

        input.click();

    };

    $.fn.addFiles = function (files) {

        var index = uploadLength(uploads);

        var addedFiles = {};

        $.each(files, function (i, obj) {

            var formData = new FormData();
            var key = "i" + index;

            if ($(options.data).length > 0) {
                $(options.data + ' input').each(function (i, input) {
                    formData.append($(input).attr('name'), $(input).val());
                });
            }

            formData.append("file", files[i]);
            formData.append("uploadIn", options.uploadIn);
            formData.append('_token', $('meta[name=csrf-token]').attr('content'));

            uploads[key] = {};
            uploads[key]['key'] = key;
            uploads[key]['formData'] = formData;

            if (options.onProgress != null)
                uploads[key]['onProgress'] = options.onProgress;

            if (options.onComplete != null)
                uploads[key]['onComplete'] = options.onComplete;

            addedFiles[index] = {
                key: key,
                file: files[i]
            };

            if (options.onAddFiles != null) {

                var callback = eval(options.onAddFiles);
                if (typeof callback == 'function') {
                    callback(addedFiles[index]);
                }

            }

            index++;

        });

        if (options.onAddFiles != null) {

            var callback = eval(options.onCompleteAddFiles);
            if (typeof callback == 'function') {
                callback(addedFiles);
            }

        }

        if (!uploading) {
            this.startUpload();
            uploading = true;
        }

    };

    $.fn.startUpload = function () {

        if (uploadLength(uploads) > 0) {

            if (options.url == null) {
                alert('url not found');
                return;
            }

            var data = uploadsPop(uploads);

            ajax = $.ajax({
                url: options.url,
                type: 'post',
                data: data.formData,
                dataType: 'json',
                async: true,
                processData: false,
                contentType: false,
                xhr: function () {
                    var myXhr = $.ajaxSettings.xhr();
                    myXhr.upload.addEventListener('progress', function (e) {
                        if (e.lengthComputable) {
                            var percent = parseInt((e.loaded / e.total) * 100) + "%";

                            if ("onProgress" in data) {
                                var callback = eval(data.onProgress);
                                if (typeof callback == 'function') {
                                    callback(percent);
                                }
                            }
                        }
                    });
                    return myXhr;
                },
                success: function (data) {

                    if ("onComplete" in data) {
                        var callback = eval(data.onComplete);
                        if (typeof callback == 'function') {
                            callback('success', data);
                        }
                    }

                    $(this).startUpload();

                },
                error: function (data) {

                    if ("onComplete" in data) {
                        var callback = eval(data.onComplete);
                        if (typeof callback == 'function') {
                            callback('error', data);
                        }
                    }

                    $(this).startUpload();

                }
            });

        } else {
            uploading = false;
        }

    };

    function uploadsPop(uploads) {
        var index = Object.keys(uploads)[0];
        var output = uploads[index];
        delete uploads[index];
        return output;
    };

    function uploadLength(array) {
        var count = 0;
        for (var i in array) {
            count++;
        }
        return count;
    };

}( jQuery ));

$(document).on('click', '.uploader-init', function () {

    var el = $(this);

    if (uploader == null) {
        uploader = el.uploader();
    }

    var options = {};
    var attributes = [
        'url', 'accept', 'multiple', 'data', 'uploadIn', 'onAddFiles', 'onCompleteAddFiles', 'onProgress', 'onComplete', 'item', 'body', 'uploader'
    ];
    $(attributes).each(function (i, attr) {
        var checkAttr = el.attr(attr);
        if (typeof checkAttr !== typeof undefined && checkAttr !== false) {
            options[attr] = el.attr(attr);
        }
    })

    uploader.options(options);
    uploader.load();

});
