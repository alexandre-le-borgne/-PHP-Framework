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
    const MAX_MULTIPLICATOR = 16;
    const CONSUMER_KEY = "rC3gP2pji5zoKoGf4FlUYdvaa";
    const CONSUMER_SECRET = "TYIpFvcb9wR6SrpdxmMCPruiyJSPSDfJdLz6cAlNgqoyMcMq2j";

    private $twitter;

    public function cron()
    {
        //Premierement, on charge tous les streams. On recupere leur date firstUpdate, et lastUpdate.
        //Pour chaque stream:
        //{
        //    on recupere le 1er et le dernier article
        //    on recharge tous les articles en BD entre le firstUpdate et 1erArticle.date
        //    on recharge tous les articles en BD entre now et le dernierArticle.date
        //    on modifie en BD le lastUpdate a now();
        //}
        //
        //


        //todo finir pour le cron
        //Je recupere d'abord le token, afin de pouvoir demander les tweets
        $oauth = new TwitterOAuth("rC3gP2pji5zoKoGf4FlUYdvaa", "TYIpFvcb9wR6SrpdxmMCPruiyJSPSDfJdLz6cAlNgqoyMcMq2j");
        $accesstoken = $oauth->oauth2('oauth2/token', ['grant_type' => 'client_credentials']);

        //Je cree l'instance de TwitterOAuth, avec le token genere precedemment qui me permettra de recuperer les tweets
        $this->twitter = new TwitterOAuth("rC3gP2pji5zoKoGf4FlUYdvaa",
            "TYIpFvcb9wR6SrpdxmMCPruiyJSPSDfJdLz6cAlNgqoyMcMq2j", null, $accesstoken->access_token);
    }

    public function getStreamById($id)
    {
        if (intval($id)) {
            $db = new Database();
            $result = $db->execute("SELECT * FROM stream_twitter WHERE id = ?", array($id));
            $result->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'TwitterEntity');
            return $result->fetch();
        }
        return null;
    }

    public function createStream($channel, DateTime $firstUpdate)
    {
        $db = new Database();
        $req = 'SELECT * FROM stream_twitter WHERE channel = ?';
        $result = $db->execute($req, array($channel));
        $fetch = $result->fetch();
        if (!($fetch)) {
            $req = 'INSERT INTO stream_twitter (channel, firstUpdate, lastUpdate) VALUES (? , ?, now())';
            $db->execute($req, array($channel, $firstUpdate));
        } else if ($firstUpdate->getTimestamp() < strtotime($fetch['firstUpdate'])) {
            //On modifie le stream pour qu'il prenne en compte le debut plus tot
            $req = "UPDATE stream_twitter SET firstUpdate = ? WHERE channel = ?";
            $db->execute($req, array($firstUpdate, $channel));
        }
    }

    public function getTweets($channel, $excludeReplies, $count)
    {
        /**
         * Comme l'explique l'API twitter, quand on recupere des tweets avec exclude_replies (pas les reponses),
         * il va d'abord recuperer $count tweets, puis enlever les reponses. Donc pour avoir au final $count reponses,
         * je dois en recuperer plus que prevu, puis couper mon tableau
         */
        $tweets = array();
        $multiplicator = 2;

        while (count($tweets) != $count) {
            $tooMuchTweets = $this->twitter->get('statuses/user_timeline', [
                'screen_name' => $channel,
                'exclude_replies' => ($excludeReplies ? true : false),
                'count' => $count * $multiplicator
            ]);
            $multiplicator *= 2;

            $tweets = array_slice($tooMuchTweets, 0, $count);

            //Dans ce cas la, on suppose que l'on a suffisamment recupere de tweets, et l'utilisateur pourrait donc
            //avoir moins de $count tweets, donc pas de boucle infinie
            if ($multiplicator > self::MAX_MULTIPLICATOR)
                break;
        }

        return $tweets;
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
}*/