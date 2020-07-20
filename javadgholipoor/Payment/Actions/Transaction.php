<?php


namespace LaraBase\Payment\Actions;


trait Transaction {

    public function price() {

        $price = $this->price;
        $currency = $this->currency;
        return convertPrice($price);

    }

}
