<?php

namespace LaraBase\Store\Actions;

use LaraBase\Store\Models\ProductAttribute;

trait Product {

    public function attributes() {

    }

    public function price() {
        if ($this->discount() > 0)
            return convertPrice($this->special_price);

        return convertPrice($this->price);
    }

    public function discount() {
        if ($this->start_date != null && $this->end_date != null) {
            $from = strtotime($this->start_date);
            $to = strtotime($this->end_date);
            $now = strtotime('now');
            if ($now >= $from && $now <= $to)
                return convertPrice($this->price - $this->special_price);
        }
        return 0;
    }

}
