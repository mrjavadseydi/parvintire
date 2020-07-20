$(document).ready(function () {

    if ($('#select2-tags').length > 0) {
        $('#select2-tags').select2({
            dir: $('#select2-tags').attr('dir'),
            tags: $('#select2-tags').attr('tags'),
            maximumSelectionLength: $('#select2-tags').attr('maximumSelectionLength'),
            ajax: {
                url: $('#select2-tags').attr('url'),
                data: function (params) {
                    return params;
                },
                processResults: function (data) {
                    return {
                        results: data.items
                    };
                }
            }
        });
    }

});
