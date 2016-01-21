<?php

/**
 * Created by PhpStorm.
 * User: l14011190
 * Date: 14/01/16
 * Time: 09:30
 */
class EmailEntity extends Entity
{
    private $id, $server, $user, $password, $port, $firstUpdate;

    //Getters
    public function getId()
    {
        return $this->id;
    }

    public function getServer()
    {
        return $this->server;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getPort()
    {
        return $this->port;
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

    public function setServer($server)
    {
        $this->server = $server;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setPort($port)
    {
        $this->port = $port;
    }

    public function setFirstUpdate($firstUpdate)
    {
        $this->firstUpdate = $firstUpdate;
    }
}