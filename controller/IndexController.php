<?php

/**
 * Le Controlleur correspondant a l'index
 */

class IndexController extends Controller
{
    public function IndexAction(Request $request, $channel = null)
    {
        if($channel) {
            echo "Chargement de la chaine de $channel";
        }
        else {
            if ($request->getSession()->isGranted(Session::USER_IS_CONNECTED))
            {
                $this->render('layouts/home', array('home' => 'Connecté'));
            }
            else
            {
                $this->render('forms/loginForm');
            }
        }
    }

    public function CronAction() {
        $this->loadModel('EmailModel');
        $this->loadModel('RssModel');
        $this->loadModel('TwitterModel');
        $this->emailmodel->cron();
        $this->rssmodel->cron();
        $this->twittermodel->cron();
    }

    public function RssAction()
    {
        $firstUpdate = new DateTime('2016-01-01');
        $lastUpdate = new DateTime('2016-01-16');
        //var_dump($feed->getPosts());
        $this->loadModel('RssModel');
        $this->rssmodel->createStream("http://www.journaldunet.com/rss/", time() - 608400);
        $this->rssmodel->cron();

    }
}