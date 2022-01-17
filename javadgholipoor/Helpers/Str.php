<?php

namespace LaraBase\Helpers;

class Str
{
    /**
     * String slugger
     * @param $sting
     * @param string $separator
     * @return string
     */
    public static function slug($sting, $separator = '-')
    {
        $flip = $separator == '-' ? '_' : '-';
        $sting = preg_replace('![' . preg_quote($flip) . ']+!u', $separator, $sting);
        # Replace @ with the word 'at'
        $sting = str_replace('@', $separator . 'at' . $separator, $sting);
        # Remove all characters that are not the separator, letters, numbers, or whitespace.
        $sting = preg_replace('![^' . preg_quote($separator) . '\pL\pN\s]+!u', '', mb_strtolower($sting));
        # Replace all separator characters and whitespace by a single separator
        $sting = preg_replace('![' . preg_quote($separator) . '\s]+!u', $separator, $sting);
        # Trim
        return trim($sting, $separator);
    }

    /**
     * Genrate random string
     * @param int $length
     * @return string
     */
    public static function randomString( $length = 32 )
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ( $i = 0; $i < $length; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }

    public static function generateUniqueToken()
    {
        return md5(microtime() . randomString());
    }

    public static function generateToken($length = 32) {
        $characters = '12345679abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function generateInt($length = 32) {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function removeExtension($name, $mod = '_') {
        $parts = explode('.', $name);
        unset($parts[count($parts) - 1]);
        return implode($mod, $parts);
    }
    
    public static function echoAttributes($attributes) {
        
        if (!is_array($attributes)) {
            $attributes = json_decode($attributes, true);
        }
    
        foreach ($attributes as $key => $items) {
            if ($items != null && !empty($items)) {
                if (is_array($items)) {
                    $values = implode(' ', $items);
                } else {
                    $values = $items;
                }
                echo "{$key}={$values} ";
            }
        }
        
    }
    
}
 ?>
