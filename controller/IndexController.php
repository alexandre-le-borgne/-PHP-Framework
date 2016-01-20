<?php

/**
 * Le Controlleur correspondant a l'index
 */

class IndexController extends Controller
{
    public function IndexAction(Request $request)
    {
        var_dump($_POST);
        var_dump($_GET);
        $this->loadModel('IndexModel');
        if ($request->getSession()->isGranted(Session::USER_IS_CONNECTED))
        {
            $this->render('layouts/home', array('home' => 'Connecté'));
        }
        else
        {
            $this->render('layouts/layoutNotConnected');
        }
    }

    public function RssAction()
    {
        //var_dump($feed->getPosts());
        $this->loadModel('RssModel');
        $this->rssmodel->createStream("http://www.journaldunet.com/rss/", time() - 608400);
        $this->rssmodel->cron();

    }
}