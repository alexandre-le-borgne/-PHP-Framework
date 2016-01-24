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
    const AUTHENTIFICATION_BY_EXTERNAL = 1;

    const ALREADY_USED_EMAIL = 1;
    const BAD_EMAIL_REGEX = 2;
    const CORRECT_EMAIL = 3;

    public function getByNameOrEmail($nameOrEmail)
    {
        $db = new Database();
        $data = $db->execute("SELECT * FROM accounts WHERE username = ? OR email = ?", array($nameOrEmail, $nameOrEmail));
        $data->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'UserEntity');
        $data = $data->fetch();
        return $data;
    }

    public function getById($id)
    {
        if (intval($id))
        {
            $db = new Database();
            $result = $db->execute("SELECT * FROM accounts WHERE id = ?", array($id));
            $result->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'UserEntity');
            return $result->fetch();
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

    public function addExternalUser($username, $email)
    {
        $db = new Database();
        $db->execute("INSERT INTO accounts (username, email, authentification, active) VALUES (?, ?, "
            . UserModel::AUTHENTIFICATION_BY_EXTERNAL . ", 1)", array($username, $email));
        return $db->lastInsertId();
    }

    public function addUser($username, $email, $password)
    {
        $db = new Database();
        $key = Security::generateKey();
        $password = Security::encode($password);

        $db->execute("INSERT INTO accounts (username, email, authentification, userKey) VALUES (?, ?, "
            . UserModel::AUTHENTIFICATION_BY_PASSWORD . ", ?)", array($username, $email, $key));

        $id = $db->lastInsertId();

        $db->execute("INSERT INTO passwords (account, password) VALUES (?, ?)", array($id, $password));

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

        $db->execute("INSERT INTO passwords (account, password) VALUES (?, ?)", array($id, $password));

        Mail::sendVerificationMail($username, $email, $key);
    }


    public function forgotPassword($email)
    {
        $db = new Database();
        $req = "Select * From accounts WHERE email = ?";
        $result = $db->execute($req, array($email));

        $stmt = $result->fetch();

        $user = $stmt['username'];
        var_dump($user);
        $key = $stmt['userKey'];
        var_dump($key);

        Mail::sendForgotMail($email, $user, $key);
    }

    public function resetPassword($user, $key, $password){
        $db = new Database();

        $user = Security::escape($user);
        $oldKey = Security::escape($key);
        $password = Security::escape($password);

        $key = Security::generateKey();
        $password = Security::encode($password);

        $data = $this->getByNameOrEmail($user);

        $req = "UPDATE accounts SET userKey = ? WHERE id = ?";
        $db->execute($req, array($key, $data['id']));

        $req = "UPDATE passwords SET password = ? WHERE account = ?";
        $db->execute($req, array($password, $data['id']));

    }
}