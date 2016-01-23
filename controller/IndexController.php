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
                $this->loadModel('CategoryModel');
                $this->loadModel('ArticleModel');
                $categories = $this->categorymodel->getByUserId($request->getSession()->get('id'));
                $articles = $this->articlemodel->getArticlesByUserId($request->getSession()->get('id'), 0, 20);
                $data = array('categories' => $categories, 'articles' => $articles);
                $this->render('layouts/home', $data);
            }
            else
            {
                $this->render('forms/loginForm');
            }
        }
    }

    public function CronAction() {
        // Vérifier qu'on est bien dans les horaires du cron (5H du mat)
        $this->loadModel('EmailModel');
        $this->loadModel('RssModel');
        $this->loadModel('TwitterModel');
        $this->emailmodel->cron();
        $this->rssmodel->cron();
        $this->twittermodel->cron();
    }

    public function RssAction()
    {
        $test = new DateTime();
        $test->setTimestamp(time() - 608400);


        //var_dump($feed->getPosts());
        $this->loadModel('RssModel');
        $this->rssmodel->createStream("http://www.journaldunet.com/rss/", $test);
        $this->rssmodel->cron();

    }
}