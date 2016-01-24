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
        if (is_numeric($id)) {
            $db = new Database();
            $data = $db->execute("SELECT DISTINCT * FROM article WHERE id = ?", array($id));
            $data->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'ArticleEntity');
            return $data->fetch();
        }
        return null;
    }

    public function getByCategoryId($id) {
        if (is_numeric($id)) {
            $db = new Database();
            $data = $db->execute("SELECT DISTINCT article.* FROM article JOIN stream_category ON article.stream_id = stream_category.stream AND article.streamType = stream_category.streamType WHERE stream_category.category = ?)", array($id));
            $data->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'ArticleEntity');
            return $data->fetchAll();
        }
        return null;
    }

    public function getArticlesByUserId($user, $start = 0, $len = 0)
    {
        if (is_numeric($user) && is_numeric($start) && is_numeric($len)) {
            $db = new Database();
            $req = "SELECT DISTINCT article.* FROM article JOIN stream_category ON article.stream_id = stream_category.stream AND article.streamType = stream_category.streamType WHERE stream_category.category IN (SELECT id FROM categories WHERE account = ?) ORDER BY articleDate DESC LIMIT $start, $len";
            $data = $db->execute($req, array($user));
            $data->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'ArticleEntity');
            return $data->fetchAll();
        }
        return null;
    }

    public function userHasStream($user, $stream, $type) {
        var_dump($user);
        echo $stream."ùù".$type;
        if (is_numeric($user) && is_numeric($stream) && is_numeric($type)) {
            echo $stream.$type;
            $db = new Database();
            $req = "SELECT * FROM stream_category JOIN categories ON stream_category.category = categories.id WHERE categories.account = ? AND stream_category.stream = ? AND stream_category.streamType = ?";
            $data = $db->execute($req, array($user, $stream, $type));
            var_dump($data->fetch());
            if($data->fetch())
                return true;
        }
        return false;
    }

    public function getArticlesFavorisByUserId($user, $start = 0, $len = 0)
    {
        if (is_numeric($user) && is_numeric($start) && is_numeric($len)) {
            $db = new Database();
            $req = "SELECT DISTINCT article.* FROM article JOIN articlesfavoris ON article.id = articlesfavoris.article WHERE articlesfavoris.account = ? ORDER BY articleDate DESC LIMIT $start, $len";
            $data = $db->execute($req, array($user));
            $data->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'ArticleEntity');
            return $data->fetchAll();
        }
        return null;
    }

    public function getArticlesByCategoryId($category, $start = 0, $len = 0)
    {
        if (is_numeric($category) && is_numeric($start) && is_numeric($len)) {
            $db = new Database();
            $req = "SELECT DISTINCT article.* FROM article JOIN stream_category ON article.stream_id = stream_category.stream AND article.streamType = stream_category.streamType WHERE stream_category.category = ? ORDER BY articleDate DESC LIMIT $start, $len";
            $data = $db->execute($req, array($category));
            $data->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'ArticleEntity');
            return $data->fetchAll();
        }
        return null;
    }

    public function getArticlesByStreamTypeAndId($type, $id, $start = 0, $len = 0)
    {
        if (is_numeric($type) && is_numeric($id) && is_numeric($start) && is_numeric($len))
        {
            $db = new Database();
            switch ($type)
            {
                case ArticleModel::RSS:
                    $req = "SELECT DISTINCT article.* FROM article WHERE article.stream_id = ? AND article.streamType = ? ORDER BY articleDate DESC LIMIT $start, $len";
                    $data = $db->execute($req, array($id, ArticleModel::RSS));
                    break;
                case ArticleModel::TWITTER:
                    $req = "SELECT DISTINCT article.* FROM article WHERE article.streamType = ? AND article.streamType = ?  ORDER BY articleDate DESC LIMIT $start, $len";
                    $data = $db->execute($req, array($id, ArticleModel::TWITTER));
                    break;
                case ArticleModel::EMAIL:
                    $req = "SELECT DISTINCT article.* FROM article WHERE article.streamType = ? AND article.streamType = ? ORDER BY articleDate DESC LIMIT $start, $len";
                    $data = $db->execute($req, array($id, ArticleModel::EMAIL));
                    break;
                default:
                    $this->redirectToRoute('index');
                    return;
            }
            $data->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'ArticleEntity');
            return $data->fetchAll();
        }
        return null;
    }

    public function getIdOfFavoris($id) {
        $favoris = array();
        if (is_numeric($id)) {
            $db = new Database();
            $req = "SELECT DISTINCT article.id FROM article JOIN articlesfavoris ON article.id = articlesfavoris.article WHERE articlesfavoris.account = ?";
            $data = $db->execute($req, array($id));
            while($result = $data->fetch()) {
                $favoris[] = $result['id'];
            }
        }
        return $favoris;
    }

    public function removeArticleFromFavoris($account, $article) {
        if(is_numeric($article)) {
            $db = new Database();
            $db->execute("DELETE FROM articlesfavoris WHERE account = ? AND article = ?", array($account, $article));
        }
    }

    public function getArticleFromFavoris($account, $article) {
        $db = new Database();
        $data = array($account, $article);
        $data = $db->execute("SELECT * FROM articlesfavoris WHERE account = ? AND article = ?", $data);
        $data->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'ArticleEntity');
        return $data->fetch();
    }

    public function getArticleFromBlog($account, $article) {
        $db = new Database();
        $data = array($account, $article);
        $data = $db->execute("SELECT * FROM blog WHERE account = ? AND article = ?", $data);
        $data->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'ArticleEntity');
        return $data->fetch();
    }

    public function removeArticleFromBlog($account, $article) {
        if(is_numeric($article)) {
            $db = new Database();
            $db->execute("DELETE FROM blog WHERE account = ? AND article = ?", array($account, $article));
        }
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