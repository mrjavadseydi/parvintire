<?php

namespace LaraBase\Auth\Controllers;

use Carbon\Carbon;
use Hash;
use Illuminate\Http\Request;
use LaraBase\Auth\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Mail;

class GoogleController extends AuthCore {

    public function __construct(Request $request) {

        $this->middleware('guest');
        parent::__construct($request);

    }

    /**
     * Redirect the user to the Google authentication page.
     *
     * @return Response
     */
    public function sign() {
        if ($this->isActiveGoogle())
            return Socialite::driver('google')->redirect();

        return redirect()->back()->with('error', 'در حال حاضر امکان ورود و ثبت نام از طریق گوگل وجود ندارد.');
    }

    /**
     * Obtain the user information from Google.
     *
     * @return Response
     */
    public function callback()
    {

        if ($this->isActiveGoogle()) {

            $google = Socialite::driver('google')->user();
            $googleUser = $google->user;

            if ($googleUser['email_verified']) {
                $email = $googleUser['email'];

                $user = User::where('email', $email)->first();

                $register = false;
                if ($user == null) {
                    $password = generateInt(config('authConfig.passwordLength'));
                    $user = User::create([
                        'email'             => $email,
                        'password'          => Hash::make($password),
                        'email_verified_at' => Carbon::now()->toDateTimeString(),
                        'name'              => $googleUser['given_name'],
                        'family'            => $googleUser['family_name'],
                    ]);

                    $register = true;

                    $title = 'ثبت نام شما با موفقیت انجام شد';
                    $html = emailView('content', [
                        'title'       => $title,
                        'description' => 'اطلاعات حساب شما به شرح زیر می باشد.',
                        'parameters'  => [
                            'email'    => $user->email,
                            'password' => $password
                        ]
                    ]);

                    Mail::html($html, function ($message) use ($user, $title) {
                        $message->to($user->email)->cc($user->email)->subject($title);
                    });

                }

                $user->login();
                $user->log($register);

                return redirect('/');
            } else {
                return redirect('login');
            }

        }

    }


}
