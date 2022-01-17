<?php

namespace LaraBase\Auth\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use LaraBase\Auth\Models\User;
use LaraBase\Auth\Models\UserVerification;
use Mail;

class PasswordController extends AuthCore {

    public function __construct(Request $request) {

        $this->middleware('guest');
        parent::__construct($request);

    }

    public function index() {
        $configs = $this->recoveryConfigs;
        return authView('recovery', compact('configs'));
    }

    public function recovery(Request $request) {
        $this->recoveryValidator($request);

        $type = $this->getType();
        $userLogin = $request->userLogin;
        $user = User::where($type, $userLogin)->first();

        $recoveryType = "{$type}Recovery";
        $token = generateUniqueToken();
        if ($user->verifications()->where('type', $recoveryType)->exists()) {
            $user->verifications()->where('type', $recoveryType)->update(['token' => $token]);
        } else {
            $user->verifications()->create([
                'type'      => $recoveryType,
                'token'     => $token
            ]);
        }

        return redirect(route('passwordSend') . "?token={$token}");

    }

    public function recoveryValidator($request) {

        if ($this->isActiveRecovery()) {

            $this->validateGoogleReCaptchaV3($request);

            $setRulesMessages = false;

            $userLogin = $request->userLogin;

            if (checkMail($userLogin)) {

                if (!$this->isActiveRecoveryEmail()) {
                    $rules['userLogin']  = 'false';
                    $messages = [
                        'userLogin.false' => 'در حال حاضر امکان بازیابی رمزعبور از طریق ایمیل غیرفعال می باشد.',
                    ];
                    $setRulesMessages = true;
                }

            } else if (checkMobile($userLogin)) {

                if (!$this->isActiveRecoveryMobile()) {
                    $rules['userLogin']  = 'false';
                    $messages = [
                        'userLogin.false' => 'در حال حاضر امکان بازیابی رمزعبور از طریق موبایل غیرفعال می باشد.',
                    ];
                    $setRulesMessages = true;
                }

            }

            if (!$setRulesMessages) {

                $rules = [
                    'userLogin' => 'required|exists:users,' . $this->getType()
                ];

                $messages = [
                    'userLogin.required'    => 'لطفا ایمیل یا موبایل خود را وارد کنید.',
                    'userLogin.exists'      => 'ایمیل یا موبایل وارد شده در سیستم یافت نشد.',
                ];

            }

        } else {

            $rules['userLogin']  = 'false';
            $messages = [
                'userLogin.false' => 'در حال حاضر امکان بازیابی رمز عبور وجود ندارد.',
            ];

        }

        return $this->validate($request, $rules, $messages);

    }

    public function send() {

        if (!isset($_GET['token']))
            return redirect('login');

        $token = $_GET['token'];
        $verification = UserVerification::where('token', $token)->first();

        if ($verification == null) {
            return redirect('login');
        }

        $type = $verification->type;
        $user = User::where('id', $verification->user_id)->first();

        $rememberToken = sha1(generateUniqueToken());
        $user->update(['remember_token' => $rememberToken]);

        if ($type == 'emailRecovery') {

            $title = 'تغییر رمز عبور';
            $url   = route('passwordChange', ['rememberToken' => $rememberToken]) . "?token={$token}";
            $html  = emailView('url', [
                'url'   => $url,
                'title' => $title
            ]);

            Mail::html($html, function ($message) use ($user, $title) {
                $message->to($user->email)->cc($user->email)->subject($title);
            });

        } else {

            $sendSms = false;
            $code = generateInt(5);
            $userVerification = UserVerification::where(['user_id' => $user->id, 'type' => 'mobileCode'])->first();
            if ($userVerification == null) {
                UserVerification::create([
                    'type'    => 'mobileCode',
                    'user_id' => $user->id,
                    'token'   => $code
                ]);
                $sendSms = true;
            } else {

                $now = Carbon::now()->timestamp;
                $sendSmsTime = config('authConfig.sendSmsTime') * 60;
                $timestamp = strtotime($userVerification->updated_at) + $sendSmsTime;
                if ($now >= $timestamp) {
                    $userVerification->update(['token' => $code]);
                    $sendSms = true;
                }

            }

            if ($sendSms) {
                sms()->text('')->numbers([$user->mobile])->sendPattern('verificationCode', [
                    'code' => $code
                ]);
            }

        }

        return authView('recovery.send', compact('type','user', 'token'));

    }

    public function mobile(Request $request) {
        // confirmation mobile by code and redirect to change password form

        $this->validateGoogleReCaptchaV3($request);

        $request->validate([
            'code'  => 'required',
        ]);

        $code  = $request->code;
        $token = $request->token;

        $verification = UserVerification::where(['token' => $request->token])->first();

        if ($verification == null)
            return $this->redirect();

        $user = User::where('id', $verification->user_id)->first();
        $userVerification = UserVerification::where(['user_id' => $user->id, 'type' => 'mobileCode'])->first();

        if ($code == $userVerification->token) {
            return redirect(route('passwordChange', ['rememberToken' => $user->remember_token]) . "?token={$token}");
        } else {
            return redirect()->back()->with('error', 'کد تایید وارد شده اشتباه است.')->with('token', $token)->with('mobile', $request->mobile);
        }

    }

    public function change($rememberToken) {

        if (!isset($_GET['token']))
            return redirect('login');

        $token = $_GET['token'];
        $verification = UserVerification::where('token', $token)->first();

        if ($verification == null) {
            return redirect('login');
        }

        $type = $verification->type;
        $user = User::where('id', $verification->user_id)->first();

        if ($user->remember_token != $rememberToken) {
            return redirect(route('passwordRecovery'));
        }

        return authView('recovery.change', compact('rememberToken'));

    }

    public function update(Request $request, $rememberToken) {

        $this->updateValidator($request);

        if (!isset($_GET['token']))
            return $this->redirect();

        $token = $_GET['token'];
        $verification = UserVerification::where('token', $token)->first();

        if ($verification == null)
            return $this->redirect();

        $user = User::where('remember_token', $rememberToken)->first();

        if ($user->id != $verification->user_id)
            return $this->redirect();

        $user->update([
            'remember_token' => null,
            'password' => Hash::make($request->password)
        ]);

        $user->verifications()->where('token', $token)->delete();

        auth()->loginUsingId($user->id);

        if (hasAuthReferer()) {
            return redirect(url(getAuthReferer()));
        }

        return redirect(url(''));

    }

    public function updateValidator($request) {
        $passwordLength = config('authConfig.passwordLength');
        return $this->validate($request, [
            'password' => ['required', "min:{$passwordLength}", 'confirmed']
        ]);
    }

    public function redirect() {
        return redirect(route('recoveryPassword'));
    }

}
