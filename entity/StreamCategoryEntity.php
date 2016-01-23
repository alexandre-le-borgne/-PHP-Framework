<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 23/01/2016
 * Time: 16:59
 */
class StreamCategoryEntity extends Entity {

    private $id, $stream, $category, $streamType;

    public function persist()
    {
        $db = new Database();
        if ($this->id == null)
        {
            $req = 'INSERT INTO stream_category (stream, category, streamType) VALUES (?, ?, ?)';
            $db->execute($req, array($this->stream, $this->category, $this->streamType));
        }
        else
        {
            $req = 'UPDATE stream_category SET stream = ?, category = ?, streamType = ? WHERE id = ?';
            $db->execute($req, array($this->stream, $this->category, $this->streamType, $this->id));
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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }
}