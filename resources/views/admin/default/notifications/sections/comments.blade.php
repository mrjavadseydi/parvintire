@can('commentsNotif')
    @foreach (\JavadGholipoor\Notifications::comments() as $record)
        @php
            $user = $record->user;
            $img = 'comment.png';
            $icon = 'icon-bubbles';
            $title = $record->title;
            $label = $record->comment;
            if ($record->parent == '0') {
                $img = 'question.png';
                $icon = 'icon-help';
                $label = '-';
            } else if ($record->parent > 0) {
                $img = 'answer.png';
                $icon = 'icon-wb_incandescent rotate-180';
                $title = $record->question('title');
                $label = $record->title;
            }
        @endphp
        <div class="item toggle-class" find=".operation" slideDownUp toggle-class="active">
            <img src="{{ $user->image() }}" alt="{{ $record->title }}">
            <div class="body">
                <div class="info">
                    <i class="{{ $icon }}"></i>
                    <span>{{ $title }}</span>
                    <small>{{ $record->datetime() }}</small>
                </div>
                <div class="info">
                    <label>{{ $label }}</label>
                </div>
            </div>
            <div class="operation">
                <span id="{{ $record->id }}" class="publish-comment success">انتشار</span>
                <span class="info"><a href="{{ url('/admin/comments/'.$record->id.'/edit') }}" target="_blank" >مشاهده نظر</a></span>
                <span class="warning"><a href="{{ url('/admin/posts/'.$record->post_id.'/edit') }}" target="_blank" >مشاهده مطلب</a></span>
                <span class="danger"><a href="{{ url('/admin/users/'.$record->user_id.'/edit') }}" target="_blank" >مشاهده کاربر</a></span>
            </div>
        </div>
    @endforeach
@endif

<script>

    $(document).on('click', '.publish-comment', function () {
        var el = $(this);
        var id = el.attr('id');
        swal({
            'title': 'انتشار نظر',
            'text': 'آیا از انتشار این مورد اطمینان دارید؟',
            icon: "success",
            buttons: ['لغو', 'انتشار'],
        }).then(function (willDelete) {
            if (willDelete) {
                _url = '/admin/comments/' + id + '/ajaxPublish';
                _token = $('meta[name=csrf-token]').attr('content');

                $.ajax({
                    url: _url,
                    type: 'POST',
                    data: {
                        '_token': _token,
                    },
                    success: function (response, status) {
                        if (response.result === true) {
                            el.closest('.toggle-class').fadeOut(500);
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

</script>
