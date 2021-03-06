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

        $request->request->add(['userLogin' => toEnglish($request->userLogin)]);

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
                        'userLogin.required'    => '???????? ?????????? ???? ???????????? ?????? ???? ???????? ????????',
                        'userLogin.max'         => "?????????? ???????????????? ?????????? ???? {$emailMaxLength} ?????????????? ????????",
                        'userLogin.unique'      => '?????????? ???????? ???? ?????????? ?????? ?????? ??????',
                    ];

                } else {
                    $rules['userLogin']  = 'false';
                    $messages = [
                        'userLogin.false' => '?????????? ???????? ?????????? ?????? ?????? ???? ???????? ?????????? ?????? ???????? ???? ????????.',
                    ];
                }

            } else if ($this->getType() == 'mobile') {

                if ($this->isActiveRegisterMobile()) {

                    $rules['password'] = ['required', "min:{$passwordLength}", 'confirmed'];
                    $rules['userLogin']  = ['required', 'mobile', 'size:11', 'unique:users,mobile'];
                    $messages = [
                        'userLogin.required'    => '???????? ?????????? ???? ???????????? ?????? ???? ???????? ????????',
                        'userLogin.size'        => '?????? ???????????? 11 ?????? ???? ????????',
                        'userLogin.unique'      => '???????????? ???????? ???? ?????????? ?????? ?????? ??????',
                    ];

                } else {
                    $rules['userLogin']  = 'false';
                    $messages = [
                        'userLogin.false' => '?????????? ???????? ?????????? ?????? ?????? ???? ???????? ???????????? ?????? ???????? ???? ????????.',
                    ];
                }

            }

        } else {

            $rules['userLogin']  = 'false';
            $messages = [
                'userLogin.false' => '???? ?????? ???????? ?????????? ?????? ?????? ???????? ??????????',
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
