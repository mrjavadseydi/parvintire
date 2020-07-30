<div id="update-group">
    <form id="addFileGroupForm" method="post" action="{{ url('/api/files/update-group') }}" class="files-groups-add-file">
        <input type="hidden" name="postId" value="{{ $post->id }}">
        <input type="hidden" name="groupId" value="{{ $group->id }}">
        <div class="row">
            <div class="col-md-9 tac">
                <div class="input-group">
                    <input type="text" name="title" value="{{ $group->title ?? '' }}">
                </div>
            </div>
            <div class="col-3 tac mt8">
                <span onclick="$(this).closest('.files-groups-add-file').remove();" class="delete-process-file btn btn-danger">بستن</span>
                <button class="add-group-btn btn btn-success">ذخیره</button>
            </div>
        </div>
    </form>
    <script>
        $('#addFileGroupForm').ajaxForm({
            beforeSubmit: function beforeSubmit(data, jqForm, options) {
                $('.add-group-btn').text('...');
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
                    $('.add-group-btn').text('ذخیره');
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
