<div class="message-notification">

    <div class="search">
        <span class="icon-search"></span>
        <input type="text">
    </div>

    <div class="body">

        @include('admin.notifications.sections.comments')

        <?php
        $notifications = [
//            'comments' => [
//                'title' => 'نظرات',
//                'label' => 'نظرات درانتظار تایید',
//                'icon'  => 'icon-bubbles',
//                'count' => \JavadGholipoor\Notifications::commentCount(),
//                'image' => asset('images/notification/comment.png'),
//                'time'  => '-',
//                'operation' => [
//                    'info' => [
//                        'title' => 'اطلاعات',
//                    ],
//                    'success' => [
//                        'title' => 'مشاهده',
//                    ],
//                    'warning' => [
//                        'title' => 'ویرایش',
//                    ],
//                    'danger' => [
//                        'title' => 'حذف',
//                    ],
//                ]
//            ],
//            'questions' => [
//                'title' => 'سوالات',
//                'label' => 'سوالات درانتظار تایید',
//                'icon'  => 'icon-question',
//                'count' => \JavadGholipoor\Notifications::questionCount(),
//                'image' => asset('images/notification/question.png'),
//                'time'  => '-',
//                'operation' => [
//                    'info' => [
//                        'title' => 'اطلاعات',
//                    ],
//                    'success' => [
//                        'title' => 'مشاهده',
//                    ],
//                    'warning' => [
//                        'title' => 'ویرایش',
//                    ],
//                    'danger' => [
//                        'title' => 'حذف',
//                    ],
//                ]
//            ],
//            'answers' => [
//                'title' => 'پاسخ ها',
//                'label' => 'پاسخ های درانتظار تایید',
//                'icon'  => 'icon-lightbulb_outline',
//                'count' => \JavadGholipoor\Notifications::answers(),
//                'image' => asset('images/notification/answer.png'),
//                'time'  => '-',
//                'operation' => [
//                    'info' => [
//                        'title' => 'اطلاعات',
//                    ],
//                    'success' => [
//                        'title' => 'مشاهده',
//                    ],
//                    'warning' => [
//                        'title' => 'ویرایش',
//                    ],
//                    'danger' => [
//                        'title' => 'حذف',
//                    ],
//                ]
//            ]
        ];
        ?>

        @foreach ($notifications as $key => $notification)
            <div class="item toggle-class" find=".operation" slideDownUp toggle-class="active">
                <img src="{{ $notification['image'] }}" alt="{{ $notification['title'] }}">
                <div class="body">
                    <div class="info">
                        <i class="{{ $notification['icon'] }}"></i>
                        <span>{{ $notification['title'] }}</span>
                        <small>{{ $notification['time'] }}</small>
                    </div>
                    <div class="info">
                        <label>{{ $notification['label'] }}</label>
                        <small class="badge">{{ $notification['count'] }}</small>
                    </div>
                </div>
                <div class="operation">
                    @foreach ($notification['operation'] as $k => $value)
                        <span class="{{ $k }}">{{ $value['title'] }}</span>
                    @endforeach
                </div>
            </div>
        @endforeach

    </div>

</div>
