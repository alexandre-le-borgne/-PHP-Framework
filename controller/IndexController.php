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
        if($request->getSession()->isGranted(Session::USER_IS_CONNECTED)) {
            echo "CONNECTER!";

        }
        else {
            $this->render('layouts/notConnectedForm');
        }
    }

    public function FeedAction()
    {
        $feed = new RSSReaderModel("http://www.journaldunet.com/rss/");
        //var_dump($feed->getPosts());
        foreach($feed->getPosts() as $post) {
            echo $post->getSummary();
        }
    }

    public function EmailAction($id = 0) {
        phpinfo();
        $this->loadModel('EmailModel');

        var_dump($this->emailmodel->get($id));
    }
}