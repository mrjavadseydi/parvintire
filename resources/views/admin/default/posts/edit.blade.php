<?php
    $title = "ویرایش مطلب ({$post->title})";
?>
@extends("admin.{$adminTheme}.master")
@section('title', $title)

@section('content')

    {!! uploader()->relation('post', $post->id)->validations(json_decode($postType->validations, true))->theme('default')->load() !!}

    <form id="post" action="{{ route('admin.posts.update', $post) }}" method="post">

        @csrf
        @method('patch')
        @if(!empty($post->needChange()))
            <div class="alert alert-danger">
                <p>کاربر عزیز در مطلب شما مواردی دیده شده که نیازمند تغییر می باشد لطفا بعد از اصلاح موارد مجددا پست را جهت بررسی ارسال کنید</p>
            </div>
            <div class="alert alert-warning">
                <p>{!! preg_replace("/\r\n/", '<br>', $post->needChange()) !!}</p>
            </div>
        @endif

        <div class="row">

            <div class="col-md-8">

                <div class="box box-primary">

                    <div class="box-header">
                        <h3 class="box-title">{{ $title }}</h3>
                        <div class="box-tools">
                            <i class="box-tools-icon icon-minus"></i>
                        </div>
                    </div>

                    <div class="box-body">
                        @include('admin.default.posts.box', [
                            'loadBoxes' => [
                                'title',
                                'slug',
                                'excerpt',
                                'content',
                            ]
                        ])
                    </div>

                </div>

                @include('admin.default.posts.box', [
                    'loadBoxes' => [
                        'product',
                        'preview',
                        'attributes',
                        'plan',
                        'textMetas',
                        'contentMetas',
                        'location'
                    ]
                ])

                @foreach($projectBoxes as $key => $projectBox)
                    @if(isset($boxes[$key]))
                        @if(in_array($key, $postBoxes))
                            @if(isset($projectBox['view']))
                               @include($projectBox['view'], [
                                   'key' => $key,
                                   'box' => $boxes[$key]
                               ])
                            @else
                                @include("boxes.default.{$boxes[$key]['box']}", [
                                   'key' => $key,
                                   'box' => $boxes[$key]
                               ])
                            @endif
                        @endif
                    @endif
                @endforeach

            </div>

            <div class="col-md-4">

                <div class="box box-success">

                    <div class="box-header">
                        <h3 class="box-title">انتشار</h3>
                        <div class="box-tools">
                            <i class="box-tools-icon icon-minus"></i>
                        </div>
                    </div>

                    <div class="box-body">

                        @php
                            $status  = old('status') ?? $post->status;
                            $display = old('display') ?? $post->display;
                        @endphp

                        <div class="input-group">
                            <i class="icon-playlist_add_check"></i>
                            <label>وضعیت انتشار</label>
                            @if($user->can('finalStatus'))
                                @php
                                $finalStatus = 'draft';
                                if (!empty($post->final_status))
                                    $finalStatus = $post->final_status;

                                $st = config("statusConfig.{$finalStatus}");
                                @endphp
                                <small style="color: {{ $st['color'] }};">(وضعیت تایید: {{ $st['title'] }})</small>
                            @endif

                            <select name="status" id="status" class="select-dropdown">
                                @foreach ($statuses as $key => $value)
                                    <option {{ ($status == $key ? 'selected' : '') }} dropdown="{{ isset($value['dropdown']) ? $value['dropdown'] : '' }}" value="{{ $key }}">{{ $value['title'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="publishTime" class="status {{ $status == 'publishTime' ? '' : 'dn' }} input-group">
                            <i class="icon-timer"></i>
                            <label>زمان انتشار</label>
                            <input readonly value="{{ $post->published_at }}" class="persianDateTime ltr" name="publishTime" type="text">
                        </div>

                        <div id="needChange" class="status {{ $status == 'needChange' || $post->verify_status == 'needChange' ? '' : 'dn' }} input-group">
                            <i class="icon-warning2"></i>
                            <label>تغییرات</label>
                            <textarea name="needChange">{{ $post->needChange() }}</textarea>
                        </div>

                        @include('admin.default.posts.box', [
                            'loadBoxes' => [
                                'tags',
                                'survey',
                                'mobiles',
                                'phones',
                                'aparat'
                            ]
                        ])

                    </div>

                    <div class="box-footer tal">
                        @if(!empty($post->parent))
                            <a target="_blank" class="btn-lg btn-info" href="{{ url("admin/posts/{$post->parent}/edit") }}">نسخه اصلی</a>
                        @endif
                        <button type="submit" class="btn-lg btn-success">ذخیره</button>
                    </div>

                </div>


                @include('admin.default.posts.box', [
                    'loadBoxes' => [
                        'telegram',
                        'categories',
                        'course',
                        'thumbnail',
                        'gallery',
                        'sounds'
                    ]
                ])

            </div>

        </div>

    </form>

@endsection

@section('footer-content')
    <style>
        .datepicker-plot-area .datepicker-time-view .divider {
            margin: 0;
        }
        .datepicker-plot-area .datepicker-time-view .divider span {
            height: 43px;
            line-height: 43px;
            border: none;
        }
        .datepicker-plot-area .datepicker-time-view .divider span:after {
            border: none;
        }
    </style>
    <script>

        $("#post").keypress(function(e) {
            if (e.which == 13) {
                if(e.target.tagName.toLowerCase() != 'textarea') {
                    return false;
                }
            }
        });

        $(document).on('change', 'select[name=status]', function () {
            $('.status').slideUp();
            $('#'+$(this).val()).slideDown();
        });

        $(document).ready(function() {

            $('input[name="publishTime"]').val('{{ $post->publishTime() }}');

            $('.persianDateTime').persianDatepicker({
                initialValue: false,
                format: "YYYY-MM-DD HH-mm-ss",
                minDate: new persianDate(),
                timePicker: {
                    enabled: true,
                    meridiem: {
                        enabled: true
                    }
                },
                formatter: function(unix){
                    persianDate.toLocale('en');
                    return new persianDate(unix).format("YYYY/MM/DD HH:mm:ss");
                }
            });

        });

    </script>

@endsection
