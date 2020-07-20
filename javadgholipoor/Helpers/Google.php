<?php

namespace LaraBase\Helpers;

class Google {

    public static function googleReCaptchaV3() {

//        $secretKey = getOption('googleReCaptchaV3SecretKey');
//        $siteKey = getOption('googleReCaptchaV3SiteKey');
        $siteKey = env('NOCAPTCHA_SITEKEY');

        if (env('APP_DEBUG')) {
            return '
                <script>
                    $(document).ready(function () {
                        var text = $(".reCaptchaBtn").attr("recaptcha");
                        $(".reCaptchaBtn").text(text);
                        $(".reCaptchaBtn").removeAttr("disabled");
                    });
                </script>
            ';
        } else {
            return '
                <input type="hidden" name="g-recaptcha-response" value="">
                <script src="https://www.google.com/recaptcha/api.js?render='.$siteKey.'&hl=fa"></script>
                <script>
                    grecaptcha.ready(function() {
                        grecaptcha.execute(\''.$siteKey.'\', {action: \'homepage\'}).then(
                            function(token) {
                                $(\'input[name="g-recaptcha-response"]\').val(token);
                                $(\'.reCaptchaBtn\').text($(\'.reCaptchaBtn\').attr(\'recaptcha\'));
                                $(\'.reCaptchaBtn\').removeAttr(\'disabled\');
                            });
                    });
                </script>
            ';
        }
    }

    public static function googleAnalytics() {
        echo getOption('googleAnalytics');
    }

}
