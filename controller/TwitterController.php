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

        $tweet = $tweets[0];
        echo $tweet->media_url;
        echo '<br/>#############################################################################<br/>';
        echo '<br/>#############################################################################<br/>';
        echo '<br/>#############################################################################<br/>';
        echo '<br/>#############################################################################<br/>';

        var_dump($tweet);
        echo '<br/>#############################################################################<br/>';

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


/*



stdClass Object (
[created_at] => Sat Jan 23 17:35:15 +0000 2016
[id] => 690950937213550592
[id_str] => 690950937213550592
[text] => RT @rems0120: A lundi @MonsieurDream 🎬 https://t.co/eVLeXgSE1g
[source] => Twitter Web Client
[truncated] => [in_reply_to_status_id] => [in_reply_to_status_id_str] => [in_reply_to_user_id] => [in_reply_to_user_id_str] => [in_reply_to_screen_name] =>

[user] => stdClass Object (
[id] => 6661522
[id_str] => 6661522
[name] => Cyprien
[screen_name] => MonsieurDream
[location] => Paris
[description] => J'suis derrière @NarmolTshirt et @CyprienGaming, mon Snap : CyprienVideo [url] => http://t.co/xVb77ovOk6 [entities] =>


stdClass Object (
[url] => stdClass Object (
[urls] => Array (
[0] => stdClass Object (
[url] => http://t.co/xVb77ovOk6
[expanded_url] => http://www.cyprien.fr/
[display_url] => cyprien.fr
[indices] => Array ( [0] => 0 [1] => 22 ) ) ) )
[description] => stdClass Object (
[urls] => Array ( ) ) ) [protected] =>
[followers_count] => 5009053 [friends_count] => 467 [listed_count] => 4282 [created_at] => Fri Jun 08 08:21:12 +0000 2007 [favourites_count] => 3 [utc_offset] => 3600 [time_zone] => Paris [geo_enabled] => 1 [verified] => 1 [statuses_count] => 10724 [lang] => fr [contributors_enabled] => [is_translator] => [is_translation_enabled] => [profile_background_color] => F1F5F6 [profile_background_image_url] => http://pbs.twimg.com/profile_background_images/378800000075071311/3c320b2879cd1d366745a42f13254912.jpeg [profile_background_image_url_https] => https://pbs.twimg.com/profile_background_images/378800000075071311/3c320b2879cd1d366745a42f13254912.jpeg [profile_background_tile] => [profile_image_url] => http://pbs.twimg.com/profile_images/682151829480861696/kqaGJuf5_normal.jpg [profile_image_url_https] => https://pbs.twimg.com/profile_images/682151829480861696/kqaGJuf5_normal.jpg [profile_banner_url] => https://pbs.twimg.com/profile_banners/6661522/1398182466 [profile_link_color] => 006699 [profile_sidebar_border_color] => FFFFFF [profile_sidebar_fill_color] => 9B989F [profile_text_color] => 000000 [profile_use_background_image] => 1 [has_extended_profile] => [default_profile] => [default_profile_image] => [following] => [follow_request_sent] => [notifications] => ) [geo] => [coordinates] => [place] => [contributors] => [retweeted_status] => stdClass Object ( [created_at] => Sat Jan 23 12:12:58 +0000 2016 [id] => 690869832330416128 [id_str] => 690869832330416128 [text] => A lundi @MonsieurDream 🎬 https://t.co/eVLeXgSE1g [source] => Twitter for iPhone [truncated] => [in_reply_to_status_id] => [in_reply_to_status_id_str] => [in_reply_to_user_id] => [in_reply_to_user_id_str] => [in_reply_to_screen_name] => [user] => stdClass Object ( [id] => 2783584450 [id_str] => 2783584450 [name] => leu' [screen_name] => rems0120 [location] => [description] => [url] => [entities] => stdClass Object ( [description] => stdClass Object ( [urls] => Array ( ) ) ) [protected] => [followers_count] => 103 [friends_count] => 103 [listed_count] => 0 [created_at] => Thu Sep 25 09:33:00 +0000 2014 [favourites_count] => 110 [utc_offset] => [time_zone] => [geo_enabled] => [verified] => [statuses_count] => 127 [lang] => fr [contributors_enabled] => [is_translator] => [is_translation_enabled] => [profile_background_color] => C0DEED [profile_background_image_url] => http://abs.twimg.com/images/themes/theme1/bg.png [profile_background_image_url_https] => https://abs.twimg.com/images/themes/theme1/bg.png [profile_background_tile] => [profile_image_url] => http://pbs.twimg.com/profile_images/682736516595073024/ks5JafDH_normal.jpg [profile_image_url_https] => https://pbs.twimg.com/profile_images/682736516595073024/ks5JafDH_normal.jpg [profile_banner_url] => https://pbs.twimg.com/profile_banners/2783584450/1439076379 [profile_link_color] => 0084B4 [profile_sidebar_border_color] => C0DEED [profile_sidebar_fill_color] => DDEEF6 [profile_text_color] => 333333 [profile_use_background_image] => 1 [has_extended_profile] => 1 [default_profile] => 1 [default_profile_image] => [following] => [follow_request_sent] => [notifications] => ) [geo] => [coordinates] => [place] => [contributors] => [is_quote_status] => [retweet_count] => 37 [favorite_count] => 326 [entities] => stdClass Object ( [hashtags] => Array ( ) [symbols] => Array ( ) [user_mentions] => Array ( [0] => stdClass Object ( [screen_name] => MonsieurDream [name] => Cyprien [id] => 6661522 [id_str] => 6661522 [indices] => Array ( [0] => 8 [1] => 22 ) ) ) [urls] => Array ( ) [media] => Array ( [0] => stdClass Object ( [id] => 690869823035805696 [id_str] => 690869823035805696 [indices] => Array ( [0] => 25 [1] => 48 ) [media_url] => http://pbs.twimg.com/media/CZZ2cZQWEAAziYX.jpg [media_url_https] => https://pbs.twimg.com/media/CZZ2cZQWEAAziYX.jpg [url] => https://t.co/eVLeXgSE1g [display_url] => pic.twitter.com/eVLeXgSE1g [expanded_url] => http://twitter.com/rems0120/status/690869832330416128/photo/1 [type] => photo [sizes] => stdClass Object ( [thumb] => stdClass Object ( [w] => 150 [h] => 150 [resize] => crop ) [medium] => stdClass Object ( [w] => 600 [h] => 800 [resize] => fit ) [large] => stdClass Object ( [w] => 768 [h] => 1024 [resize] => fit ) [small] => stdClass Object ( [w] => 340 [h] => 453 [resize] => fit ) ) ) ) ) [extended_entities] => stdClass Object ( [media] => Array ( [0] => stdClass Object ( [id] => 690869823035805696 [id_str] => 690869823035805696 [indices] => Array ( [0] => 25 [1] => 48 ) [media_url] => http://pbs.twimg.com/media/CZZ2cZQWEAAziYX.jpg [media_url_https] => https://pbs.twimg.com/media/CZZ2cZQWEAAziYX.jpg [url] => https://t.co/eVLeXgSE1g [display_url] => pic.twitter.com/eVLeXgSE1g [expanded_url] => http://twitter.com/rems0120/status/690869832330416128/photo/1 [type] => photo [sizes] => stdClass Object ( [thumb] => stdClass Object ( [w] => 150 [h] => 150 [resize] => crop ) [medium] => stdClass Object ( [w] => 600 [h] => 800 [resize] => fit ) [large] => stdClass Object ( [w] => 768 [h] => 1024 [resize] => fit ) [small] => stdClass Object ( [w] => 340 [h] => 453 [resize] => fit ) ) ) ) ) [favorited] => [retweeted] => [possibly_sensitive] => [lang] => pt ) [is_quote_status] => [retweet_count] => 37 [favorite_count] => 0 [entities] => stdClass Object ( [hashtags] => Array ( ) [symbols] => Array ( ) [user_mentions] => Array ( [0] => stdClass Object ( [screen_name] => rems0120 [name] => leu' [id] => 2783584450 [id_str] => 2783584450 [indices] => Array ( [0] => 3 [1] => 12 ) ) [1] => stdClass Object ( [screen_name] => MonsieurDream [name] => Cyprien [id] => 6661522 [id_str] => 6661522 [indices] => Array ( [0] => 22 [1] => 36 ) ) ) [urls] => Array ( ) [media] => Array ( [0] => stdClass Object ( [id] => 690869823035805696 [id_str] => 690869823035805696 [indices] => Array ( [0] => 39 [1] => 62 ) [media_url] => http://pbs.twimg.com/media/CZZ2cZQWEAAziYX.jpg [media_url_https] => https://pbs.twimg.com/media/CZZ2cZQWEAAziYX.jpg [url] => https://t.co/eVLeXgSE1g [display_url] => pic.twitter.com/eVLeXgSE1g [expanded_url] => http://twitter.com/rems0120/status/690869832330416128/photo/1 [type] => photo [sizes] => stdClass Object ( [thumb] => stdClass Object ( [w] => 150 [h] => 150 [resize] => crop ) [medium] => stdClass Object ( [w] => 600 [h] => 800 [resize] => fit ) [large] => stdClass Object ( [w] => 768 [h] => 1024 [resize] => fit ) [small] => stdClass Object ( [w] => 340 [h] => 453 [resize] => fit ) ) [source_status_id] => 690869832330416128 [source_status_id_str] => 690869832330416128 [source_user_id] => 2783584450 [source_user_id_str] => 2783584450 ) ) ) [extended_entities] => stdClass Object ( [media] => Array ( [0] => stdClass Object ( [id] => 690869823035805696 [id_str] => 690869823035805696 [indices] => Array ( [0] => 39 [1] => 62 ) [media_url] => http://pbs.twimg.com/media/CZZ2cZQWEAAziYX.jpg [media_url_https] => https://pbs.twimg.com/media/CZZ2cZQWEAAziYX.jpg [url] => https://t.co/eVLeXgSE1g [display_url] => pic.twitter.com/eVLeXgSE1g [expanded_url] => http://twitter.com/rems0120/status/690869832330416128/photo/1 [type] => photo [sizes] => stdClass Object ( [thumb] => stdClass Object ( [w] => 150 [h] => 150 [resize] => crop ) [medium] => stdClass Object ( [w] => 600 [h] => 800 [resize] => fit ) [large] => stdClass Object ( [w] => 768 [h] => 1024 [resize] => fit ) [small] => stdClass Object ( [w] => 340 [h] => 453 [resize] => fit ) ) [source_status_id] => 690869832330416128 [source_status_id_str] => 690869832330416128 [source_user_id] => 2783584450 [source_user_id_str] => 2783584450 ) ) ) [favorited] => [retweeted] => [possibly_sensitive] => [lang] => pt )
#############################################################################




 */