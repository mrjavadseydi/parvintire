<?php

namespace LaraBase\Auth\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use LaraBase\Auth\Models\User;
use LaraBase\Auth\Models\UserVerification;

class AuthController extends AuthCore
{

    private $blockKey = null;
    private $isBlockKey = null;
    private $blockCounter = 5;
    private $passwordLength = 8;
    private $emailMaxLength = 191;
    private $sendSmsTime = 5;

    public function login(Request $request)
    {

        $request->validate([
            'userLogin' => "required",
            'password' => "required"
        ]);

        $userLogin = toEnglish($request->userLogin);
        $password = toEnglish($request->password);

        $this->setBlockKey($userLogin);

        if (checkMail($userLogin))
            $type = 'email';
        else if (checkMobile($userLogin))
            $type = 'mobile';
        else
            $type = 'username';

        $user = User::where($type, $userLogin)->first();

        $output = $this->isBlock();

        if ($output['status'] == 'success') {

            if ($user == null) {
                $this->addBlock();
                return [
                    'status' => 'error',
                    'message' => 'نام کاربری یا رمز عبور اشتباه است',
                    'userLogin' => $userLogin,
                    'password' => $password
                ];
            }

            $verification = UserVerification::where(['type' => $type, 'user_id' => $user->id])->first();

            if ($verification != null) {
                $this->addBlock();
                return [
                    'status' => 'error',
                    'message' => 'نام کاربری یا رمز عبور اشتباه است',
                    'userLogin' => $userLogin,
                    'password' => $password
                ];
            }

            if (Hash::check($password, $user->password)) {
                $user->login();
                $user->log();
                $output['user'] = $user;
                $output['token'] = $user->getApiToken();
            } else {
                $this->addBlock();
                return [
                    'status' => 'error',
                    'message' => 'نام کاربری یا رمز عبور اشتباه است',
                    'userLogin' => $userLogin,
                    'password' => $password
                ];
            }

        }

        return response()->json($output);

    }

    public function register(Request $request)
    {

        $request->validate(['userLogin' => "required"], ['userLogin.required'    => 'لطفا ایمیل یا موبایل خود را وارد کنید']);

        $userLogin = toEnglish($request->userLogin);
        $password = toEnglish($request->password);
        $this->setBlockKey($userLogin);

        if (is_numeric($userLogin))
            $type = 'mobile';
        else
            $type = 'email';

        $user = User::where($type, $userLogin)->first();

        $validation = "{$type}Validation";
        $this->$validation($request, $user);

        if ($user != null) {
            $verification = UserVerification::where(['type' => $type, 'user_id' => $user->id])->first();
            $user->update(['password' => Hash::make($request->password)]);
        } else {
            $data = [
                $type => $userLogin,
                'password' => Hash::make($password),
                'remember_token' => generateToken(100)
            ];
            if ($request->has('invite_code')) {
                $data['invite_code'] = $request->invite_code;
            }
            $user = User::create($data);
            $verification = $user->verifications()->create([
                'type'      => $type,
                'user_id'   => $user->id,
                'token'     => generateUniqueToken(),
            ]);
            $user->log(true);
        }

        $sendVerify = "{$type}SendVerify";
        $this->$sendVerify($user, $verification);

        $message = "ثبت نام با موفقیت انجام شد\n";
        if ($type == 'email') {
            $message .= "لطفا با کلیک بر روی لینک ارسال شده به ایمیل " . $userLogin . " حساب کاربری خود را فعال کنید";
        } else {
            $message .= "لطفا کد تایید ارسال شده به شماره " . $userLogin . " را وارد کنید";
            $output['verify'] = url("api/v1/auth/verify/{$verification->token}");
        }

        $output = array_merge([
            'status' => 'success',
            'message' => $message,
            'type' => $type
        ], $output ?? []);

        return response()->json($output);

    }

    public function verify($token, Request $request)
    {
        $request->validate(['code' => 'required']);
        $code = toEnglish($request->code);
        $verification = UserVerification::where('token', $token)->first();
        $user = User::find($verification->user_id);
        $mobileVerification = UserVerification::where(['type' => 'mobileCode', 'user_id' => $user->id])->first();
        if ($mobileVerification != null) {
            if ($mobileVerification->token == $code) {
                $user->update(['mobile_verified_at' => Carbon::now()->toDateTimeString()]);
                $verification->delete();
                $mobileVerification->delete();
                $user->log(true, true);
                return [
                    'status' => 'success',
                    'user' => $user,
                    'token' => $user->getApiToken()
                ];
            }
        }
        return [
            'status' => 'error',
            'message' => 'کد تایید اشتباه می باشد'
        ];
    }

    public function emailValidation($request, $user)
    {
        $uniqueId = '';
        if ($user != null) {
            if (empty($user->email_verified_at)) {
                $uniqueId = ',' . $user->id;
            }
        }
        $request->validate([
            'userLogin' => "email|max:{$this->emailMaxLength}|unique:users,email" . $uniqueId,
            'password' => "required|confirmed|min:{$this->passwordLength}"
        ], [
            'userLogin.required'    => 'لطفا ایمیل یا موبایل خود را وارد کنید',
            'userLogin.max'         => "ایمیل نمیتواند بیشتر از {$this->emailMaxLength} کاراکتر باشد",
            'userLogin.unique'      => 'ایمیل قبلا در سیستم ثبت شده است',
        ]);
    }

    public function mobileValidation($request, $user)
    {
        $uniqueId = '';
        if ($user != null) {
            if (empty($user->mobile_verified_at)) {
                $uniqueId = ',' . $user->id;
            }
        }
        $request->validate([
            'userLogin' => 'required|mobile|size:11|unique:users,mobile' . $uniqueId,
            'password' => "required|confirmed|min:{$this->passwordLength}",
        ], [
            'userLogin.required'    => 'لطفا ایمیل یا موبایل خود را وارد کنید',
            'userLogin.size'        => 'طول موبایل 11 رقم می باشد',
            'userLogin.unique'      => 'موبایل قبلا در سیستم ثبت شده است',
        ]);
    }

    public function setBlockKey($userLogin) {
        $ip = ip();
        $this->blockKey = "block{$ip}{$userLogin}";
        $this->isBlockKey = "block{$ip}{$userLogin}IsBlock";
    }

    public function addBlock()
    {
        $counter = 0;
        if (hasCache($this->blockKey)) {
            $counter = getCache($this->blockKey) + 1;
        }
        setCache($this->blockKey, $counter, 4320);
        if ($counter == $this->blockCounter) {
            setCache($this->isBlockKey, strtotime('now') + 300, 5);
        } else if ($counter == ($this->blockCounter*2)) {
            setCache($this->isBlockKey, strtotime('now') + 900, 15);
        } else if ($counter == ($this->blockCounter*3)) {
            setCache($this->isBlockKey, strtotime('now') + 3600, 60);
        } else if ($counter == ($this->blockCounter*4)) {
            setCache($this->isBlockKey, strtotime('now') + 86400, 1440);
        } else if ($counter == ($this->blockCounter*5)) {
            setCache($this->isBlockKey, strtotime('now') + 259200, 4320);
        }
    }

    public function isBlock()
    {
        if (hasCache($this->isBlockKey)) {
            $timestamp = getCache($this->isBlockKey);
            $diff = $timestamp - strtotime('now');
            if ($diff < 60) {
                $duration = $diff . " ثانیه";
            } else if ($diff < 3600) {
                $duration = (round($diff / 60)) . " دقیقه";
            } else if ($diff < 86400) {
                $duration = (round($diff / 3600)) . " ساعت";
            } else if ($diff > 86400) {
                $duration = (round($diff / 86400)) . " روز";
            }
            return [
                'status' => 'error',
                'message' => "شما به مدت {$duration} مسدود شده اید."
            ];
        }
        return [
            'status' => 'success'
        ];
    }

    public function emailSendVerify($user, $verification)
    {
        $title = 'فعال سازی';
        $url   = route('verify') . "?token={$verification->token}&rememberToken={$user->remember_token}";
        $html  = emailView('url', [
            'url'   => $url,
            'title' => $title
        ]);
        Mail::html($html, function ($message) use ($user, $title) {
            $message->to($user->email)->cc($user->email)->subject($title);
        });
    }

    public function mobileSendVerify($user, $verification)
    {
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
            $sendSmsTime = $this->sendSmsTime * 60;
            $timestamp = strtotime($userVerification->updated_at) + $sendSmsTime;
            if ($now >= $timestamp) {
                $userVerification->update(['token' => $code]);
                $sendSms = true;
            }
        }
        if ($sendSms) {
            $patternValues = [
                "code" => $code,
            ];
            $bulkID = \IPPanel::sendPattern(
                config('smspatterns.verificationCode'),
                config('smspatterns.sender'),
                $user->mobile,
                $patternValues
            );
        }
    }

}
