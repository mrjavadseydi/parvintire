<?php

namespace LaraBase\LaraBase\Controllers;

use Illuminate\Http\Request;

class SmsController
{

    public function sms()
    {
        if (isDev()) {
            $patterns = json_decode(sms()->getPatterns());
            if (empty($patterns->disablePatterns)) {
                die('پترن ها با موفقیت ثبت شده اند');
            }
            return view('larabase.sms-pattern', compact('patterns'));
        }
    }

    public function sync(Request $request)
    {
        if (isDev()) {
            $response = json_decode(sms()->setPatterns($request->patterns));
            if ($response->status == 'success') {
                return redirect()->back();
            } else {
                echo $response->message;
            }
        }
    }

}
