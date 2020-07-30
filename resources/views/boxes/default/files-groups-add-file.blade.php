<div id="add-update-file">
    <form id="addFileForm" method="post" action="{{ url('/api/files/add-file') }}" class="files-groups-add-file">
        <input type="hidden" name="postId" value="{{ $postId }}">
        <input type="hidden" name="groupId" value="{{ $groupId }}">
        <div class="row">
            <div class="col-md-5">
                <div class="input-group">
                    <label>عنوان</label>
                    <input type="text" name="title" value="{{ $file->title ?? '' }}">
                </div>
            </div>
            <div class="col-md-2">
                <div class="input-group">
                    <label>نوع</label>
                    <select name="type">
                        <option value="0">رایگان</option>
                        <option {{ selected($file->type ?? '', '1') }} value="1">اعضا</option>
                        <option {{ selected($file->type ?? '', '2') }} value="2">اشتراک</option>
                        <option {{ selected($file->type ?? '', '3') }} value="3">نقدی</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="input-group">
                    <label>وضعیت</label>
                    <select class="activeName" name="status">
                        <option value="1">فعال</option>
                        <option {{ selected($file->status ?? '', '0') }} value="0">غیرفعال</option>
                        <option {{ selected($file->status ?? '', '2') }} value="2">انتشار در آینده</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="input-group">
                    <label>یادداشت</label>
                    <input type="text" name="note" value="{{ $file->note ?? '' }}">
                </div>
            </div>
            <div class="col-md-3 mt10">
                <div class="input-group">
                    <label>سرور</label>
                    <select class="ltr" name="server">
                        <option value="website">{{ url('') }}</option>
                        @foreach(\LaraBase\Options\Models\Option::where('key', 'servers')->get() as $serv)
                            <option {{ selected($file->server ?? '', $serv->value) }} value="{{ $serv->value }}">{{ $serv->more }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-9 mt10">
                <div class="input-group">
                    <label>مسیر فایل</label>
                    <input class="ltr" type="text" name="path" value="{{ $attachment->path ?? '' }}">
                </div>
            </div>
            <div class="col-12 mt10 tal">
                <span onclick="$(this).closest('.files-groups-add-file').remove();" class="delete-process-file btn btn-danger">بستن</span>
                <button class="add-file-btn btn btn-success">ذخیره</button>
            </div>
        </div>
    </form>
    <script>
        $('#addFileForm').ajaxForm({
            beforeSubmit: function beforeSubmit(data, jqForm, options) {
                $('.add-file-btn').text('...');
            },
            complete: function complete(data) {
                var response = $.parseJSON(data.responseText);
                if(response.status == 'success') {
                    loadFiles();
                } else {
                    iziToast.error({
                        title: '',
                        message: response.message,
                        position: 'bottomRight',
                        rtl: true,
                    });
                    $('.add-file-btn').text('ذخیره');
                }
            },
            error: function error(data) {
            }
        });
    </script>
    <style>

        .files-groups-add-file {
            background: #dfeef7;
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
            color: black;
        }

        .files-groups-add-file input, .files-groups-add-file select {
            background: white;
        }

    </style>
</div>
