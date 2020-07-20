<div class="text-center">

    <div class="auth-form full">

        @csrf

        {!! googleReCaptchaV3() !!}

        @include("auth.{$authTheme}.logo")

        <div class="alert alert-success">
            <h4>
                <i class="icon-block"></i>
                <span>ثبت نام با موفقیت انجام شد</span>
            </h4>
            <ul>
                <li>ما یک ایمیل حاوی لینک فعال سازی برای شما ارسال کردیم</li>
                <li>برای فعال سازی اکانت خود در سایت بر روی دکمه <b style="color: deeppink;">فعال سازی</b> در ایمیل ارسال شده کلیک کنید</li>
                <li>در صورتی که ایمیلی دریافت نکرده اید لطفا پوشه هرزنامه (spam) خود را چک کنید</li>
            </ul>
        </div>

        @if(isset($_GET['resend']))
            <div class="alert alert-success">
                <i class="icon-check"></i>
                ایمیل فعال سازی ارسال شد
            </div>
        @endif

        <div class="row mt-2">
            <div class="col-md-4 pl-1">
                <a href="{{ route('verify') }}?token={{ $_GET['token'] }}&resend" class="btn-orange">ارسال مجدد ایمیل</a>
            </div>
            <div class="col-md-8 pr-1">
                <a href="{{ route('login') }}" class="btn-green">برای ورود به سایت کلیک کنید</a>
            </div>
        </div>


    </div>

</div>
