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
        $categoryTitle = $request->post('category');
        $firstUpdate = $request->post('firstUpdate');
        $channel = $request->post('channel');
        $userId = $request->getSession()->get('id');

        $this->loadModel('CategoryModel');
        $this->loadModel('TwitterModel');

        if (!$this->twittermodel->isValidChannel($channel))
            throw new Exception('Chaine invalide');


        $twitterEntity = $this->twittermodel->createStream($channel, $firstUpdate);
        $categoryEntity = $this->categorymodel->createCategory($userId, $categoryTitle);

        $streamCategoryEntity = new StreamCategoryEntity();
        $streamCategoryEntity->setCategory($categoryEntity->getId());
        $streamCategoryEntity->setStream($twitterEntity->getId());
        $streamCategoryEntity->setStreamType(ArticleModel::TWITTER);
        $streamCategoryEntity->persist();

        /** @var TwitterEntity $twitterEntity */
        $this->twittermodel->streamCron($twitterEntity);
        $this->render('index');
    }

    //Test
    function TestTwitterAction()
    {
//
//        $CONSUMER_KEY = "rC3gP2pji5zoKoGf4FlUYdvaa";
//        $CONSUMER_SECRET = "TYIpFvcb9wR6SrpdxmMCPruiyJSPSDfJdLz6cAlNgqoyMcMq2j";
//        $this->loadModel('TwitterModel');
//        /** @var TwitterModel $twitterModel */
//        $twitterModel = $this->twittermodel;
//
//        $oauth = new TwitterOAuth("rC3gP2pji5zoKoGf4FlUYdvaa", "TYIpFvcb9wR6SrpdxmMCPruiyJSPSDfJdLz6cAlNgqoyMcMq2j");
//        $accesstoken = $oauth->oauth2('oauth2/token', ['grant_type' => 'client_credentials']);
//        $twitter = new TwitterOAuth("rC3gP2pji5zoKoGf4FlUYdvaa",
//            "TYIpFvcb9wR6SrpdxmMCPruiyJSPSDfJdLz6cAlNgqoyMcMq2j", null, $accesstoken->access_token);
//
//        $tweets = $twitter->get('statuses/user_timeline', [
//            'screen_name' => 'MonsieurDream',
//            'exclude_replies' => true,
//            'count' => 1
//        ]);

        $this->loadModel('TwitterModel');
        /** @var TwitterModel $twitterModel */
        $twitterModel = $this->twittermodel;

        $twitterModel->isValidChannel('Spacssssssesuit2');


    }
}


//
//        $tweet = $tweets[0];
//
//        // $tweet->extended_entities->media->media_url; = la si elle est presente
//        // $tweet->extended_entities->media->type; = le type 'photo si photo'
//        // $tweet->extended_entities->media->url; = l'url du tweet, du statut
//
//        $imageLink = '';
//
//        if (isset($tweet->extended_entities->media[0]->url, $tweet->extended_entities->media[0]->media_url))
//            $imageLink = '<a href="' . $tweet->extended_entities->media[0]->url . '" target="_blank"><img src="' . $tweet->extended_entities->media[0]->media_url . '"></a>';
//
//
//        var_dump($tweet->extended_entities);
//        $href = $tweet->extended_entities->media[0]->url;
//        $src = $tweet->extended_entities->media[0]->media_url;
//
//        echo $imageLink;
//        echo '<br/>#############################################################################<br/>';


//        if (!($channel && $firstUpdate && $categoryTitle))
//            throw new Exception('Pas tous les arguments inseres');
//
//        //Chargement des models
//        $this->loadModel('CategoryModel');
//        /** @var CategoryModel $categoryModel */
//        $categoryModel = $this->categorymodel;
//        $this->loadModel('TwitterModel');
//        /** @var TwitterModel $twitterModel */
//        $twitterModel = $this->twittermodel;
//
//        //Creation du flux
//        $dateTime = new DateTime();
//        $dateTime->setTimestamp(strtotime($firstUpdate));
//        $twitterModel->createStream($channel, $dateTime);
//
//
//        $defaultCategory = null;
//        $category = null;
//
//        $categories = $categoryModel->getByUserId($userId);
//        foreach ($categories as $cat)
//        {
//            /** @var CategoryEntity $cat */
//            if ($cat->getTitle() == 'Twitter')
//                $defaultCategory = $cat;
//            if ($cat->getTitle() == $categoryTitle)
//                $category = $cat;
//        }
//
//        $streamCategory = new StreamCategoryEntity();
//        $streamCategory->setId($userId);
//        $streamCategory->setStreamType(ArticleModel::TWITTER);
//        $streamCategory->setStream($twitterModel->getStreamByChannel($channel)->getId());
//
//        if ($category)
//        {
//            //La categorie donnee en param existe, on ajoute donc le stream cree dans cette categorie.
//            //On insere alors une ligne dans stream_category
//            $streamCategory->setCategory($category->getId());
//            $streamCategory->persist();
//        }
//        else
//        {
//            if ($defaultCategory)
//            {
//                //On place le stream dans la categorie par defaut, qui s'appelle Twitter
//                $streamCategory->setCategory($defaultCategory->getId());
//                $streamCategory->persist();
//            }
//            else
//            {
//                //On cree la categorie par defaut, qui s'appelle Twitter, puis on place le stream dans celle la
//                $newDefaultCategory = new CategoryEntity();
//                $newDefaultCategory->setAccount($userId);
//                $newDefaultCategory->setTitle('Twitter');
//                $newDefaultCategory->persist();
//                $streamCategory->setCategory($newDefaultCategory->getId());
//                $streamCategory->persist();
//            }
//        }
//
//        $twitterEntity = new TwitterEntity();
//        $twitterEntity->setChannel($channel);
//        $twitterModel->streamCron($twitterEntity);

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