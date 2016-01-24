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
            $db->execute('INSERT INTO followers (user, follower) VALUES (?, ?)', array($followedUser->getId(), $followerId));
            return true;
        }
        return false;
    }
}