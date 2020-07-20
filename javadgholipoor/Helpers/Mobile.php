<?php
namespace Larabase\Helper;

class Mobile
{
    public static function sanitize( $phone ){
        /**
         * Convert all chars to en digits
         */
        $phone = Converter::toEnglish( $phone );
	    # 00989185223232
	    if( strpos( $phone, '0098' ) === 0 ){
            $phone = substr( $phone, 4 );
        }
        # 0989108210911
        if( strlen( $phone ) == 13 && strpos( $phone, '098' ) === 0 ){
            $phone = substr( $phone, 3 );
        }
        # +989156040160
        if( strlen( $phone ) == 13 && strpos( $phone, '+98' ) === 0 ){
            $phone = substr( $phone, 3 );
        }
        # 989152532120
        if( strlen( $phone ) == 12 && strpos( $phone, '98' ) === 0 ){
            $phone = substr( $phone, 2 );
        }
        # Prepend 0
        if( strpos( $phone, '0' ) !== 0 ){
            $phone = '0' . $phone;
        }
        # Check for all character was digit
        if( ! ctype_digit( $phone ) ){
            return '';
        }
        if( strlen( $phone ) != 11 ){
            return '';
        }
	    return $phone;
    }

}
