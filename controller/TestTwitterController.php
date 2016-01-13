<?php
/**
 * Coucou
 * dzada
 * dza
 * dzza
 * da
 * d
 * zad
 * za
 * dz
 */

use Abraham\TwitterOAuth\TwitterOAuth;

require './../vendor/abraham/twitteroauth/autoload.php';

class TestTwitterController extends Controller
{
    function TwitterAction()
    {
        //Consumer key,
        $oauth = new TwitterOAuth("rC3gP2pji5zoKoGf4FlUYdvaa", "TYIpFvcb9wR6SrpdxmMCPruiyJSPSDfJdLz6cAlNgqoyMcMq2j");
        $accesstoken = $oauth->oauth2('oauth2/token', ['grant_type' => 'client_credentials']);
        var_dump($accesstoken);

        $twitter = new TwitterOAuth("rC3gP2pji5zoKoGf4FlUYdvaa", "TYIpFvcb9wR6SrpdxmMCPruiyJSPSDfJdLz6cAlNgqoyMcMq2j", null, $accesstoken->access_token);
    }
}

//s
//http://www.grafikart.fr/tutoriels/php/twitter-api-tweets-100
//"token_type": "bearer",
//"access_token": "AAAAAAAAAAAAAAAAAAAAAGszjwAAAAAAQ9wM2BLgs2H5JAbsI6Iv9gE6xDU%3DiXa5ZguYCBBgkWld7lIhMWzfPUHbpqDuwQgktXQ8qVoR8GjnOj"
