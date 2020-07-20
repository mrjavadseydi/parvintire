<?php
namespace LaraBase\Helpers;

class Converter
{
    public static $numbers = [
        'english' => [0 , 1, 2, 3, 4, 5, 6, 7, 8, 9],
        'persian' => ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'],
        'arabic' => ['۰', '۱', '۲', '۳', '٤', '٥', '٦', '۷', '۸', '۹'],
    ];

    /**
     * Convert to english number
     * @param string $string
     * @return string
     */
    public static function toEnglish($string)
    {
        $string = str_replace(self::$numbers['persian'], self::$numbers['english'], $string);
        $string = str_replace(self::$numbers['arabic'], self::$numbers['english'], $string);
        return $string;
    }

    /**
     * Convert to persian number
     * @param string $string
     * @return string
     */
    public static function toPersian($string)
    {
        $string = str_replace(self::$numbers['english'], self::$numbers['persian'], $string);
        $string = str_replace(self::$numbers['arabic'], self::$numbers['persian'], $string);
        return $string;
    }

    /**
     * Convert to arabic number
     * @param string $string
     * @return string
     */
    public static function toArabic($string)
    {
        $string = str_replace(self::$numbers['english'], self::$numbers['arabic'], $string);
        $string = str_replace(self::$numbers['persian'], self::$numbers['arabic'], $string);
        return $string;
    }
    
    public static function distance( $meter, $lang = 'fa' ) {
        
        $units = [
            'en' => ['km', 'm', 'cm'],
            'fa' => ['ک.م', 'متر', 'س.م']
        ];
        
        if ($meter >= 1000) {
            return round(($meter/1000), 1) . ' ' . $units[$lang][0];
        }
        
        if ($meter >= 1 && $meter < 1000) {
            return round($meter, 1) . ' ' . $units[$lang][1];
        }
        
        if ($meter >= 0.99) {
            return round(($meter*100), 1)  . ' ' . $units[$lang][2];
        }
        
    }
    
}
