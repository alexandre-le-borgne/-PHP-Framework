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
            $this->id = $db->lastInsertId();
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
    public function getStream()
    {
        return $this->stream;
    }

    /**
     * @param mixed $stream
     */
    public function setStream($stream)
    {
        $this->stream = $stream;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getStreamType()
    {
        return $this->streamType;
    }

    /**
     * @param mixed $streamType
     */
    public function setStreamType($streamType)
    {
        $this->streamType = $streamType;
    }
}