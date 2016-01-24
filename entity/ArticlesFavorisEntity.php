<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 24/01/2016
 * Time: 01:50
 */

class ArticlesFavorisEntity
{
    private $id, $account, $article;

    public function persist()
    {
        $db = new Database();
        if ($this->id == null)
        {
            $req = 'INSERT INTO articlesfavoris (account, article) VALUES (?, ?)';
            $db->execute($req, array($this->account, $this->article));
            $this->id = $db->lastInsertId();
        }
        else
        {
            $req = 'UPDATE articlesfavoris SET account = ?, article = ? WHERE id = ?';
            $db->execute($req, array($this->account, $this->article, $this->id));
        }
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @param mixed $account
     */
    public function setAccount($account)
    {
        $this->account = $account;
    }

    /**
     * @return mixed
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @param mixed $article
     */
    public function setArticle($article)
    {
        $this->article = $article;
    }
}