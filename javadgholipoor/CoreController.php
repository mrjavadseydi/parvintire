<?php

namespace LaraBase;

use App\Http\Controllers\Controller;

class CoreController extends Controller {

    public function validateGoogleReCaptchaV3($request) {
        if ($this->isActiveGoogleReCaptchaV3()) {
            if (!env('APP_DEBUG')) {
                return $this->validate($request, [
                    'g-recaptcha-response' => 'required|captcha'
                ]);
            }
        }
    }

    public function apiSecurity($permission)
    {
        // TODO دریافت توکن و دریافت یوزر و بررسی دسترسی
        can($permission);
    }

}
