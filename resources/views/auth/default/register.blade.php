@extends("auth.{$authTheme}.master")
@section('title', 'ثبت نام')

@section('content')

    <div class="text-center">

        <form class="auth-form" action="{{ route('register') }}" method="post">

            @csrf

            {!! googleReCaptchaV3() !!}

            @include("auth.{$authTheme}.logo")

            @if($errors->any())
                <div class="alert alert-danger">
                    <h4>
                        <i class="icon-block"></i>
                        <span>خطا</span>
                    </h4>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="input-group">
                <label for="">موبایل / ایمیل</label>
                <input class="input-ltr" type="text" name="userLogin" value="{{ old('userLogin') }}">
            </div>

            <div class="input-group mt-2">
                <label for="">رمز عبور</label>
                <input class="input-ltr" type="password" name="password" value="">
            </div>

            <div class="input-group mt-2">
                <label for="">تکرار رمز عبور</label>
                <input class="input-ltr" type="password" name="password_confirmation" value="">
            </div>

            <div class="mt-4">
                <button disabled recaptcha="ثبت نام" class="btn-green reCaptchaBtn">لطفا صبر کنید...</button>
            </div>

            <div class="row mt-2">
                <div class="col-md-6 pl-md-1 mb-2">
                    <a href="{{ route('googleSign') }}" class="btn-red">ثبت نام با گوگل</a>
                </div>
                <div class="col-md-6 pr-md-1">
                    <a href="{{ route('login') }}" class="btn-orange">ورود</a>
                </div>
            </div>

        </form>

    </div>

@endsection
