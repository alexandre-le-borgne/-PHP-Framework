<?php

/**
 * Created by PhpStorm.
 * User: j14003626
 * Date: 14/01/16
 * Time: 09:49
 */
class TwitterEntity
{
    private $id;
    private $channel;
    private $lastUpdate;
    private $firstUpdate;

    public function persist()
    {
        $db = new Database();

        $firstUpdate = date(Database::DATE_FORMAT, strtotime($this->firstUpdate));
        $lastUpdate = date(Database::DATE_FORMAT, strtotime($this->lastUpdate));

        if ($this->id == null)
        {
            $req = 'INSERT INTO stream_twitter (channel, firstUpdate, lastUpdate) VALUES (?, ?, ?)';
            $data = array($this->channel, $firstUpdate, $lastUpdate);
            $db->execute($req, $data);
            $this->id = $db->lastInsertId();
        }
        else
        {
            $req = 'UPDATE stream_twitter SET channel = ?, firstUpdate = ?, lastUpdate = ? WHERE id = ?';
            $data = array($this->channel, $firstUpdate, $lastUpdate, $this->id);
            $db->execute($req, $data);
        }
    }

    //Getters
    public function getId()
    {
        return $this->id;
    }

    public function getChannel()
    {
        return $this->channel;
    }

    public function getLastUpdate()
    {
        return $this->lastUpdate;
    }

    public function getFirstUpdate()
    {
        return $this->firstUpdate;
    }


    //Setters
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setChannel($channel)
    {
        $this->channel = $channel;
    }

    public function setLastUpdate($lastUpdate)
    {
        $this->lastUpdate = $lastUpdate;
    }

    public function setFirstUpdate($firstUpdate)
    {
        $this->firstUpdate = $firstUpdate;
    }

    public function toString()
    {
        return $this->channel;
    }
}


/**
 *
 *
 * function __construct($id)
 * {
 * $this->id = $id;
 * if (is_numeric($id))
 * {
 * $db = new Database();
 * $req = "Select * From stream_twitter Where Id = ?";
 * $result = $db->excecute($req, array($id))->fetch();
 *
 * if ($result)
 * {
 * $this->id = $result['id'];
 * $this->channel = $result['channel'];
 * $this->lastUpdate = $result['lastUpdate'];
 * $this->firstUpdate = $result['firstUpdate'];
 * return;
 * }
 * }
 * throw new TraceableException("Flux twitter manquant");
 *
 */