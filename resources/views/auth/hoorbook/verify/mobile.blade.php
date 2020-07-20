<div class="text-center">

    <form class="auth-form" action="{{ route('verifyMobile') }}" method="post">

        @csrf
        {!! googleReCaptchaV3() !!}

        @include('auth.hoorbook.logo')

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

        <input type="hidden" name="token" value="{{ $verification->token }}">

        <div class="alert alert-success">
            <p>کد فعال سازی به شماره <b>{{ $user->mobile }}</b> ارسال شد</p>
        </div>

        <div class="input-group">
            <label for="">کد فعال سازی</label>
            <input class="input-ltr" type="text" name="code" value="">
        </div>

        <div class="mt-4">
            <button disabled recaptcha="فعال سازی" class="btn-green reCaptchaBtn">لطفا صبر کنید...</button>
        </div>

        <div class="row mt-2">
            <div class="col-md-6 pl-md-1 mb-2">
                <a href="{{ route('login') }}" class="btn-orange">ورود به سایت</a>
            </div>
            <div class="col-md-6 pr-md-1">
                <a href="{{ route('register') }}" class="btn-red">ثبت نام در سایت</a>
            </div>
        </div>

    </form>

</div>
