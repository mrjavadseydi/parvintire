<?php

namespace LaraBase\Auth\Controllers;

use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use LaraBase\Auth\Models\User;
use LaraBase\Auth\Models\UserVerification;
use Mail;

class VerifyController extends AuthCore {

    public function index() {

        if (!isset($_GET['token']))
            return redirect('login');

        $token = $_GET['token'];
        $verification = UserVerification::where('token', $token)->first();

        if ($verification == null)
            return redirect('login');

        $type = $verification->type;
        $user = User::where('id', $verification->user_id)->first();

        if ($type == 'email') {

            if (isset($_GET['rememberToken']))
                return $this->verifyEmail($user, $verification);

            $title = 'فعال سازی';
            $url   = route('verify') . "?token={$token}&rememberToken={$user->remember_token}";
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

        return authView('verify', compact('type','user', 'verification'));

    }

    public function verifyEmail($user, $verification)
    {

        if ($user->email_verified_at == null) {
            if ($_GET['rememberToken'] == $user->remember_token) {
                User::where('id', $user->id)->update([
                    'remember_token'    => null,
                    'email_verified_at' => Carbon::now()->toDateTimeString()
                ]);
                $verification->delete();
                $user->login();
                $user->log(true, true);
                return $this->redirect();
            }
        }

        return redirect('login');

    }

    public function verifyMobile(Request $request)
    {

        $this->validateGoogleReCaptchaV3($request);

        $request->validate([
            'code'  => 'required',
        ]);

        $code  = $request->code;
        $token = $request->token;

        $verification = UserVerification::where(['token' => $request->token])->first();

        if ($verification == null)
            return redirect('login');

        $user = User::where('id', $verification->user_id)->first();
        $userVerification = UserVerification::where(['user_id' => $user->id, 'type' => 'mobileCode'])->first();

        if ($code == $userVerification->token) {

            $user->update(['mobile_verified_at' => Carbon::now()->toDateTimeString()]);
            $verification->delete();
            $userVerification->delete();

            $user->login();
            $user->log(true, true);
            return $this->redirect();

        } else {
            return redirect()->back()->with('error', 'کد تایید وارد شده اشتباه است.')->with('token', $token)->with('mobile', $request->mobile);
        }

    }

    public function redirect() {
        if (hasAuthReferer()) {
            return redirect(url(getAuthReferer()));
        }
        return redirect('/');
    }

}
