<?php

/**
 * Created by PhpStorm.
 * User: l14011190
 * Date: 14/01/16
 * Time: 10:31
 */
class ArticleEntity
{
    private $id, $title, $content, $articleDate, $articleType, $url, $stream_id;

    public function persist(Database $db)
    {
        if ($this->id == null)
        {
            $req = 'INSERT INTO article (title, content, articleDate, articleType, url, stream_id) VALUES (?, ?, ?, ?, ?, ?)';
            $db->execute($req, array($this->title, $this->content, $this->articleDate,
                $this->articleType, $this->url, $this->stream_id));
        }
        else
        {
            $req = 'UPDATE article SET title = ?, content = ?, articleDate = ?, articleType = ?, url = ?, stream_id = ?';
            $db->execute($req, array($this->title, $this->content, $this->articleDate,
                $this->articleType, $this->url, $this->stream_id));
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getArticleDate()
    {
        return $this->articleDate;
    }

    public function getArticleType()
    {
        return $this->articleType;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getStreamId()
    {
        return $this->stream_id;
    }


    public function setId($id)
    {
        $this->id = $id;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function setArticleDate($articleDate)
    {
        $this->articleDate = $articleDate;
    }

    public function setArticleType($articleType)
    {
        $this->articleType = $articleType;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function setStreamId($stream_id)
    {
        $this->stream_id = $stream_id;
    }
}