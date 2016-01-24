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

    private function resolveFile($url)
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
        return new RssEntity($id);
        //Todo recoder ça avec autre chose que tes fesses julien stp
    }

    public function createStream($url, DateTime $firstUpdate)
    {
        $db = new Database();
        $req = "SELECT * FROM stream_rss WHERE url = ?";
        $result = $db->execute($req, array($url));
        if (!($fetch = $result->fetch()))
        {
            $req = 'INSERT INTO stream_rss (url, firstUpdate, lastUpdate) VALUES (? , ?, now())';
            $db->execute($req, array($url, $firstUpdate->format(Database::DATE_FORMAT)));
        }
        if ($fetch['firstUpdate'] < $firstUpdate)
        {
            $req = 'UPDATE stream_rss SET firstUpdate = ? WHERE url = ?';
            $db->execute($req, array($firstUpdate->format(Database::DATE_FORMAT), $url));
        }
    }

    public function cron()
    {
        /*
         * DateTime $firstUpdate
         * DateTime $lastUpdate
         */
        $db = new Database();
        $req = "SELECT * FROM stream_rss";
        $result = $db->execute($req);
        $result->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'RssEntity');
        $rssEntityArray = $result->fetchAll();
        foreach ($rssEntityArray as $rssEntity)
        {
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
            $result = $db->execute($req, array($stream_id, $minDate, $streamFirst));
            foreach ($x->channel->item as $item)
            {
                //$req = "SELECT content FROM article WHERE stream_id = ?";
                if ($verif = $result->fetch())
                {
                    $cont = $verif['articleDate'];
                    if ($item->articleDate != $cont)
                    {
                        $base = $item->pubDate;
                        $req = "INSERT INTO article (title, content, articleDate, streamType, url, stream_id) VALUES (?, ?, ?," . ArticleModel::RSS . ",  ?, ?)";
                        $db->execute($req, array($item->title, $item->description, date(Database::DATE_FORMAT, strtotime($base)), $item->link, $stream_id));
                    }
                }
            }//while
            $req = "SELECT Max(articleDate) as maxDate FROM article WHERE stream_id = ?";
            $result = $db->execute($req, array($stream_id))->fetch();
            $maxDate = DateTime::createFromFormat('j-m-y', $result['maxDate']); //derniere date
            $req = "SELECT * FROM article WHERE stream_id = ? AND articleDate BETWEEN ? and ?";
            $result = $db->execute($req, array($stream_id, $maxDate, $streamLast));
            foreach ($x->channel->item as $item)
            {
                //$req = "SELECT content FROM article WHERE stream_id = ?";
                if (!$verif = $result->fetch())
                {
                    $cont = $verif['articleDate'];
                    if ($item->articleDate != $cont)
                    {
                        $base = $item->pubDate;
                        $req = "INSERT INTO article (title, content, articleDate, streamType, url, stream_id) VALUES (?, ?, ?," . ArticleModel::RSS . ",  ?, ?)";
                        $db->execute($req, array($item->title, $item->description, date(Database::DATE_FORMAT, strtotime($base)), $item->link, $stream_id));
                    }
                }
            }//while
            $update = "UPDATE stream_rss SET lastUpdate = now() WHERE Id = ?";
            $db->execute($update, array($stream_id));
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
            $firstRss = $this->getFirstArticle($rssEntity);
            $lastRss = $this->getLastArticle($rssEntity);
            $connection = $this->connect($emailEntity->getServer(), $emailEntity->getPort(), $emailEntity->getAccount(), $emailEntity->getPassword());
            $stream = $connection['conn'];
            $date = date("d M Y", strtotime($emailEntity->getFirstUpdate()));
            $emails = imap_search($stream, 'SINCE "' . $date . '"');

            $articles = array();
            if (count($emails))
            {
                foreach ($emails as $email)
                {
                    $overview = imap_fetch_overview($stream, $email, 0);
                    $structure = imap_fetchstructure($stream, $overview[0]->uid, FT_UID);
                    switch ($structure->encoding)
                    {
                        case 4:
                            $body = imap_qprint(imap_fetchbody($stream, imap_msgno($stream, $overview[0]->uid), 1));
                            break;
                        case 3:
                            $body = base64_decode(imap_fetchbody($stream, imap_msgno($stream, $overview[0]->uid), 1));
                            break;
                        case 1:
                            $body = imap_qprint(imap_fetchbody($stream, imap_msgno($stream, $overview[0]->uid), 1));
                            break;
                        case 0:
                            $body = quoted_printable_decode(imap_fetchbody($stream, imap_msgno($stream, $overview[0]->uid), 1));
                            break;
                        default:
                            $body = imap_fetchbody($stream, imap_msgno($stream, $overview[0]->uid), 1);
                    }
                    $article = new ArticleEntity();
                    $subject = isset($overview[0]->subject) ? $this->decode_imap_text($overview[0]->subject) : 'Sans object';
                    $article->setTitle($structure->encoding . "$$$" . $subject . ' - ' . $this->decode_imap_text($overview[0]->from));
                    $article->setContent($this->getBody($overview[0]->uid, $stream));
                    $article->setArticleDate(date(Database::DATE_FORMAT, strtotime($overview[0]->date)));
                    $article->setStreamType(ArticleModel::EMAIL);
                    $article->setStreamId($emailEntity->getId());
                    $article->setUrl('');
                    $articles[] = $article;
                }
            }

            /** @var ArticleEntity $article */
            foreach ($articles as $article)
            {
                if (!$firstEmail || strtotime($article->getArticleDate()) < strtotime($firstEmail->getArticleDate())
                    || !$lastEmail || strtotime($article->getArticleDate()) > strtotime($lastEmail->getArticleDate())
                )
                {
                    $article->persist();
                }
            }
        }
        return $articles;
    }

    private function getFirstArticle(RssEntity $rssEntity)
    {
        $db = new Database();
        $result = $db->execute('SELECT * FROM article WHERE stream_id = ? AND streamType = ? ORDER BY articleDate ASC LIMIT 1',
            array($rssEntity->getId(), ArticleModel::RSS));
        $result->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'ArticleEntity');
        return $result->fetch();
    }

    private function getLastArticle(RssEntity $rssEntity)
    {
        $db = new Database();
        $result = $db->execute('SELECT * FROM article WHERE stream_id = ? AND streamType = ? ORDER BY articleDate DESC LIMIT 1',
            array($rssEntity->getId(), ArticleModel::RSS));
        $result->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'ArticleEntity');
        return $result->fetch();
    }
}