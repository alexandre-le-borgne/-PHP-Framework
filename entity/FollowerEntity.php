<?php

/**
 * Created by PhpStorm.
 * User: theo
 * Date: 24/01/16
 * Time: 19:01
 */
class FollowerEntity
{
    private $id, $user, $follower;

    public function persist()
    {
        $db = new Database();
        if ($this->id == null)
        {
            $db->execute('INSERT INTO followers (user, follower) VALUES (?, ?)',
                array($this->user, $this->follower));
            $this->id = $db->lastInsertId();
        }
        else
        {
            $req = 'UPDATE followers SET user = ?, follower = ? WHERE id = ?';
            $db->execute($req, array($this->user, $this->follower, $this->id));
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getFollower()
    {
        return $this->follower;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function setFollower($follower)
    {
        $this->follower = $follower;
    }
}