<?php

/**
 * Created by PhpStorm.
 * User: j13000355
 * Date: 04/01/16
 * Time: 17:19
 */

require_once './vendor/abraham/twitteroauth/autoload.php';
require_once './vendor/nojimage/twitter-text-php/lib/Twitter/Autolink.php';
require_once './app/util/time_to_.php';

use Abraham\TwitterOAuth\TwitterOAuth;


class TwitterModel extends Model implements StreamModel
{
    const MIN_COUNT = 32;
    const MAX_MULTIPLICATOR = 16;
    const CONSUMER_KEY = "rC3gP2pji5zoKoGf4FlUYdvaa";
    const CONSUMER_SECRET = "TYIpFvcb9wR6SrpdxmMCPruiyJSPSDfJdLz6cAlNgqoyMcMq2j";

    private $twitter;

    /** Tout ca c'est pour le cron */
    public function cron()
    {
        //Je recupere d'abord le token, afin de pouvoir demander les tweets
        $oauth = new TwitterOAuth("rC3gP2pji5zoKoGf4FlUYdvaa", "TYIpFvcb9wR6SrpdxmMCPruiyJSPSDfJdLz6cAlNgqoyMcMq2j");
        $accesstoken = $oauth->oauth2('oauth2/token', ['grant_type' => 'client_credentials']);

        //Je cree l'instance de TwitterOAuth, avec le token genere precedemment qui me permettra de recuperer les tweets
        $this->twitter = new TwitterOAuth("rC3gP2pji5zoKoGf4FlUYdvaa",
            "TYIpFvcb9wR6SrpdxmMCPruiyJSPSDfJdLz6cAlNgqoyMcMq2j", null, $accesstoken->access_token);
        //Voila, ma variable de classe est chargee, je peux l'utiliser pour le cron, pour charger les tweets.


        $db = new Database();

        /** 1) Recuperation de tous les flux twitter existants en BD */
        $result = $db->execute('SELECT * FROM stream_twitter');
        $result->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'TwitterEntity');
        $twitterStreams = $result->fetchAll();

        /** 2) Pour chaque flux */
        foreach ($twitterStreams as $twitterEntity)
        {
            /** @var TwitterEntity $twitterEntity Pour l'autocompletion de l'IDE */
            /** @var ArticleEntity $firstArticle */
            /** @var ArticleEntity $lastArticle */
            /** On recupere le premier et le dernier tweet en BD de ce flux */
            $firstArticle = $this->getFirstArticle($db, $twitterEntity);
            $lastArticle = $this->getLastArticle($db, $twitterEntity);
            $dateFirstArticle = new DateTime();
            $dateFirstArticle->setTimestamp(strtotime($firstArticle->getArticleDate()));
            $dateLastArticle = new DateTime();
            $dateLastArticle->setTimestamp(strtotime($lastArticle->getArticleDate()));
            $dateFirstUpdate = new DateTime();
            $dateFirstUpdate->setTimestamp(strtotime($twitterEntity->getFirstUpdate()));

            /** On recupere tous les tweets de maintenant a stream.firstUpdate. dans $articles, en enlevant ceux deja presents en BD*/
            $tweetsToInsert = $this->loadTweets($twitterEntity->getChannel(), $dateFirstUpdate,
                $dateFirstArticle, $dateLastArticle);

            /** On ajoute en BD les articles a inserer */
            /** de plus, on les parse pour que les liens s'affichent */
            $autolink = Twitter_Autolink::create();

            $req = 'INSERT INTO article (title, content, articleDate, articleType, url, stream_id) VALUES (?, ?, ?, ?, ?, ?)';
            foreach ($tweetsToInsert as $tweet)
            {
                $dateInsert = new DateTime();
                $dateInsert->setTimestamp(strtotime($tweet->created_at));

                $db->execute($req, array(
                    $autolink->autoLink('@' . $twitterEntity->getChannel()),
                    $autolink->autoLink($tweet->text),
                    $dateInsert->format(Database::DATE_FORMAT),
                    ArticleModel::TWITTER,
                    $tweet->url,
                    $twitterEntity->getId()));
            }

            /** On modifie en BD le lastUpdate du stream qu'on traite a now() */
            $db->execute('UPDATE stream_twitter SET lastUpdate = now()');
        }
    }

    /**
     * Va charger tous les tweets jusqu'a firstUpdate en excluant ceux dans l'intervalle [firstDate, lastDate]
     * @param $channel la chaine correspondante
     * @param DateTime $firstUpdate la date depuis laquelle on veut les tweets
     * @param DateTime $firstDate la date de debut des tweets deja presents
     * @param DateTime $lastDate la date de fin des tweets deja presents
     * @return les tweets a inserer
     */
    private function loadTweets($channel, DateTime $firstUpdate, DateTime $firstDate, DateTime $lastDate)
    {
        /**
         * Comme indique dans l'api Twitter, quand on exclut les reponses, il faut en recuperer plus que prevu, car
         * il va d'abord recuperer $count tweets, puis enlever les reponses.
         * De plus, je vais devoir recupere un nombre de tweets arbitraires
         */

        $tweets = array();
        $count = self::MIN_COUNT;
        $multiplicator = 2;
        $tooMuchTweets = array();

        /**
         * On va recuperer a chaque tour de boucle $count * $multiplicator tweets, puis couper (array_slice())
         * On verifie ensuite les dates, si c'est bon puis on charge en BD les tweets entre $firstD et $lastD
         */

        while ($multiplicator <= self::MAX_MULTIPLICATOR)
        {
            $tooMuchTweets = $this->twitter->get('statuses/user_timeline', [
                'screen_name' => $channel,
                'exclude_replies' => true,
                'count' => $count * $multiplicator
            ]);

            /** On verifie que la date du denrier tweet coincide avec la date $firstUpdate */
            if (strtotime(end($tooMuchTweet)->created_at) < strtotime($firstUpdate))//Alors on est bon, on a au moins recup ce qu'on veut
                break;

            //sinon, on continue la boucle pour recuperer plus de tweets

            $multiplicator *= 2;
        }

        //On le range du dernier au premier
        $tooMuchTweets = array_reverse($tooMuchTweets);
        $tweetsTruncated = array();
        /** Je trunc tous les tweets qui sont avant $firstUpdate */
        foreach ($tooMuchTweets as $tweet)
        {
            if (strtotime($tweet->created_at) >= strtotime($firstUpdate))
                $tweetsTruncated[] = $tweet;
        }
        unset($tooMuchTweets);

        $tweetsToInsert = array();
        /** 2.2.1) on enleve tous les articles de $articles qui sont deja presents en BD, donc entre firstA.date et lastA.date */
        foreach ($tweetsTruncated as $tweet)
        {
            $createdAt = strtotime($tweet->created_at);
            if ($createdAt < strtotime($firstDate) || $createdAt > strtotime($lastDate))
                $tweetsToInsert[] = $tweet;//On l'ajoute
        }
        unset ($tweetsTruncated);

        return $tweetsToInsert;
    }

    private function getFirstArticle(Database $db, TwitterEntity $twitterStream)
    {
        $result = $db->execute('SELECT * FROM article WHERE stream_id = ? ORDER BY articleDate ASC LIMIT 1',
            array($twitterStream->getId()));
        $result->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'ArticleEntity');
        if ($articleEntity = $result->fetch())
            return $articleEntity;
        return new ArticleEntity(time());
    }

    private function getLastArticle(Database $db, TwitterEntity $twitterStream)
    {
        $result = $db->execute('SELECT * FROM article WHERE stream_id = ? ORDER BY articleDate DESC LIMIT 1',
            array($twitterStream->getId()));
        $result->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'ArticleEntity');
        if ($articleEntity = $result->fetch())
            return $articleEntity;
        return new ArticleEntity(time());//Si n'existe pas, on dit que l'on recuperera jusqua cet article
    }

    /** Fin du tout ca pour le cron */


    public
    function getStreamById($id)
    {
        if (intval($id))
        {
            $db = new Database();
            $result = $db->execute("SELECT * FROM stream_twitter WHERE id = ?", array($id));
            $result->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'TwitterEntity');
            return $result->fetch();
        }
        return null;
    }

    public
    function createStream($channel, DateTime $firstUpdate)
    {
        $db = new Database();
        $req = 'SELECT * FROM stream_twitter WHERE channel = ?';
        $result = $db->execute($req, array($channel));
        $fetch = $result->fetch();
        if (!($fetch))
        {
            $req = 'INSERT INTO stream_twitter (channel, firstUpdate, lastUpdate) VALUES (? , ?, now())';
            $db->execute($req, array($channel, $firstUpdate->format(Database::DATE_FORMAT)));
        }
        else if ($firstUpdate->getTimestamp() < strtotime($fetch['firstUpdate']))
        {
            //On modifie le stream pour qu'il prenne en compte le debut plus tot
            $req = "UPDATE stream_twitter SET firstUpdate = ? WHERE channel = ?";
            $db->execute($req, array($firstUpdate->format(Database::DATE_FORMAT), $channel));
        }
    }
}

/*class TwitterRSSStream
{

    private function parse($text)
    {
        $text = preg_replace('#http://[a-z0-9._/-]+#i', '<a href="$0">$0</a>', $text);
        $text = preg_replace('#@([a-z0-9_]+)#i', '@<a href="http://twitter.com/$1">$1</a>', $text);
        $text = preg_replace('# \#([a-z0-9_-]+)#i', ' #<a href="http://search.twitter.com/search?q=%23$1">$1</a>', $text);
        return $text;
    }

    public function TwitterStream($user)
    {
        $count = 5;
        $date_format = 'd M Y, H:i:s';

        $url = 'https://api.twitter.com/1/statuses/user_timeline.json?include_entities=true&include_rts=true&screen_name=' . $user . '&count=' . $count;
        $oXML = simplexml_load_file($url);
        echo '<ul>';
        foreach ($oXML->status as $oStatus) {
            $datetime = date_create($oStatus->created_at);
            $date = date_format($datetime, $date_format) . "\n";
            echo '<li>' . parse(utf8_decode($oStatus->text));
            echo ' (<a href="http://twitter.com/' . $user . '/status/' . $oStatus->id_str . '">' . $date . '</a>)</li>';
        }
        echo '</ul>';
    }
}







public function getTweets($channel, $excludeReplies, $count)
    {
        /**
         * Comme l'explique l'API twitter, quand on recupere des tweets avec exclude_replies (pas les reponses),
         * il va d'abord recuperer $count tweets, puis enlever les reponses. Donc pour avoir au final $count reponses,
         * je dois en recuperer plus que prevu, puis couper mon tableau
         *
         * $tweets = array();
         * $multiplicator = 2;
         *
         * while (count($tweets) != $count)
         * {
         * $tooMuchTweets = $this->twitter->get('statuses/user_timeline', [
         * 'screen_name' => $channel,
         * 'exclude_replies' => ($excludeReplies ? true : false),
         * 'count' => $count * $multiplicator
         * ]);
         * $multiplicator *= 2;
         *
         * $tweets = array_slice($tooMuchTweets, 0, $count);
         *
         * //Dans ce cas la, on suppose que l'on a suffisamment recupere de tweets, et l'utilisateur pourrait donc
         * //avoir moins de $count tweets, donc pas de boucle infinie
         * if ($multiplicator > self::MAX_MULTIPLICATOR)
         * break;
         * }
         *
         * return $tweets;
}














*/