<?php
/**
 * Created by PhpStorm.
 * User: l14011190
 * Date: 14/01/16
 * Time: 11:13
 */

class PasswordModel {
    public function getByUser(UserEntity $user) {
            $db = new Database();
            $result = $db->execute("SELECT * FROM passwords WHERE account = ?", array($user->getId()))->fetch();
        if($result) {
            $password = new PasswordEntity();
            $password->setId($result['id']);
            $password->setUser($result['user']);
            $password->setPassword($result['password']);
            return $password;
        }
        return null;
    }
}