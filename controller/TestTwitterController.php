<?php
///**
// * Coucou
// */
//
//
////var_dump(scandir('./vendor/abraham/twitteroauth/src'));
//
//require './vendor/autoload.php';
//require './vendor/abraham/twitteroauth/autoload.php';
//
//use Abraham\TwitterOAuth;
////use Abraham\TwitterOAuth\TwitterOAuth;
//
////require '/../vendor/abraham/twitteroauth/autoload.php';
//
//class TestTwitterController extends Controller
//{
//    function TwitterAction()
//    {
//        //Tout cela pour récupérer les tweets d'un compte donné
//        //Consumer key,
//        $oauth = new TwitterOAuth("rC3gP2pji5zoKoGf4FlUYdvaa", "TYIpFvcb9wR6SrpdxmMCPruiyJSPSDfJdLz6cAlNgqoyMcMq2j");
//        $accesstoken = $oauth->oauth2('oauth2/token', ['grant_type' => 'client_credentials']);
////        var_dump($accesstoken);
//
//        $twitter = new TwitterOAuth("rC3gP2pji5zoKoGf4FlUYdvaa", "TYIpFvcb9wR6SrpdxmMCPruiyJSPSDfJdLz6cAlNgqoyMcMq2j", null, $accesstoken->access_token);
//
//        $tweets = $twitter->get('statuses/user_timeline', [
//            'screen_name' => 'Spacesuit2',
//            'exclude_replies' => 'true',
//            'count' => 50
//        ]);
//
//        // (array_slice($tweets, 0, 12) car le count se fait avant d'avoir récupéré les tweets
//        // donc on récupère beaucoup et on gère le count intérieurement
//
//        ?>
<!--        <ul class="white_text">-->
<!--            --><?php //foreach (array_slice($tweets, 0, 12) as $tweet): ?>
<!--                <li>--><?php //$this->render('layouts/tweetTemplate',$tweet->text); ?><!--</li>-->
<!--            --><?php //endforeach; ?>
<!--        </ul>-->
<!--    --><?php
//    }
//}
//
////s
////http://www.grafikart.fr/tutoriels/php/twitter-api-tweets-100
////"token_type": "bearer",
////"access_token": "AAAAAAAAAAAAAAAAAAAAAGszjwAAAAAAQ9wM2BLgs2H5JAbsI6Iv9gE6xDU%3DiXa5ZguYCBBgkWld7lIhMWzfPUHbpqDuwQgktXQ8qVoR8GjnOj"
