<?php
    $usernames = [];
    $users = \LaraBase\Auth\Models\User::whereIn('id', $comments->pluck('user_id')->toArray())->get();
    foreach ($users as $user) {
        $usernames[$user->id] = $user->name();
    }
?>
<div class="comments">
    <form clear="#c1" class="form ajaxForm ajaxForm-iziToast" method="post" action="{{ route('addComment') }}">
        <input type="hidden" name="type" value="1">
        <input type="hidden" name="post_id" value="{{ $post->id }}">
        <div class="row">
            <div class="col-md-12">
                <div class="input-group">
                    <textarea id="c1" class="py-2" name="comment" placeholder="متن پیام خود را بنویسید"></textarea>
                </div>
            </div>
            <div class="col-12 text-left mt-3">
                <button>ارسال نظر</button>
            </div>
        </div>
    </form>
    <div class="pt-5 pb-2">
        @include(includeTemplate('divider.2'), [
            'title' => 'نظرات کاربران',
            'secondTitle' => '('. $post->commentCount() .' نظر)',
        ])
    </div>
    @foreach($comments as $comment)
        <div class="item mt-3">
        <div class="d-flex justify-content-between">
            <h6>{{ $usernames[$comment->user_id] ?? 'کاربر مهمان' }}</h6>
            <div class="position-relative">
                <span class="reply reply-comment">پاسخ</span>
                <span>|</span>
                <span>{{ jDateTime('H:i Y/m/d', strtotime($comment->created_at)) }}</span>
                <form action="{{ route('addComment') }}" method="post" clear=".clear-textarea" class="ajaxForm ajaxForm-iziToast reply-box p-2">
                    <input type="hidden" name="type" value="1">
                    <input type="hidden" name="post_id" value="{{ $post->id }}">
                    <input type="hidden" name="parent" value="{{ $comment->id }}">
                    <textarea name="comment" class="clear-textarea w-100 p-2" placeholder="پاسخ خود را بنویسید" type="text"></textarea>
                    <button class="btn btn-outline-success w-100">ثبت پاسخ</button>
                </form>
            </div>
        </div>
        <p class="pt-3">
            {{ $comment->comment }}
        </p>
        <div class="replys pr-5">
            @foreach(\LaraBase\Comments\Models\Comment::where(['parent' => $comment->id, 'status' => '2'])->get() as $c)
                <div class="item mt-3">
                    <div class="d-flex justify-content-between">
                        <h6>{{ $usernames[$c->user_id] ?? 'کاربر مهمان' }}</h6>
                        <div class="position-relative">
                            <span>{{ jDateTime('H:i Y/m/d', strtotime($c->created_at)) }}</span>
                        </div>
                    </div>
                    <p class="pt-3">
                        {{ $comment->comment }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
    @endforeach
    <div class="pagination-rtl mt-3">
        {{ $comments->appends(['section' => 'comment'])->links() }}
    </div>
</div>
@if(isset($_GET['section']))
    @if($_GET['section'] == 'comment')
        <script>
            $('html,body').animate({
                scrollTop: $('.comments').offset().top
            }, 0);
        </script>
    @endif
@endif
