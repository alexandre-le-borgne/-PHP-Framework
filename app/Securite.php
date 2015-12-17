<?php

class Securite {

    public static function escape($string)
    {
        if (ctype_digit($string))
            return intval($string);
        else {
            $string = mysql_real_escape_string($string);
            $string = addcslashes($string, '%_');
        }

        return $string;
    }

    public static function display($string){
        return htmlentities($string);
    }

    public static function encode($str){
        $key = hash('sha512', $str);
        $encoded = crypt($str, '$6$rounds=5000$' . $key . '$');

        return $encoded;
    }
}