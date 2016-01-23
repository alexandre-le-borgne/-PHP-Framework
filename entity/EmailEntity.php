<?php

/**
 * Created by PhpStorm.
 * User: l14011190
 * Date: 14/01/16
 * Time: 09:30
 */
class EmailEntity extends Entity
{
    private $id, $server, $account, $password, $port, $firstUpdate;

    public function persist()
    {
        $db = new Database();
        if ($this->id == null)
        {
            $req = 'INSERT INTO stream_email (server, account, password, port, firstUpdate) VALUES (?, ?, ?, ?, ?)';
            $db->execute($req, array($this->server, $this->account, $this->password, $this->port, $this->firstUpdate));
            $this->id = $db->lastInsertId();
        }
        else
        {
            $req = 'UPDATE stream_email SET server = ?, account = ?, password = ?, port = ?, firstUpdate = ? WHERE id = ?';
            $db->execute($req, array($this->server, $this->account, $this->password, $this->port, $this->firstUpdate, $this->id));
        }
    }

    //Getters
    public function getId()
    {
        return $this->id;
    }

    public function getServer()
    {
        return $this->server;
    }

    public function getAccount()
    {
        return $this->account;
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

    public function setAccount($account)
    {
        $this->account = $account;
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