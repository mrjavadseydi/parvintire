<?php

namespace LaraBase\Helpers;

use Illuminate\Support\Facades\Validator;

class Validation
{
    /**
     * Email validation
     *
     * @param string $email
     * @return bool
     */
    public static function email($email)
    {
        if (preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i', $email))
            return true;

        return false;
    }

    /**
     * Mobile validation
     *
     * @param int|string $number
     * @return bool
     */
    public static function mobile($number)
    {
        if (preg_match('/^(?:09|\+?63)(?:\d(:?-)?){9,10}$/', $number))
            return true;

        return false;
    }

    /**
     * National code validation
     * @param $nationalCode
     * @return bool
     */
    public static function nationalCode($nationalCode)
    {
        if (!preg_match('/^[0-9]{10}$/', $nationalCode))
            return false;

        for ($i = 0; $i < 10; $i++) {
            if (preg_match('/^' . $i . '{10}$/', $nationalCode))
                return false;
        }

        for ($i = 0, $sum = 0; $i < 9; $i++) {
            $sum += ((10 - $i) * intval(substr($nationalCode, $i, 1)));
        }

        $ret = $sum % 11;
        $parity = intval(substr($nationalCode, 9, 1));
        if (($ret < 2 && $ret == $parity) || ($ret >= 2 && $ret == 11 - $parity)) {
            return true;
        }
        return false;
    }

    public static function validate($request, $rules, $messages = []) {

        if( $request->is('api/*')) {
            $output['status'] = 'success';
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                $output['status'] = 'error';
                $output['message'] = $validator->getMessageBag()->first();
            }
            return $output;
        }

        if ($request->ajax()) {
            $output['status'] = 'success';
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                $output['status'] = 'error';
                $output['message'] = $validator->getMessageBag()->first();
            }
            return $output;
        } else {
            return $request->validate($rules, $messages);
        }

    }

}
