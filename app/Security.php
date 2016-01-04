<?php

class Security
{
    /* http://stackoverflow.com/questions/134099/are-pdo-prepared-statements-sufficient-to-prevent-sql-injection*/
    public static function escape($string)
    {
        return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    public static function generateKey()
    {
        return md5(microtime(TRUE)*100000);
    }

    public static function encode($str)
    {
        $key = hash('sha512', $str);
        $encoded = crypt($str, '$6$rounds=5000$' . $key . '$');
        return $encoded;
    }

    public static function equals($hashedPassword, $userPassword)
    {
        return hash_equals($hashedPassword, crypt($userPassword, $hashedPassword));
    }
}