<?php


namespace LaraBase\Payment;


class CoreGateway {
    
    public $messages = [];
    
    protected function hiddenInput( $name, $value ){
        return '<input type="hidden" name="' . $name . '" value="' . $value . '"/>' . PHP_EOL;
    }
    
    protected function message($code) {
        if (isset($this->messages[$code]))
            return $this->messages[$code];
        
        return 'کد خطا در لیست خطاها یافت نشد';
    }
    
}
