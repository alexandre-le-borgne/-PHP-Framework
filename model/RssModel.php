<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 30/12/2015
 * Time: 12:29
 */
class RssModel extends Model implements StreamModel
{
    private $posts = array();
    private $db;

    public function resolveFile($url)
    {
        if (!preg_match('|^https?:|', $url))
            $feed_uri = $_SERVER['DOCUMENT_ROOT'] . 'shared/xml/' . $url;
        else
            $feed_uri = $url;
        return $feed_uri;
    }

    private function summarizeText($summary)
    {
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

    public function getStreamById($id)
    {
        if (is_numeric($id))
        {
            $db = new Database();
            $data = $db->execute("SELECT * FROM stream_rss WHERE id = ?", array($id));
            $data->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'RssEntity');
            return $data->fetch();
        }
        return null;
    }

    public function getByUserId($id){
        if(is_numeric($id)) {
            $db = new Database();
            $data = $db->execute("SELECT DISTINCT stream_rss.* FROM stream_rss JOIN stream_category ON stream_rss.id = stream_category.stream AND stream_category.streamType = '".ArticleModel::RSS."' JOIN categories ON stream_category.category = categories.id WHERE categories.account = ?", array($id));
            $data->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'RssEntity');
            return $data->fetchAll();
        }
        return null;
    }

    public function getStreamByUrl($url){
        $db = new Database();
        $req = 'SELECT * FROM stream_rss WHERE url = ?';
        $result = $db->execute($req, array($url));
        $result->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'TwitterEntity');
        return $result->fetch();
    }

    public function createStream($url, $firstUpdate)
    {
        $db = new Database();

        $result = $this->getStreamByUrl($url);

        if ($result)
        {   //Si existe deja, et nouvelle date plus ancienne, alors on modifie le firstUpdate a la nouvelle date donnee
            if (strtotime($firstUpdate) < strtotime($result->getFirstUpdate()))
                $db->execute('UPDATE stream_rss SET firstUpdate = ? WHERE url = ?', array(date(Database::DATE_FORMAT, strtotime($firstUpdate)), $url));
            return $result;
        }
        else
        {   //Sinon, on cree le flux
            $twitterEntity = new RssEntity();
            $twitterEntity->setUrl($url);
            $twitterEntity->setFirstUpdate($firstUpdate);
            $twitterEntity->setLastUpdate(date(Database::DATE_FORMAT));
            $twitterEntity->persist();
            return $twitterEntity;
        }
    }


    public function cron()
    {
        $db = new Database();
        $result = $db->execute('SELECT * FROM stream_rss');
        $result->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'RssEntity');
        $rssStreams = $result->fetchAll();

        /** @var EmailEntity $emailEntity */
        foreach ($rssStreams as $rssEntity)
        {
            $this->streamCron($rssEntity);
        }
    }

    public function streamCron($rssEntity)
    {
        if ($this->db == null)
            $this->db = new Database();

        $firstRss = $this->getFirstRssArticle($rssEntity);

        $stream_id = $rssEntity->getId();
        $url = $rssEntity->getUrl();
        $x = simplexml_load_file($url);

        if (!$firstRss)
        {

            foreach ($x->channel->item as $item)
            {
                $base = $item->articleDate;
                $req = "INSERT INTO article (title, content, articleDate, streamType, url, stream_id) VALUES (?, ?, ?," . ArticleModel::RSS . ",  ?, ?)";
                $this->db->execute($req, array($item->title, $item->description, date(Database::DATE_FORMAT, strtotime($base)), $item->link, $stream_id));
            }
        }
        $update = "UPDATE stream_rss SET lastUpdate = now() WHERE Id = ?";
        $this->db->execute($update, array($stream_id));
    }

    private function getFirstRssArticle(RssEntity $rssEntity)
    {
        $db = new Database();
        $result = $db->execute('SELECT * FROM article WHERE stream_id = ? AND streamType = ? ORDER BY articleDate ASC LIMIT 1',
            array($rssEntity->getId(), ArticleModel::RSS));
        $result->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'ArticleEntity');
        return $result->fetch();
    }
}