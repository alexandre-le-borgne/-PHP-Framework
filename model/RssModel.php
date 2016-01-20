<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 30/12/2015
 * Time: 12:29
 */

class RssModel extends Model
{
    private $posts = array();
    private $url;


    private function resolveFile($url) {
        if (!preg_match('|^https?:|', $url))
            $feed_uri = $_SERVER['DOCUMENT_ROOT'] .'shared/xml/'. $url;
        else
            $feed_uri = $url;

        return $feed_uri;
    }

    private function summarizeText($summary) {
        $summary = strip_tags($summary);

        // Truncate summary line to 100 characters
        $max_len = 100;
        if (strlen($summary) > $max_len)
            $summary = substr($summary, 0, $max_len) . '...';

        return $summary;
    }

    /**
     * @return array
     */
    public function getPosts()
    {
        return $this->posts;
    }

    public function getById($id){
        return new RssEntity($id);
    }

    public function createStream($url, $firstUpdate){
        $db = new Database();
        $req = "SELECT * FROM stream_rss WHERE url = ?";
        $result = $db->execute($req, array($url));

        if(!$result->fetch()){
            $req = "INSERT INTO stream_rss (url, firstUpdate, lastUpdate) VALUES (? , ?, ?)";
            $db->execute($req, array($url, $firstUpdate, date("F j, Y, g:i a")));
        }
    }

    public function cron(){
        $db = new Database();

        $req = "SELECT * FROM stream_rss";
        $result = $db->execute($req);
        var_dump($result);
$i = 0;
        while($fetch = $result->fetch() && $i < 20) {
            var_dump($fetch['url']);
            $url = $this->resolveFile($fetch['url']);
            if ($x = simplexml_load_file($url)) {
                foreach ($x->channel->item as $item) {
                    $req = "SELECT * FROM article WHERE url = ?";
                    $result = $db->execute($req, array($item->link));
                    if(!$result->fetch()) {
                        $req = "INSERT INTO article (title, content, articleDate, articleType, url) VALUES (?, ?, " . ArticleModel::RSS . ", ?, ?)";
                        $db->execute($req, array($item->title, $item->description, strtotime($item->pubDate), $item->link));
                    }
                }
            }
        ++$i;
        }
    }

}