<?php
/**
 * Le Controlleur correspondant a l'index
 *
 */

class IndexController extends Controller
{
    public function IndexAction()
    {
        phpinfo();
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