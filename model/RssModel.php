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

    public function __construct($url)
    {
        $url = $this->resolveFile($url);
        if (!($x = simplexml_load_file($url)))
            throw new TraceableException("Le flux '$url' n'a pas pu être parsé en tant que flux RSS.");

        foreach ($x->channel->item as $item)
        {
            $this->posts[] = new PostModel($item->pubDate, strtotime($item->pubDate), $item->link, $item->title,
                                      $item->description, $this->summarizeText($item->description));
        }
    }

    private function resolveFile($url) {
        if (!preg_match('|^https?:|', $url))
            $feed_uri = $_SERVER['DOCUMENT_ROOT'] .'/shared/xml/'. $url;
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

    public function addRss($post){
        $db = new Database();

        $url = $post->getLink();

        $req = "SELECT * FROM stream_rss WHERE url = '$url'";
        $result = $db->execute($req);

        if($result->fetch()){

        }
        else {
            $title = $post->getTitle();
            $content = $post->getText();

            $date = $post->getDate();

            $req = "INSERT INTO article (title, content, articleDate, articleType, url) VALUES (?, ?, ". ArticleModel::RSS  .", ?, ?)";
            $db->execute($req, array($title, $content, $date, $url));
        }

    }

}