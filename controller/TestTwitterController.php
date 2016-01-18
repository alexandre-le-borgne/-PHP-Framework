<?php
/**
 * Coucou
 */

require_once './vendor/twitteroauth/autoload.php';
require_once './vendor/nojimage/twitter-text-php/lib/Twitter/Autolink.php';
require_once './app/util/time_to_.php';

use Abraham\TwitterOAuth\TwitterOAuth;

class TestTwitterController extends Controller
{
    function TwitterAction()
    {
        //Tout cela pour récupérer les tweets d'un compte donné
        //Consumer key, consumerSecret, oauthToken, oauthTokenSecret
        $oauth = new TwitterOAuth("rC3gP2pji5zoKoGf4FlUYdvaa", "TYIpFvcb9wR6SrpdxmMCPruiyJSPSDfJdLz6cAlNgqoyMcMq2j");
        $accesstoken = $oauth->oauth2('oauth2/token', ['grant_type' => 'client_credentials']);
        var_dump($accesstoken);

        $twitter = new TwitterOAuth("rC3gP2pji5zoKoGf4FlUYdvaa", "TYIpFvcb9wR6SrpdxmMCPruiyJSPSDfJdLz6cAlNgqoyMcMq2j", null, $accesstoken->access_token);

        $tweets = $twitter->get('statuses/user_timeline', [
            'screen_name' => 'Spacesuit2',
            'exclude_replies' => 'true',
            'count' => 50
        ]);

        //Pour afficher avec les liens : + $autolink-autolink($tweet-text)
        $autolink = Twitter_Autolink::create();


        // (array_slice($tweets, 0, 12) car le count se fait avant d'avoir récupéré les tweets
        // donc on récupère beaucoup et on gère le count intérieurement

        echo "<br/><br/>";
        foreach (array_slice($tweets, 0, 12) as $tweet):
            echo "tweet : " . $autolink->autoLink($tweet->text) . "<br/>" . time_to_delay(time(), true, 'Posté il y a ', '.', $tweet->created_at) . '<br/>';
        endforeach;


    }
    /**
     * @param mixed $models
     * @return Controller
     */
    public function setModels($models)
    {
        $this->models = $models;
        return $this;
    }
}


//http://www.grafikart.fr/tutoriels/php/twitter-api-tweets-100
//"token_type": "bearer",
//"access_token": "AAAAAAAAAAAAAAAAAAAAAGszjwAAAAAAQ9wM2BLgs2H5JAbsI6Iv9gE6xDU%3DiXa5ZguYCBBgkWld7lIhMWzfPUHbpqDuwQgktXQ8qVoR8GjnOj"
/**
 *
 *
 * <ul class="white_text">
 * <?php foreach (array_slice($tweets, 0, 12) as $tweet): ?>
 * <li><?php $this->render('layouts/tweetTemplate', $tweet->text); ?></li>
 * <?php endforeach; ?>
 * </ul>
 *
 *
 *
 *
 *
 *
 */