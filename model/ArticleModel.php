<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 18/01/2016
 * Time: 23:12
 */
class ArticleModel extends Model
{

    const EMAIL = 0;
    const TWITTER = 1;
    const RSS = 2;


    public function getById($id)
    {
        if (intval($id)) {
            $db = new Database();
            $data = $db->execute("SELECT * FROM article WHERE id = ?", array($id));
            $data->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'ArticleEntity');
            return $data->fetch();
        }
        return null;
    }

    public function getByCategoryId($id) {
        if (intval($id)) {
            $db = new Database();
            $data = $db->execute("SELECT article.* FROM article JOIN stream_category ON article.stream_id = stream_category.stream AND article.streamType = stream_category.streamType WHERE stream_category.category = ?)", array($id));
            $data->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'ArticleEntity');
            return $data->fetchAll();
        }
        return null;
    }

    public function getArticlesByUserId($user, $start = 0, $len = 0)
    {
        if (is_numeric($user) && is_numeric($start) && is_numeric($len)) {
            $db = new Database();
            $req = "SELECT article.* FROM article JOIN stream_category ON article.stream_id = stream_category.stream AND article.streamType = stream_category.streamType WHERE stream_category.category IN (SELECT id FROM categories WHERE account = ?) ORDER BY articleDate DESC LIMIT ?, ?";
            var_dump($req);
            $data = $db->execute($req, array($user, $start, $len));
            var_dump($data->queryString);
            $data->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'ArticleEntity');
            return $data->fetchAll();
        }
        return null;
    }


    /*public function addArticle($title, $content, $date, $type, $url){
        $db = new Database();

        $url = $post->getLink();

        $req = "SELECT * FROM stream_rss WHERE url = ?";
        $result = $db->execute($req, array($url));

        if($result->fetch()){

        }
        else {
            $title = $post->getTitle();
            $content = $post->getText();

            $date = $post->getDate();

            $req = "INSERT INTO article (title, content, articleDate, streamType, url) VALUES (?, ?, ". ArticleModel::RSS  .", ?, ?)";
            $db->execute($req, array($title, $content, $date, $url));
        }

    }*/
}