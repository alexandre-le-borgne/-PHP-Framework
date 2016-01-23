<?php

/**
 * Coucou
 */

require_once './vendor/abraham/twitteroauth/autoload.php';
require_once './vendor/nojimage/twitter-text-php/lib/Twitter/Autolink.php';

use Abraham\TwitterOAuth\TwitterOAuth;

class TwitterController extends Controller
{
    function addTwitterStreamAction(Request $request)
    {
        $channel = $request->post('channel');
        $firstUpdate = $request->get('firstUpdate');
        $userId = $request->getSession()->get('id');
        $categoryTitle = $request->post('category');

        if (!($channel && $firstUpdate && $categoryTitle))
            throw new Exception('Pas tous les arguments inseres');

        //Chargement des models
        $this->loadModel('CategoryModel');
        /** @var CategoryModel $categoryModel */
        $categoryModel = $this->categorymodel;
        $this->loadModel('TwitterModel');
        /** @var TwitterModel $twitterModel */
        $twitterModel = $this->twittermodel;

        //Creation du flux
        $dateTime = new DateTime();
        $dateTime->setTimestamp(strtotime($firstUpdate));
        $twitterModel->createStream($channel, $dateTime);


        $defaultCategory = null;
        $category = null;

        $categories = $categoryModel->getByUserId($userId);
        foreach ($categories as $cat)
        {
            /** @var CategoryEntity $cat */
            if ($cat->getTitle() == 'Twitter')
                $defaultCategory = $cat;
            if ($cat->getTitle() == $categoryTitle)
                $category = $cat;
        }

        $streamCategory = new StreamCategoryEntity();
        $streamCategory->setId($userId);
        $streamCategory->setStreamType(ArticleModel::TWITTER);
        $streamCategory->setStream($twitterModel->getStreamByChannel($channel)->getId());

        if ($category)
        {
            //La categorie donnee en param existe, on ajoute donc le stream cree dans cette categorie.
            //On insere alors une ligne dans stream_category
            $streamCategory->setCategory($category->getId());
            $streamCategory->persist();
        }
        else
        {
            if ($defaultCategory)
            {
                //On place le stream dans la categorie par defaut, qui s'appelle Twitter
                $streamCategory->setCategory($defaultCategory->getId());
                $streamCategory->persist();
            }
            else
            {
                //On cree la categorie par defaut, qui s'appelle Twitter, puis on place le stream dans celle la
                $newDefaultCategory = new CategoryEntity();
                $newDefaultCategory->setAccount($userId);
                $newDefaultCategory->setTitle('Twitter');
                $newDefaultCategory->persist();
                $streamCategory->setCategory($newDefaultCategory->getId());
                $streamCategory->persist();
            }
        }

        $twitterEntity = new TwitterEntity();
        $twitterEntity->setChannel($channel);
        $twitterModel->streamCron($twitterEntity);

        //

        //

        //

        //

        //

        //

        //Todo redirection sur le home, on affiche les streams

        //Creation de la categorie

        /*
         *
         * Ajouter un stream :
            Nom categorie
            Les champs pour chaque stream Twitter/email/etc

            Le bouton de soumission du formulaire
            Action a faire :
            Une categorie existe ? Si non on créer un categorie avec le nom passé dans le formulaire
            On creer le stream
            On créér un stream Category qu'on persist, avec l'id su stream, son type et la catégorie qui a été créé ou celle qui existe
            pour acceder aux categories des utilisateur : CategoryModel => getByUserId($id)
         */
    }

    //Test
    function TestTwitterAction()
    {
        $CONSUMER_KEY = "rC3gP2pji5zoKoGf4FlUYdvaa";
        $CONSUMER_SECRET = "TYIpFvcb9wR6SrpdxmMCPruiyJSPSDfJdLz6cAlNgqoyMcMq2j";
        $this->loadModel('TwitterModel');
        /** @var TwitterModel $twitterModel */
        $twitterModel = $this->twittermodel;

        $oauth = new TwitterOAuth("rC3gP2pji5zoKoGf4FlUYdvaa", "TYIpFvcb9wR6SrpdxmMCPruiyJSPSDfJdLz6cAlNgqoyMcMq2j");
        $accesstoken = $oauth->oauth2('oauth2/token', ['grant_type' => 'client_credentials']);
        $twitter = new TwitterOAuth("rC3gP2pji5zoKoGf4FlUYdvaa",
            "TYIpFvcb9wR6SrpdxmMCPruiyJSPSDfJdLz6cAlNgqoyMcMq2j", null, $accesstoken->access_token);


        $tweets = $twitter->get('statuses/user_timeline', [
            'screen_name' => 'MonsieurDream',
            'exclude_replies' => true,
            'count' => 1
        ]);

        foreach ($tweets as  $tweet)
        {
            echo $tweet->text;
            var_dump($tweet);
            echo '<br/>#############################################################################<br/>';
        }
    }
}


//http://www.grafikart.fr/tutoriels/php/twitter-api-tweets-100
//"token_type": "bearer",s
//"access_token": "AAAAAAAAAAAAAAAAAAAAAGszjwAAAAAAQ9wM2BLgs2H5JAbsI6Iv9gE6xDU%3DiXa5ZguYCBBgkWld7lIhMWzfPUHbpqDuwQgktXQ8qVoR8GjnOj"
/**
 *
 *
 * <ul class="white_text">
 * <?php foreach (array_slice($tweets, 0, 12) as $tweet): ?>
 * <li><?php $this->render('layouts/tweetTemplate', $tweet->text); ?></li>
 * <?php endforeach; ?>
 * </ul>
 *
 *
 *
 *
 *
 *
 */


//        $i = 1;
//        $channel = 'lalaitha';
//        $twitterModel = new TwitterModel();
//        $tweets = $twitterModel->getTweets($channel, true, 12);
//
//        $autolink = Twitter_Autolink::create();
//
//        $articles = array();
//
//        foreach ($tweets as $tweet):
//        {
//            $article = new ArticleEntity();
//            $article->setTitle($channel);
//            $article->setContent($tweet->text);
//            $article->setDate($tweet->created_at);
//            $article->setId($i++);
//            array_push($articles, $article);
//        }
//        endforeach;
//
//        echo "<br/><br/>";
//        foreach ($articles as $article):
//            if ($article instanceof ArticleEntity)
//                echo 'Titre : ' . $autolink->autoLink('@' . $article->getTitle()) . '<br/>' .
//                    $autolink->autoLink($article->getContent()) .
//                    '<br/>Il y a ' . time_to_delay(strtotime($article->getDate())) . '.<br/>';
//        endforeach;