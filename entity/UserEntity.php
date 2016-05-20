<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 04/01/2016
 * Time: 13:58
 */

class UserEntity extends PersistableEntity
{
    private $username;
    private $password;

    function getFields()
    {
        return array(
            'username' => $this->username,
            'password' => $this->password
        );
    }

    public final static function getModel()
    {
        return new UserModel('UserEntity');
    }
    
    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }
}