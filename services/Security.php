<?php

/**
 * Class Security
 */
class Security
{
    /**
     * @param string $string
     * @return string
     */
    public function escape($string)
    {
        return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    /**
     * @return string
     */
    public function generateKey()
    {
        return md5(microtime(TRUE) * 100000);
    }

    /**
     * @param string $string
     * @return string
     */
    public function encode($string)
    {
        $key = hash('sha512', $string);
        $encoded = crypt($string, '$6$rounds=5000$' . $key . '$');
        return $encoded;
    }

    /**
     * @param string $hashedPassword
     * @param string $userPassword
     * @return bool
     */
    public function equals($hashedPassword, $userPassword)
    {
        if (!function_exists('hash_equals'))
        {
            function hash_equals($str1, $str2)
            {
                if (strlen($str1) != strlen($str2))
                {
                    return false;
                }
                else
                {
                    $res = $str1 ^ $str2;
                    $ret = 0;
                    for ($i = strlen($res) - 1; $i >= 0; $i--) $ret |= ord($res[$i]);
                    return !$ret;
                }
            }
        }
        return hash_equals($hashedPassword, crypt($userPassword, $hashedPassword));
    }
}