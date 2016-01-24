<?php

/**
 * Created by PhpStorm.
 * User: j13000355
 * Date: 04/01/16
 * Time: 17:19
 */

require_once './vendor/abraham/twitteroauth/autoload.php';
require_once './vendor/nojimage/twitter-text-php/lib/Twitter/Autolink.php';

use Abraham\TwitterOAuth\TwitterOAuth;

class TwitterModel extends Model implements StreamModel
{
    const MIN_COUNT = 32;
    const MAX_MULTIPLICATOR = 16;
    const CONSUMER_KEY = "rC3gP2pji5zoKoGf4FlUYdvaa";
    const CONSUMER_SECRET = "TYIpFvcb9wR6SrpdxmMCPruiyJSPSDfJdLz6cAlNgqoyMcMq2j";

    private $twitter;
    private $db;

    public function getStreamById($id)
    {
        if (intval($id))
        {
            $db = new Database();
            $result = $db->execute('SELECT * FROM stream_twitter WHERE id = ?', array($id));
            $result->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'TwitterEntity');
            $fetch = $result->fetch();
            if ($fetch)
                return $fetch;
        }
        return null;
    }

    public function getStreamByChannel($channel)
    {
        $db = new Database();
        $req = 'SELECT * FROM stream_twitter WHERE channel = ?';
        $result = $db->execute($req, array($channel));
        $result->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'TwitterEntity');
        return $result->fetch();
    }

    public function createStream($channel, DateTime $firstUpdate)
    {
        $db = new Database();
        $fetch = $this->getStreamByChannel($channel);
        if (!($fetch))
        {
            $req = 'INSERT INTO stream_twitter (channel, firstUpdate, lastUpdate) VALUES (? , ?, now())';
            $db->execute($req, array($channel, date(Database::DATE_FORMAT, $firstUpdate->getTimestamp())));
        }
        else if ($firstUpdate->getTimestamp() < strtotime($fetch['firstUpdate']))
        {
            //On modifie le stream pour qu'il prenne en compte le debut plus tot
            $req = "UPDATE stream_twitter SET firstUpdate = ? WHERE channel = ?";
            $db->execute($req, array(date(Database::DATE_FORMAT, $firstUpdate->getTimestamp()), $channel));
        }
    }

    /**
     * Fonction de suppression d'un flux, verifie d'abord que plus personne ne suit ce flux
     */
    public function deleteStream(TwitterEntity $twitterEntity)
    {

    }


    /******************************************   CRON   *******************************************************/

    /**
     * La tache cron appelee tous les soirs, qui va recharger tous les flux twitters en BD et les mettre a jour.
     */
    public function cron()
    {
        $this->initTwitterOAuth();
        $this->db = new Database();

        /** 1) Recuperation de tous les flux twitter existants en BD */
        $result = $this->db->execute('SELECT * FROM stream_twitter');
        $result->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'TwitterEntity');
        $twitterStreams = $result->fetchAll();

        /** 2) Pour chaque flux, on appelle le cron correspondant a ce stream */
        foreach ($twitterStreams as $twitterEntity)
        {
            $this->streamCron($twitterEntity);
        }
    }

    /**
     * La tache cron pour un flux particulier (une TwitterEntity, donc une chaine avec ses dates)
     */
    public function streamCron(TwitterEntity $twitterEntity)
    {
        if ($this->db == null)
            $this->db = new Database();

        /** @var TwitterEntity $twitterEntity Pour l'autocompletion de l'IDE */
        /** @var ArticleEntity $firstArticle */
        /** @var ArticleEntity $lastArticle */
        /** On recupere le premier et le dernier tweet en BD de ce flux */
        $firstArticle = $this->getFirstArticle($twitterEntity);
        $lastArticle = $this->getLastArticle($twitterEntity);

        /** On recupere tous les tweets de maintenant a stream.firstUpdate. dans $articles, en enlevant ceux deja presents en BD*/
        $tweetsToInsert = $this->loadTweets($twitterEntity->getChannel(), $twitterEntity->getFirstUpdate(),
            $firstArticle->getArticleDate(), $lastArticle->getArticleDate());

        /** On ajoute en BD les articles a inserer */
        /** de plus, on les parse pour que les liens s'affichent */
        $autolink = Twitter_Autolink::create();


        $req = 'INSERT INTO article (title, content, articleDate, streamType, stream_id, url) VALUES (?,?,?,?,?,?)';
        foreach ($tweetsToInsert as $tweet)
        {
            $text1 = $autolink->autoLink($tweet->text);
            $imageLink = $this->getImageLink($tweet);

            $this->db->execute($req, array(
                $twitterEntity->getChannel(),
                $text1 . '<br/>' . $imageLink,
                date(Database::DATE_FORMAT, strtotime($tweet->created_at)),
                ArticleModel::TWITTER,
                $twitterEntity->getId(),
                $autolink->autoLink('@' . $twitterEntity->getChannel())));
        }

        /** On modifie en BD le lastUpdate du stream qu'on traite a now() */
        $this->db->execute('UPDATE stream_twitter SET lastUpdate = now()');
    }

    /**
     * Va charger tous les tweets jusqu'a firstUpdate en excluant ceux dans l'intervalle [firstDate, lastDate]
     * @param $channel la chaine correspondante
     * @param $firstUpdate la date depuis laquelle on veut les tweets
     * @param $firstDate la date de debut des tweets deja presents
     * @param $lastDate la date de fin des tweets deja presents
     * @return les tweets a inserer
     */
    private function loadTweets($channel, $firstUpdate, $firstDate, $lastDate)
    {
        /**
         * Comme indique dans l'api Twitter, quand on exclut les reponses, il aut en recuperer plus que prevu, car
         * il va d'abord recuperer $count tweets, puis enlever les reponses.
         * De plus, je vais devoir recupere un nombre de tweets arbitraires
         */

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

            if (!($tooMuchTweets)) //si l'on a rien, on a rien a recuperer
                return null;

            /** On verifie que la date du denrier tweet coincide avec la date $firstUpdate */
            if (strtotime(end($tooMuchTweets)->created_at) < strtotime($firstUpdate))//Alors on est bon, on a au moins recup ce qu'on veut
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

    private function getFirstArticle(TwitterEntity $twitterStream)
    {
        $result = $this->db->execute('SELECT * FROM article WHERE stream_id = ? AND streamType = ? ORDER BY articleDate ASC LIMIT 1',
            array($twitterStream->getId(), ArticleModel::TWITTER));
        $result->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'ArticleEntity');
        if ($articleEntity = $result->fetch())
            return $articleEntity;
        $articleEntity = new ArticleEntity();
        $articleEntity->setArticleDate(time());
        return $articleEntity;
    }

    private function getLastArticle(TwitterEntity $twitterStream)
    {
        $result = $this->db->execute('SELECT * FROM article WHERE stream_id = ? AND streamType = ? ORDER BY articleDate DESC LIMIT 1',
            array($twitterStream->getId(), ArticleModel::TWITTER));
        $result->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'ArticleEntity');
        if ($articleEntity = $result->fetch())
            return $articleEntity;
        $articleEntity = new ArticleEntity();
        $articleEntity->setArticleDate(time());
        return $articleEntity;
    }

    private function initTwitterOAuth()
    {
        /** Creation de l'objet TwitterOAuth qui me permet de recuperer les tweets */
        $oauth = new TwitterOAuth("rC3gP2pji5zoKoGf4FlUYdvaa", "TYIpFvcb9wR6SrpdxmMCPruiyJSPSDfJdLz6cAlNgqoyMcMq2j");
        $accesstoken = $oauth->oauth2('oauth2/token', ['grant_type' => 'client_credentials']);
        $this->twitter = new TwitterOAuth("rC3gP2pji5zoKoGf4FlUYdvaa",
            "TYIpFvcb9wR6SrpdxmMCPruiyJSPSDfJdLz6cAlNgqoyMcMq2j", null, $accesstoken->access_token);
    }

    private function getImageLink($tweet)
    {
        $imageLink = '';
        if (isset($tweet->extended_entities->media[0]->url, $tweet->extended_entities->media[0]->media_url))
            $imageLink = '<a href="' . $tweet->extended_entities->media[0]->url . '" target="_blank"><img src="' .
                $tweet->extended_entities->media[0]->media_url . '"></a>';
        return $imageLink;
    }
}