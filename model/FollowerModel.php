<?php

/**
 * Created by PhpStorm.
 * User: theo
 * Date: 24/01/16
 * Time: 19:20
 */
class FollowerModel extends Model
{
    public function follow($followedName, $followerId)
    {
        $db = new Database();
        $userModel = new UserModel();
        $followedUser = $userModel->getByNameOrEmail($followedName);
        if ($followedUser)
        {
            if (!$this->getFollowerLine($followedUser->getId(), $followerId))
                $db->execute('INSERT INTO followers (user, follower) VALUES (?, ?)', array($followedUser->getId(), $followerId));
            return true;
        }
        return false;
    }

    public function getFollowerLine($userId, $folloserId)
    {
        $db = new Database();
        $data = $db->execute("SELECT * FROM followers WHERE user = ? AND follower = ?", array($userId, $folloserId));
        $data->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'FollowerEntity');
        return $data->fetch();
    }

    public function getFollowersById($userId)
    {
        $db = new Database();
        $data = $db->execute(
            "SELECT * FROM followers JOIN accounts ON followers.follower = accounts.id  WHERE followers.user = ?",
            array($userId));
        $data->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'FollowerEntity');
        return $data->fetchAll();
    }

    public function getFollowingById($userId)
    {
        $db = new Database();
        $data = $db->execute(
            "SELECT * FROM followers JOIN accounts ON followers.follower = accounts.id  WHERE followers.follower = ?",
            array($userId));
        $data->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'FollowerEntity');
        return $data->fetchAll();
    }
}