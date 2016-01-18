<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 18/01/2016
 * Time: 23:12
 */
class ArticleModel extends Model
{
    public function getById($id)
    {
        if (intval($id)) {
            $db = new Database();
            $result = $db->execute("SELECT * FROM article WHERE id = ?", array($id))->fetch();
            if ($result) {
                $article = new ArticleEntity();
                $article->setId($result['id']);
                $article->setTitle($result['title']);
                $article->setContent($result['content']);
                $article->setDate($result['date']);
                return $article;
            }
        }
        return null;
    }
}