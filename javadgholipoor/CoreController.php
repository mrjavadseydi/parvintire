<?php

namespace LaraBase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

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

    public function apiSecurity($request, $permission)
    {
        if ($request->has('user'))
            $user = $request->user;
        else
            $user = auth()->user();

        if (!$user->can($permission))
            abort(401);
    }

}
