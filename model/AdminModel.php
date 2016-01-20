<?php

/**
 * Created by PhpStorm.
 * User: j14003626
 * Date: 20/01/16
 * Time: 11:27
 */
class AdminModel extends Model
{
    function getAllUsers()
    {
        $userModel = new UserModel();
        $database = new Database();

        $allId = $this->getAllId($database);
        $allUsers = array();

        foreach ($allId as $id):
            array_push($allUsers, $userModel->getById($id));
        endforeach;

        return ['users' => $allUsers];
    }

    private function getAllId(Database $database)
    {
        $query = 'SELECT id FROM accounts';
        $result = $database->execute($query);
        $allId = array();

        $fetch = $result->fetch();
        while ($fetch)
        {
            array_push($allId, $fetch['id']);
            $fetch = $result->fetch();
        }

        return $allId;
    }
}