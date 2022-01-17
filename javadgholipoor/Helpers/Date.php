<?php
namespace LaraBase\Helpers;

class Date
{
    
    public static function jalaliMonth() {
        return [
            '1'  => 'فروردین',
            '2'  => 'اردیبهشت',
            '3'  => 'خرداد',
            '4'  => 'تیر',
            '5'  => 'مرداد',
            '6'  => 'شهریور',
            '7'  => 'مهر',
            '8'  => 'آبان',
            '9'  => 'آذر',
            '10' => 'دی',
            '11' => 'بهمن',
            '12' => 'اسفند',
        ];
    }
    
    public static function repaire($date, $delmiter = '-') {
        $dateParts = explode($delmiter, $date);
    
        $rePairDate = [];
        foreach ($dateParts as $item) {
            if (strlen($item) < 2) {
                $rePairDate[] .= '0' . $item;
            } else {
                $rePairDate[] .= $item;
            }
        }
    
        return implode($delmiter, $rePairDate);
    }

}
