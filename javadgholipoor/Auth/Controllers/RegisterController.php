<?php

namespace LaraBase\Auth\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use LaraBase\Auth\Models\User;
use LaraBase\Auth\Models\UserVerification;

class RegisterController extends AuthCore {

    public function __construct(Request $request) {

        $this->middleware('guest');
        parent::__construct($request);

    }

    public function index() {
        $configs = $this->registerConfigs;
        return authView('register', compact('configs'));
    }

    public function register(Request $request) {

        $this->validator($request);

        $userLogin = $request->userLogin;
        $user = User::where($this->getType(), $userLogin)->first();

        if ($user != null) {
            $verification = UserVerification::where([
                'type'      => $this->getType(),
                'user_id'   => $user->id
            ])->first();

            if ($verification != null) {
                $user->update(['password' => Hash::make($request->password)]);
                return redirect("verify?token={$verification->token}");
            }
        }

        $user = $this->create($request->all());
        $token = generateUniqueToken();

        $data = [
            'type'      => $this->getType(),
            'user_id'   => $user->id,
            'token'     => $token,
        ];

        if ($request->has('invite_code'))
            $data['invite_code'] = $request->invite_code;

        $user->verifications()->create($data);
        return redirect("verify?token={$token}");

    }

    public function validator($request) {

        if ($this->isActiveRegister()) {

            $this->validateGoogleReCaptchaV3($request);

            $passwordLength = config('authConfig.passwordLength');
            $emailMaxLength = config('authConfig.emailMaxLength');

            if ($this->getType() == 'email') {

                if ($this->isActiveRegisterEmail()) {

                    $rules['password'] = ['required', "min:{$passwordLength}", 'confirmed'];
                    $rules['userLogin'] = ['required', 'email', "max:{$emailMaxLength}", 'unique:users,email'];
                    $messages = [
                        'userLogin.required'    => 'لطفا ایمیل یا موبایل خود را وارد کنید',
                        'userLogin.max'         => "ایمیل نمیتواند بیشتر از {$emailMaxLength} کاراکتر باشد",
                        'userLogin.unique'      => 'ایمیل قبلا در سیستم ثبت شده است',
                    ];

                } else {
                    $rules['userLogin']  = 'false';
                    $messages = [
                        'userLogin.false' => 'درحال حاضر امکان ثبت نام از طریق ایمیل غیر فعال می باشد.',
                    ];
                }

            } else if ($this->getType() == 'mobile') {

                if ($this->isActiveRegisterMobile()) {

                    $rules['password'] = ['required', "min:{$passwordLength}", 'confirmed'];
                    $rules['userLogin']  = ['required', 'mobile', 'size:11', 'unique:users,mobile'];
                    $messages = [
                        'userLogin.required'    => 'لطفا ایمیل یا موبایل خود را وارد کنید',
                        'userLogin.size'        => 'طول موبایل 11 رقم می باشد',
                        'userLogin.unique'      => 'موبایل قبلا در سیستم ثبت شده است',
                    ];

                } else {
                    $rules['userLogin']  = 'false';
                    $messages = [
                        'userLogin.false' => 'درحال حاضر امکان ثبت نام از طریق موبایل غیر فعال می باشد.',
                    ];
                }

            }

        } else {

            $rules['userLogin']  = 'false';
            $messages = [
                'userLogin.false' => 'در حال حاضر امکان ثبت نام وجود ندارد',
            ];

        }

        return $this->validate($request, $rules, $messages);
    }

    public function create($data) {
        $user = User::create([
            $this->getType()    => $data['userLogin'],
            'password'          => Hash::make($data['password']),
            'remember_token'    => generateToken(100)
        ]);
        $user->log(true);
        return $user;
    }

}
