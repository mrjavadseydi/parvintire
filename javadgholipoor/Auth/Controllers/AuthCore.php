<?php

namespace LaraBase\Auth\Controllers;

use Illuminate\Http\Request;
use LaraBase\CoreController;

class AuthCore extends CoreController {

    public $type = null;

    public
        $configs,
        $registerConfigs,
        $loginConfigs,
        $recoveryConfigs;

    public function __construct(Request $request) {
        $this->setType($request);
        $this->configs = config('authConfig');
        $this->registerConfigs = $this->configs['register'];
        $this->loginConfigs = $this->configs['login'];
        $this->recoveryConfigs = $this->configs['recovery'];
    }

    public function setType($request) {
        $type = 'email';
        if (is_numeric($request->userLogin)) {
            $type = 'mobile';
        }
        $this->type = $type;
    }

    public function getType() {
        return $this->type;
    }

    public function isActiveLogin() {
        return $this->loginConfigs['self'];
    }

    public function isActiveLoginUsername() {
        return $this->loginConfigs['username'];
    }

    public function isActiveLoginEmail() {
        return $this->loginConfigs['email'];
    }

    public function isActiveLoginMobile() {
        return $this->loginConfigs['mobile'];
    }

    public function isActiveRegister() {
        return $this->configs['register']['self'];
    }

    public function isActiveRegisterUsername() {
        return $this->configs['register']['username'];
    }

    public function isActiveRegisterEmail() {
        return $this->configs['register']['email'];
    }

    public function isActiveRegisterMobile() {
        return $this->configs['register']['mobile'];
    }

    public function isActiveGoogleReCaptchaV3() {
        return $this->configs['google']['reCaptchaV3']['active'];
    }

    public function sendCodeMessage($code) {
        return siteName() . "\nکد تایید: {$code}";
    }

    public function isActiveGoogle() {
        return $this->configs['google']['sign']['active'];
    }

    public function isActiveRecovery() {
        return $this->recoveryConfigs['self'];
    }

    public function isActiveRecoveryMobile() {
        return $this->recoveryConfigs['mobile'];
    }

    public function isActiveRecoveryEmail() {
        return $this->recoveryConfigs['email'];
    }

}
