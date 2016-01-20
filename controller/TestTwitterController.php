<?php

/**
 * Coucou
 */
class TestTwitterController extends Controller
{
    function TwitterAction()
    {
        $twitterModel = new TwitterModel();
        $tweets = $twitterModel->getTweets('Spacesuit2', true, 12);

        $autolink = Twitter_Autolink::create();
        echo "<br/><br/>";
        foreach ($tweets as $tweet):
            echo 'tweet : ' . $autolink->autoLink($tweet->text) . '<br/>Il y a ' . time_to_delay(strtotime($tweet->created_at)) . '.<br/>';
        endforeach;
    }
}


//http://www.grafikart.fr/tutoriels/php/twitter-api-tweets-100
//"token_type": "bearer",s
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