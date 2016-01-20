<?php

/**
 * Le Controlleur correspondant a l'index
 */
/*
set_include_path( get_include_path() . PATH_SEPARATOR . 'vendor/google/apiclient/src' );
require_once('Google/Client.php');
require_once('Google/Auth/OAuth2.php');*/

class IndexController extends Controller
{
    public function IndexAction(Request $request)
    {
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