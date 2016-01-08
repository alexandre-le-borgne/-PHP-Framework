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

    public function __construct($id)
    {
        if (intval($id))
        {
            $db = new Database();
            $result = $db->execute("SELECT * FROM accounts WHERE id = ?", array($id))->fetch();
            if($result) {
                $this->id = $result['id'];
                $this->email = $result['email'];
                $this->authentification = $result['authentification'];
            }
        } else
        {
            throw new TraceableException("L'id d'un utilisateur est attendu !");
        }
    }

    public function getAuthentification()
    {
        return $this->authentification;
    }

    public function getPassword() {
        if($this->authentification == 0) {
            $password = new PasswordEntity($this->id);
            return $password->getPassword();
        }
        return false;
    }
}