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
}