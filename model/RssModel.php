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


    public function getStreamById($id){
        return new RssEntity($id);
    }


    public function createStream($url,DateTime $firstUpdate){
        $db = new Database();
        $req = "SELECT * FROM stream_rss WHERE url = ?";
        $result = $db->execute($req, array($url));
        if(!($fetch = $result->fetch())){
            $req = 'INSERT INTO stream_rss (url, firstUpdate, lastUpdate) VALUES (? , ?, now())';
            $db->execute($req, array($url, $firstUpdate->format(Database::DATE_FORMAT)));
        }
        if($fetch['firstUpdate'] < $firstUpdate){
            $req = 'UPDATE stream_rss SET firstUpdate = ? WHERE url = ?';
            $db->execute($req, array($firstUpdate->format(Database::DATE_FORMAT), $url));
        }
    }

    public function cron(){
        /*
         * DateTime $firstUpdate
         * DateTime $lastUpdate
         */
        $db = new Database();
        $req = "SELECT * FROM stream_rss";
        $result = $db->execute($req);
        $result->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'RssEntity');

        $rssEntityArray = $result->fetchAll();

        foreach($rssEntityArray as $rssEntity) {
                /** @var RssEntity $fetch */
            $stream_id = $rssEntity->getId();
            $streamFirst = $rssEntity->getFirstUpdate();
            $streamLast = $rssEntity->getLastUpdate();
            $url = $rssEntity->getUrl();
            $x = simplexml_load_file($url);
            $req = "SELECT Min(articleDate) as minDate FROM article WHERE stream_id = ?";
            $result = $db->execute($req, array($stream_id));
            $fetch = $result->fetch();
            $minDate = $fetch['minDate']; //date du 1er article du stream
            $req = "SELECT * FROM article WHERE stream_id = ? AND articleDate BETWEEN ? and ?";
            $result = $db->execute($req, array($stream_id, $streamFirst, $minDate));
            $result->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'ArticleEntity');


            if(!$verif = $result->fetch()) {

                //$req = "SELECT content FROM article WHERE stream_id = ?";
                foreach ($x->channel->item as $item) {
                    $cont = $verif->getTitle();
                    echo 'first';
                    if ($item->title != $cont) {
                        echo 'insert first';
                        $base = $item->pubDate;
                        $req = "INSERT INTO article (title, content, articleDate, articleType, url, stream_id) VALUES (?, ?, ?," . ArticleModel::RSS . ",  ?, ?)";
                        $db->execute($req, array($item->title, $item->description, date(Database::DATE_FORMAT, strtotime($base)), $item->link, $stream_id));
                    }
                }
            }//while
            $req = "SELECT Max(articleDate) as maxDate FROM article WHERE stream_id = ?";
            $result = $db->execute($req, array($stream_id))->fetch();
            $maxDate = DateTime::createFromFormat('j-m-y', $result['maxDate']); //derniere date
            $req = "SELECT * FROM article WHERE stream_id = ? AND articleDate BETWEEN ? and ?";
            $result = $db->execute($req, array($stream_id, $maxDate, $streamLast));
            $result->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'ArticleEntity');
            if(!$verif = $result->fetch()) {
                //$req = "SELECT content FROM article WHERE stream_id = ?";
                foreach ($x->channel->item as $item) {
                    $cont = $verif->getTitle();
                    echo 'last';
                    if ($item->title != $cont) {
                        echo 'insert last';
                        $base = $item->pubDate;
                        $req = "INSERT INTO article (title, content, articleDate, articleType, url, stream_id) VALUES (?, ?, ?," . ArticleModel::RSS . ",  ?, ?)";
                        $db->execute($req, array($item->title, $item->description, date(Database::DATE_FORMAT, strtotime($base)), $item->link, $stream_id));
                    }
                }
            }//while
            $update = "UPDATE stream_rss SET lastUpdate = now() WHERE Id = ?";
            $db->execute($update, array($stream_id));
        }
    }
}