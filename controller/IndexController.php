<?php

/**
 * Le Controlleur correspondant a l'index
 *
 */
class IndexController extends Controller
{
    public function IndexAction(Request $request)
    {
        $this->loadModel('IndexModel');
        if ($request->getSession()->isGranted(Session::USER_IS_CONNECTED))
        {
            $this->render('layouts/home', array('home' => 'Connecté'));
        } else
        {
            $this->render('layouts/notConnectedForm');
        }
    }

    public function RssAction()
    {
        $feed = new RssModel("http://www.journaldunet.com/rss/");
        //var_dump($feed->getPosts());
        foreach ($feed->getPosts() as $post)
        {
            echo $post->getSummary();
        }
    }
}