<?php

/**
 * Le Controlleur correspondant a l'index
 *
 */
require_once('./vendor/google/apiclient/src/Google/autoload.php');

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
            ######### edit details ##########
            $clientId = '150676207911-artsrukbljruts6t2t0675q8c1l4o8av.apps.googleusercontent.com'; //Google CLIENT ID
            $clientSecret = '6SllD3XReMzfXKdZl1M9A2lm'; //Google CLIENT SECRET
            $redirectUrl = 'http://alex83690.alwaysdata.net/aaron/facebook';  //return url (url to script)
            $homeUrl = 'http://alex83690.alwaysdata.net/aaron';  //return to home

            $gClient = new Google_Client();
            $gClient->setApplicationName('Se connecter à Aaron');
            $gClient->setClientId($clientId);
            $gClient->setClientSecret($clientSecret);
            $gClient->setRedirectUri($redirectUrl);

            $google_oauthV2 = new Google_Oauth2Service($gClient);

            $authUrl = $gClient->createAuthUrl();

            $this->render('layouts/notConnectedForm', array('authUrl' => $authUrl));
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