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