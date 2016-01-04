<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 04/01/2016
 * Time: 15:56
 */
class PasswordEntity
{
    private $id, $user, $password;

    public function __construct($user)
    {
        if(intval($user)) {
            $db = new Database();
            $result = $db->execute("SELECT * FROM passwords WHERE account = ?", $user)->fetch();
            $this->id = $result['id'];
            $this->user = $result['user'];
            $this->password = $result['password'];
        }
        else {
            throw new TraceableException("L'id d'un utilisateur utilisant un mot de passe est attendu !");
        }
    }

    public function getPassword() {
        return $this->password;
    }
}