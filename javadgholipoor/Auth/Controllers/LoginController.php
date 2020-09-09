<?php

namespace LaraBase\Auth\Controllers;

use Hash;
use Illuminate\Http\Request;
use LaraBase\Auth\Models\User;
use LaraBase\Auth\Models\UserVerification;

class LoginController extends AuthCore {

    public function __construct(Request $request) {

        $this->middleware('guest');
        parent::__construct($request);

    }

    public function index() {
        return authView('login');
    }

    public function login(Request $request) {

        $this->validateGoogleReCaptchaV3($request);

        $userLogin = $request->userLogin;
        $password  = $request->password;

        $this->validator($request);

        if (checkMail($userLogin))
            $type = 'email';
        else if (checkMobile($userLogin))
            $type = 'mobile';
        else
            $type = 'username';

        $user = User::where($type, $userLogin)->first();

        if ($user != null) {
            $verification = UserVerification::where([
                'type'      => $type,
                'user_id'   => $user->id
            ])->first();

            if ($verification != null) {
                return redirect("verify?token={$verification->token}");
            }

            if ($user != null) {
                if (Hash::check($password, $user->password)) {
                    $user->login();
                    $user->log();
                    if (hasAuthReferer()) {
                        return redirect(url(getAuthReferer()));
                    }
                    return $this->redirect($user);
                }
            }
        }

        return redirect()->back()->withErrors([
            'userLogin' => 'نام کاربری یا رمزعبور اشتباه است.'
        ]);

    }

    protected function validator($request)
    {

        if ($this->isActiveLogin()) {

            $this->validateGoogleReCaptchaV3($request);

            $setRulesMessages = false;

            $userLogin = $request->userLogin;

            if (checkMail($userLogin)) {

                if (!$this->isActiveLoginEmail()) {
                    $rules['userLogin']  = 'false';
                    $messages = [
                        'userLogin.false' => 'درحال حاضر امکان ورود از طریق ایمیل غیر فعال می باشد.',
                    ];
                    $setRulesMessages = true;
                }

            } else if (checkMobile($userLogin)) {

                if (!$this->isActiveLoginMobile()) {
                    $rules['userLogin']  = 'false';
                    $messages = [
                        'userLogin.false' => 'درحال حاضر امکان ورود از طریق موبایل غیر فعال می باشد.',
                    ];
                    $setRulesMessages = true;
                }

            } else {

                if (!$this->isActiveLoginUsername()) {
                    $rules['userLogin']  = 'false';
                    $messages = [
                        'userLogin.false' => 'درحال حاضر امکان ورود از طریق نام کاربری غیر فعال می باشد.',
                    ];
                    $setRulesMessages = true;
                }

            }

            if (!$setRulesMessages) {

                $rules = [
                    'password'    => 'required',
                    'userLogin'   => 'required'
                ];
                $messages = ['userLogin.required'    => 'لطفا ایمیل یا موبایل خود را وارد کنید.',];

            }

        } else {

            $rules['userLogin']  = 'false';
            $messages = [
                'userLogin.false' => 'در حال حاضر امکان ورود وجود ندارد',
            ];

        }

        return $this->validate($request, $rules, $messages);

    }

    public function redirect($user) {
        if ($user->can('administrator')) {
            return redirect('/admin');
        }
        return redirect('/');
    }

}
