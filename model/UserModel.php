<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 04/01/2016
 * Time: 13:58
 */
class UserModel extends Model
{
    const ACCOUNT_LEVEL_USER = 0;
    const ACCOUNT_LEVEL_MODERATOR = 1;
    const ACCOUNT_LEVEL_ADMIN = 2;

    const AUTHENTIFICATION_BY_PASSWORD = 0;
    const ALREADY_USED_EMAIL = 1;
    const BAD_EMAIL_REGEX = 2;
    const CORRECT_EMAIL = 3;
    const AUTHENTIFICATION_BY_FACEBOOK = 4;

    public function getIdByNameOrEmail($nameOrEmail) {
        $db = new Database();
        $data = $db->execute("SELECT id FROM accounts WHERE username = ? OR email = ?", array($nameOrEmail, $nameOrEmail));
        $fetch = $data->fetch();
        if($fetch)
            return $fetch['id'];
        return false;
    }

    public function getById($id)
    {
        if (intval($id)) {
            $db = new Database();
            $result = $db->execute("SELECT * FROM accounts WHERE id = ?", array($id))->fetch();
            if($result) {
                $user = new UserEntity();
                $user->setId($result['id']);
                $user->setAccountLevel($result['accountLevel']);
                $user->setActive($result['active']);
                $user->setBirthDate($result['birthDate']);
                $user->setEmail($result['email']);
                $user->setUserKey($result['userKey']);
                $user->setUsername($result['username']);
                $user->setAuthentification($result['authentification']);
                return $user;
            }
        }
        return null;
    }

    public function getPassword(UserEntity $user)
    {
        if ($user->getAuthentification() == UserModel::AUTHENTIFICATION_BY_PASSWORD)
        {
            $password = new PasswordEntity($user);
            return $password->getPassword();
        }
        return false;
    }

    public function availableUser($username)
    {
        $db = new Database();
        $sql = "SELECT * FROM accounts WHERE username = ?";
        return $db->execute($sql, array($username));
    }

    public function availableEmail($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            return $this::BAD_EMAIL_REGEX;
        $db = new Database();
        $sql = "SELECT * FROM accounts WHERE email = ?";
        $result = $db->execute($sql, array($email));
        if (!($result->fetch())) //Resultat requete vide
            return $this::CORRECT_EMAIL;
        return $this::ALREADY_USED_EMAIL;
    }

    public function correctPwd($password)
    {
        return (6 <= strlen($password) && strlen($password) <= 20);
    }


    public function addUser($username, $email, $password, $birthDate)
    {
        $db = new Database();
        $key = Security::generateKey();
        $password = Security::encode($password);

        $db->execute("INSERT INTO accounts (username, email, authentification, birthDate, userKey) VALUES (?, ?, "
            . UserModel::AUTHENTIFICATION_BY_PASSWORD . ", ?, ?)", array($username, $email, $birthDate, $key));

        $id = $db->lastInsertId();

        $db->execute("INSERT INTO passwords (user, password) VALUES (?, ?)", array($id, $password));

        Mail::sendVerificationMail($username, $email, $key);
    }

    public function addFacebookUser($username, $email, $password, $birthDate)
    {
        $db = new Database();
        $key = Security::generateKey();
        $password = Security::encode($password);

        $db->execute("INSERT INTO accounts (username, email, authentification, birthDate) VALUES (?, ?, "
            . UserModel::AUTHENTIFICATION_BY_FACEBOOK . ", ?, ?)", array($username, $email, $birthDate));

        $id = $db->lastInsertId();

        $db->execute("INSERT INTO passwords (user, password) VALUES (?, ?)", array($id, $password));

        Mail::sendVerificationMail($username, $email, $key);
    }
}