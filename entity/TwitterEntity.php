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
}


/**
 *
 *
 * function __construct($id)
 * {
 * $this->id = $id;
 * if (intval($id))
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