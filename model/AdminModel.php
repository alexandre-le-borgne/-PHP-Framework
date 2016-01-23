<?php

/**
 * Created by PhpStorm.
 * User: j14003626
 * Date: 20/01/16
 * Time: 11:27
 */
class AdminModel extends Model
{
    public function getAllUsers()
    {
        $userModel = new UserModel();
        $database = new Database();

        $allId = $this->getAllId($database);
        $allUsers = array();

        foreach ($allId as $id):
            array_push($allUsers, $userModel->getById($id));
        endforeach;

        return $allUsers;
    }

    public function deleteUser($id)
    {
        $db= new Database();
        $db->execute('DELETE FROM accounts WHERE id = ?', array($id));
        $db->execute('DELETE FROM passwords WHERE account = ?', array($id));
        $db->execute('DELETE FROM articlesfavoris WHERE account = ?', array($id));
        $db->execute('DELETE FROM categories WHERE account = ?', array($id));
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