<?php

namespace LaraBase\Auth\Actions;

use Auth;
use Carbon\Carbon;
use LaraBase\Auth\Models\UserMeta;
use Laravel\Passport\Passport;

trait User {

    public function login() {
        Auth::login($this);
        $this->updateLoginedAt();
    }

    public function log($register = false, $verify = false) {

        if (isActiveTelegram()) {

            if ($register) {
                $user = (object)$this->toArray();

                if (isset($user->email))
                    $userLogin = $user->email;
                else
                    $userLogin = $user->mobile;

                $now = strtotime('now');
                $date = jDateTime('Y/m/d', $now);
                $time = jDateTime('H:i:s', $now);
                $url = url("admin/users/{$user->id}/edit");
                $ip = ip();

                if ($verify) {
                    if (isset($user->email))
                        $message = "📧 ایمیل {$user->email} در سیستم تایید شد";
                    else
                        $message = "📱 موبایل {$userLogin} در سیستم تایید شد";

                    telegram()->message($message)->tags([
                        'register', "user_{$user->id}", 'approved_user'
                    ])->sendToGroup();
                } else {
                    $message = "ثبت نام <code>{$userLogin}</code> با <a href='".$url."'> شماره کاربری {$user->id}</a> در تاریخ {$date} ساعت {$time} با آی‌پی {$ip} صورت پذیرفت.";

                    telegram()->message($message)->tags([
                        'register', "user_{$user->id}"
                    ])->sendToGroup();
                }

            }

        }

    }

    public function lastSeen() {
        $strToTime = $this->logined_at;
        if ($this->logined_at == null)
            $strToTime = $this->created_at;

        return jDateTime('Y/m/d H:i', strtotime($strToTime));
    }

    public function updateLoginedAt() {
        $this->update(['logined_at' => Carbon::now()->toDateTimeString()]);
    }

    public function name() {

        if (property_exists($this, 'name'))
            if (!empty($this->name))
                return "{$this->name} {$this->family}";

        if (property_exists($this, 'username'))
            if (!empty($this->username))
                return $this->username;

        if (property_exists($this, 'email'))
            if (!empty($this->email))
                return explode('@', $this->email)[0];

        if (property_exists($this, 'mobile'))
            if (!empty($this->mobile))
                return $this->mobile;

        return 'بدون نام';
    }

    public function avatar($returnPath = false) {

        if (!empty($this->avatar)) {
            if ($returnPath)
                return $this->avatar;

            return url($this->avatar);
        }

        return $this->defaultAvatar($returnPath);

    }

    public function defaultAvatar($returnPath = false) {
        return image('default-avatar.png', 'admin', $returnPath);
    }

    public function roleName() {
        return 'مدیر سایت';
    }

    public function email() {
        if (isset($this->email))
            return $this->email;
        return '-';
    }

    public function mobile() {
        if (isset($this->mobile))
            return $this->mobile;
        return '-';
    }

    public function metas()
    {
        return UserMeta::where('user_id', $this->id)->get();
    }

    public function addMeta($key, $value, $more = null)
    {
        UserMeta::create([
            'user_id' => $this->id,
            'key'      => $key,
            'value'    => $value,
            'more'     => $more,
        ]);
    }

    /**
     * @param string $where
     * @param array $where
     * @return bool
     */
    public function hasMeta($where)
    {
        $userId = $this->id;

        if (is_array($where)) {

            // TODO optimize
            $where['user_id'] = $userId;

        } else {

            $cacheKey = "userMeta_{$userId}_$where";

            $where = [
                'key' => $where,
                'user_id' => $userId
            ];

            if (!hasCache($cacheKey)) {
                setCache($cacheKey, UserMeta::where($where)->exists(), 1);
            }

            return getCache($cacheKey);

        }

        return UserMeta::where($where)->exists();

    }

    public function updateMeta($data, $where)
    {
        $where['user_id'] = $this->id;
        UserMeta::where($where)->update($data);
    }

    public function getMeta($key)
    {
        return UserMeta::where(['user_id' => $this->id, 'key' => $key])->first();
    }

}
