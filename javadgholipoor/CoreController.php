<?php

namespace LaraBase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

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

    public function delete(Request $request) {
        $title = $request->title;
        $action = $request->action;
        $referer = $request->referer;
        return adminView('delete', compact('title', 'action', 'referer'));
    }

    public function down() {
        if (isDev()) {
            $ip = ip();
            Artisan::call('down --allow=' . $ip);
            return redirect(url('admin'));
        }
        abort(404);
    }

    public function up() {
        if (isDev()) {
            Artisan::call('up');
            return redirect(url('admin'));
        }
        abort(404);
    }

}
