<?php

/**
 * Coucou
 */

class TwitterController extends Controller
{
    function TwitterAction()
    {
        $this->loadModel('TwitterModel');
        /** @var TwitterModel $twitterModel */
        $twitterModel = $this->twittermodel;

        $dateTime = new DateTime();
        $dateTime->setDate(2015, 1, 1);

        $twitterModel->createStream('Spacesuit2', $dateTime);

        $twitterModel->cron();
//
//
//        $i = 1;
//        $channel = 'lalaitha';
//        $twitterModel = new TwitterModel();
//        $tweets = $twitterModel->getTweets($channel, true, 12);
//
//        $autolink = Twitter_Autolink::create();
//
//        $articles = array();
//
//        foreach ($tweets as $tweet):
//        {
//            $article = new ArticleEntity();
//            $article->setTitle($channel);
//            $article->setContent($tweet->text);
//            $article->setDate($tweet->created_at);
//            $article->setId($i++);
//            array_push($articles, $article);
//        }
//        endforeach;
//
//        echo "<br/><br/>";
//        foreach ($articles as $article):
//            if ($article instanceof ArticleEntity)
//                echo 'Titre : ' . $autolink->autoLink('@' . $article->getTitle()) . '<br/>' .
//                    $autolink->autoLink($article->getContent()) .
//                    '<br/>Il y a ' . time_to_delay(strtotime($article->getDate())) . '.<br/>';
//        endforeach;
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