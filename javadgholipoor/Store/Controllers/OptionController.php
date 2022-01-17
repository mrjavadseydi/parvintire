<?php


namespace LaraBase\Store\Controllers;


use LaraBase\Store\Models\Currency;

class OptionController {
    
    public function store() {
    
        $currencies = Currency::all();
        
        return adminView('options.store', compact(
            'currencies'
        ));
    }
    
}
