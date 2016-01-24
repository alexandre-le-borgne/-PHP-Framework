<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 04/01/2016
 * Time: 13:58
 */

class UserEntity extends Entity
{
    private $id;
    private $authentification;
    private $username;
    private $email;
    private $birthDate;
    private $userKey;
    private $active;
    private $accountLevel;

    public function persist()
    {
        $db = new Database();
        if ($this->id == null)
        {
            $req = 'INSERT INTO accounts (authentification, username, email, userKey, active, accountLevel, picture) VALUES (?, ?, ?, ?, ?, ?, ?)';
            $db->execute($req, array($this->authentification, $this->username, $this->email, $this->userKey, $this->active, $this->accountLevel, $this->picture));
            $this->id = $db->lastInsertId();
        }
        else
        {
            $req = 'UPDATE accounts SET authentification = ?, username = ?, email = ?, userKey = ?, active = ?, accountLevel = ?, picture = ? WHERE id = ?';
            $db->execute($req, array($this->authentification, $this->username, $this->email, $this->userKey, $this->active, $this->accountLevel, $this->picture, $this->id));
        }
    }

    // GETTERS

    public function getAuthentification()
    {
        return $this->authentification;
    }
    public function getId()
    {
        return $this->id;
    }
    public function getUsername()
    {
        return $this->username;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function getBirthDate()
    {
        return $this->birthDate;
    }
    public function getUserKey()
    {
        return $this->userKey;
    }
    public function getActive()
    {
        return $this->active;
    }
    public function getAccountLevel()
    {
        return $this->accountLevel;
    }


    // SETTERS

    public function setAuthentification($authentification)
    {
        $this->authentification = $authentification;
    }
    public function setId($id)
    {
        $this->id = $id;
    }
    public function setUsername($username)
    {
        $this->username = $username;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;
    }
    public function setUserKey($userKey)
    {
        $this->userKey = $userKey;
    }
    public function setActive($active)
    {
        $this->active = $active;
    }
    public function setAccountLevel($accountLevel)
    {
        $this->accountLevel = $accountLevel;
    }
}