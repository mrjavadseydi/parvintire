<div id="files-groups">

    <div class="table-responsive mb10">
        <table class="mb10">
            <thead>
            <tr>
                <th style="width: 100px;">
                    افزودن گروه
                </th>
                <th>
                    <input style="background: white;" id="groupTitle" class="bg-white" type="text" name="" placeholder="عنوان">
                </th>
                <th style="width: 300px">
                    <span class="add-group btn-lg btn-success">ذخیره</span>
                    <span callback="fileCopyLink" key="files" buttonTitle="کپی لینک" class="uploader-open btn-lg btn-info">بارگذاری فایل</span>
                </th>
                <script>
                    function fileCopyLink(data) {
                        $('body').append('<input id="fileLinkCopy" type="text" value="'+data['result']['path']+'">');
                        copyToClipboard('fileLinkCopy');
                        if ($('#fileLinkCopy').lenght > 0) {
                            $('#fileLinkCopy').remove();
                        }
                    }
                    function copyToClipboard(id) {
                        var copyText = document.getElementById(id);
                        copyText.select();
                        copyText.setSelectionRange(0, 99999); /*For mobile devices*/
                        document.execCommand("copy");
                        iziToast.success({
                            title: '',
                            message: 'لینک با موفقیت کپی شد',
                            position: 'bottomRight',
                            rtl: true,
                        });
                    }
                </script>
            </tr>
            </thead>
        </table>
    </div>

    <div id="group-sortable">
        @if(isset($filesGroups))
            @foreach($filesGroups as $item)
                <div groupId="{{ $item['id'] }}" class="file-group table-responsive mb10">
                    <table class="mb10">
                        <thead>
                            <tr>
                                <th colspan="4" class="tar">{{ $item['title'] }}</th>
                                <th style="width: 215px">
                                    <span groupId="{{ $item['id'] }}" class="update-group btn-sm btn-orange">ویرایش</span>
                                    <span groupId="{{ $item['id'] }}" class="add-update-file btn-sm btn-success">افزودن فایل</span>
                                    <span groupId="{{ $item['id'] }}" class="delete-group btn-sm btn-danger">حذف گروه</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="group-{{ $item['id'] }}-files-sortable">
                            @foreach($item['files'] as $file)
                                <tr fileId="{{ $file['file']->id }}" class="file">
                                    <td class="tac">{{ $file['file']->title }}</td>
                                    <td class="tac">{{ $file['attachment']->path }}</td>
                                    <td class="tac">{{ config("files.type.{$file['file']->type}.title") }}</td>
                                    <td class="tac">{{ config("files.status.{$file['file']->status}.title") }}</td>
                                    <td class="tac">
                                        <span fileId="{{ $file['file']->id }}" groupId="{{ $item['id'] }}" class="add-update-file btn-sm btn-orange edit">ویرایش</span>
                                        <span fileId="{{ $file['file']->id }}" groupId="{{ $item['id'] }}" class="delete-file btn-sm btn-danger edit">حذف</span>
                                        <a href="{{ url("admin/attachments/{$file['attachment']->id}/edit") }}" target="_blank" class="btn-sm btn-purple edit">ویرایش فایل</a>
                                    </td>
                                </tr>
                            @endforeach
                            <script>
                                $( function() {
                                    $( "#group-{{ $item['id'] }}-files-sortable" ).sortable({
                                        placeholder: "ui-state-highlight",
                                        stop: function( event, ui ) {
                                            files = [];
                                            $('#group-{{ $item['id'] }}-files-sortable .file').each(function (i, item) {
                                                files[i] = $(item).attr('fileId');
                                            });
                                            $.ajax({
                                                url: "/api/files/sort-files",
                                                method: 'post',
                                                data: {
                                                    files: files,
                                                    _token: '{{ csrf_token() }}'
                                                },
                                                success: function (data) {
                                                    if(data.status == 'success') {
                                                        iziToast.success({
                                                            title: '',
                                                            message: data.message,
                                                            position: 'bottomRight',
                                                            rtl: true,
                                                        });
                                                    } else {
                                                        iziToast.error({
                                                            title: '',
                                                            message: data.message,
                                                            position: 'bottomRight',
                                                            rtl: true,
                                                        });
                                                    }
                                                }
                                            });
                                        }
                                    });
                                    $( "#group-{{ $item['id'] }}-files-sortable" ).disableSelection();
                                });
                            </script>
                        </tbody>
                    </table>
                </div>
            @endforeach
        @else
            <script>
                $(document).ready(function() {
                    loadFiles();
                });
            </script>
        @endif
    </div>

</div>

<script>

    $( function() {
        $( "#group-sortable" ).sortable({
            placeholder: "ui-state-highlight",
            stop: function( event, ui ) {
                groups = [];
                $('.file-group').each(function (i, item) {
                    groups[i] = $(item).attr('groupId');
                });
                $.ajax({
                    url: "/api/files/sort-groups",
                    method: 'post',
                    data: {
                        postId: '{{ $post->id }}',
                        groups: groups,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (data) {
                        if(data.status == 'success') {
                            iziToast.success({
                                title: '',
                                message: data.message,
                                position: 'bottomRight',
                                rtl: true,
                            });
                        } else {
                            iziToast.error({
                                title: '',
                                message: data.message,
                                position: 'bottomRight',
                                rtl: true,
                            });
                        }
                    }
                });
            }
        });
        $( "#group-sortable" ).disableSelection();
    });

    $('.add-group').click(function () {
        $(this).text('...').attr('disabled', 'disabled');
        $.ajax({
            url: "/api/files/add-group",
            method: 'post',
            data: {
                postId: '{{ $post->id }}',
                title: $('#groupTitle').val(),
                sort: $('.file-group').length,
                _token: '{{ csrf_token() }}'
            },
            success: function (data) {
                if(data.status == 'success') {
                    loadFiles();
                } else {
                    iziToast.error({
                        title: '',
                        message: data.message,
                        position: 'bottomRight',
                        rtl: true,
                    });
                    $('.add-group').text('ذخیره');
                }
            }
        });
    });

    $('#groupTitle').keyup(function (e) {
        if (e.keyCode === 13) {
            $('.add-group').click();
        }
    });

    $('.delete-group').click(function () {
        var el = $(this);
        $(this).text('...').attr('disabled', 'disabled');
        $.ajax({
            url: "/api/files/delete-group",
            method: 'post',
            data: {
                groupId: $(this).attr('groupId'),
                _token: '{{ csrf_token() }}'
            },
            success: function (data) {
                if(data.status == 'success') {
                    loadFiles();
                } else {
                    iziToast.error({
                        title: '',
                        message: data.message,
                        position: 'bottomRight',
                        rtl: true,
                    });
                    el.text('حذف گروه');
                }
            }
        });
    });

    $('.add-update-file').click(function () {
        var el = $(this);
        $(this).text('...').attr('disabled', 'disabled');
        $('#add-update-file').remove();
        $.ajax({
            url: "/api/files/get-file",
            method: 'post',
            data: {
                _token: '{{ csrf_token() }}',
                view: 'boxes.default.files-groups-add-file',
                fileId: $(this).attr('fileId'),
                groupId: $(this).attr('groupId'),
                postId: '{{ $post->id }}'
            },
            success: function (data) {
                if(data.view) {
                    el.closest('table').before(data.html);
                }
                if(el.hasClass('edit')) {
                    el.text('ویرایش');
                } else {
                    el.text('افزودن فایل');
                }
            }
        });
    });

    $('.update-group').click(function () {
        var el = $(this);
        $(this).text('...').attr('disabled', 'disabled');
        $('#update-group').remove();
        $.ajax({
            url: "/api/files/get-group",
            method: 'post',
            data: {
                _token: '{{ csrf_token() }}',
                view: 'boxes.default.files-groups-add-group',
                groupId: $(this).attr('groupId'),
                postId: '{{ $post->id }}'
            },
            success: function (data) {
                if(data.view) {
                    el.closest('table').before(data.html);
                }
                el.text('ویرایش');
            }
        });
    });

    $('.delete-file').click(function () {
        var el = $(this);
        $(this).text('...').attr('disabled', 'disabled');
        $.ajax({
            url: "/api/files/delete-file",
            method: 'post',
            data: {
                _token: '{{ csrf_token() }}',
                fileId: $(this).attr('fileId')
            },
            success: function (data) {
                if(data.status == 'success') {
                    loadFiles();
                } else {
                    iziToast.error({
                        title: '',
                        message: data.message,
                        position: 'bottomRight',
                        rtl: true,
                    });
                    el.text('حذف');
                }
            }
        });
    });

    function loadFiles() {
        $.ajax({
            url: "/api/files",
            method: 'post',
            data: {
                postId: '{{ $post->id }}',
                view: 'boxes.default.files-groups',
                _token: '{{ csrf_token() }}'
            },
            success: function (data) {
                if(data.status == 'success') {
                    if(data.view) {
                        $('#files-groups').html(data.html);
                    }
                }
            }
        });
    }

</script>

<style>
    .ui-state-highlight { height: 5em; line-height: 3em; margin: 1em 0; }
    #files-groups td {
        color: black;
    }
</style>
