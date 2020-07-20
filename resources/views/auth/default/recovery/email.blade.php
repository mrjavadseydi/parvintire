<div class="text-center">

    <div class="auth-form full">

        @csrf

        {!! googleReCaptchaV3() !!}

        @include("auth.{$authTheme}.logo")

        <div class="alert alert-success">
            <h4>
                <i class="icon-block"></i>
                <span>بازیابی رمز عبور با موفقیت انجام شد</span>
            </h4>
            <ul>
                <li>ما یک ایمیل حاوی لینک تغییر رمزعبور برای شما ارسال کردیم</li>
                <li>برای تغییر رمزعبور اکانت خود در سایت بر روی دکمه <b style="color: deeppink;">تغییر رمزعبور</b> در ایمیل ارسال شده کلیک کنید</li>
                <li>در صورتی که ایمیلی دریافت نکرده اید لطفا پوشه هرزنامه (spam) خود را چک کنید</li>
            </ul>
        </div>

        @if(isset($_GET['resend']))
            <div class="alert alert-success">
                <i class="icon-check"></i>
                ایمیل ارسال شد
            </div>
        @endif

        <div class="row mt-2">
            <div class="col-md-4 pl-1">
                <a href="{{ route('passwordSend') }}?token={{ $_GET['token'] }}&resend" class="btn-orange">ارسال مجدد ایمیل</a>
            </div>
            <div class="col-md-8 pr-1">
                <a href="{{ route('login') }}" class="btn-green">برای ورود به سایت کلیک کنید</a>
            </div>
        </div>


    </div>

</div>
