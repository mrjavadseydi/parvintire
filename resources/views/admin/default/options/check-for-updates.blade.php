@extends("admin.{$adminTheme}.master")
@section('title', 'بروزرسانی ها')

@section('content')

    <div class="col-12">

        @if($success)

            <div class="update-item">
                <span>پروژه</span>
                @if($updateApp)
                    <a href="{{ route('larabase.update.install') }}">بروزرسانی</a>
                @else
                    <a class="up-to-date">نسخه نهایی</a>
                @endif
            </div>

            <div class="update-item">
                <span>قالب ها</span>
                @if($updateThemes)
                    <a href="{{ route('larabase.update.themes') }}">بروزرسانی</a>
                @else
                    <a class="up-to-date">نسخه نهایی</a>
                @endif
            </div>

        @else

            <div class="alert alert-info">
                <p>در حال حاضر دسترسی به سرور بروزرسانی وجود ندارد</p>
            </div>

        @endif

    </div>

    <style>
        .update-item {
            background: white;
            display: inline-block;
            padding: 5px;
            border-radius: 3px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .update-item span {
            color: #464646;
        }

        .update-item a {
            display: block;
            color: white;
            background: #3fab24;
            padding: 1px 10px;
            border-radius: 4px;
            margin-top: 15px;
        }

        .update-item .up-to-date {
            background: #a3a3a3;
        }

    </style>

@endsection
