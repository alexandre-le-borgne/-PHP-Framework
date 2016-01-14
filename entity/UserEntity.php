<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 04/01/2016
 * Time: 13:58
 */
class UserEntity extends Entity
{
    private $id, $email, $authentification;

    public function getAuthentification()
    {
        return $this->authentification;
    }

    public function getPassword() {
        if($this->authentification == UserModel::AUTHENTIFICATION_BY_PASSWORD) {
            $password = new PasswordEntity($this->id);
            return $password->getPassword();
        }
        return false;
    }
}