<?php

class Securite {

    public static function insertBD($string)
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
}