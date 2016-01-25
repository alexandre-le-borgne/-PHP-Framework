<?php

class IndexController extends Controller
{
    public function IndexAction(Request $request)
    {
        if ($request->getSession()->isGranted(Session::USER_IS_CONNECTED))
        {
            $this->loadModel('ArticleModel');
            $articles = $this->articlemodel->getArticlesByUserId($request->getSession()->get('id'), 0, 10);
            $data = array('articles' => $articles);
            $this->render('layouts/home', $data);
        }
        else
        {
            $this->render('forms/loginForm');
        }
    }

    public function SearchAction(Request $request) {
        $search = $request->post('search');
        $this->loadModel('UserModel');
        if($search) {
            /** @var UserEntity $userEntity */
            $userEntity = $this->usermodel->getByNameOrEmail($search);
            if($userEntity) {
                $this->redirectToRoute('channel', array($userEntity->getUsername()));
            }
        }
        else {
            $this->redirectToRoute('index');
        }
    }

    public function AsideAction(Request $request) {
        if($request->isInternal())
        {
            $this->loadModel('CategoryModel');
            $this->loadModel('EmailModel');
            $this->loadModel('TwitterModel');
            $this->loadModel('RssModel');
            $id = $request->getSession()->get('id');
            $categories = $this->categorymodel->getByUserId($id);
            $emailStreams = $this->emailmodel->getByUserId($id);
            $twitterStreams = $this->twittermodel->getByUserId($id);
            $rssStreams = $this->rssmodel->getByUserId($id);
            $streams = array('emailStreams' => $emailStreams, 'twitterStreams' => $twitterStreams, 'rssStreams' => $rssStreams);
            $data = array('categories' => $categories, 'streams' => $streams);
            $this->render('layouts/aside', $data);
        }
    }

    public function AddStreamAction() {
        $this->render('layouts/addStream');
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
        $this->loadModel('RssModel');
        $this->rssmodel->createStream("http://www.journaldunet.com/rss/", $test);
        $this->rssmodel->cron();

    }
}