<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 29/05/2016
 * Time: 22:50
 */
class StringManipulation
{
    function str_lreplace($search, $replace, $subject)
    {
        $pos = strrpos($subject, $search);

        if($pos !== false)
        {
            $subject = substr_replace($subject, $replace, $pos, strlen($search));
        }

        return $subject;
    }
}