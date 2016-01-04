<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 16/12/2015
 * Time: 14:37
 */

class IndexController extends Controller
{
    public function IndexAction()
    {
        $this->loadModel('IndexModel');
        $this->render('persists/home');
    }

    public function FeedAction()
    {
        $feed = new FeedRSS("http://www.journaldunet.com/rss/");
        //var_dump($feed->getPosts());
        foreach($feed->getPosts() as $post) {
            echo $post->getSummary();
        }
    }
}