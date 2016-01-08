<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 04/01/2016
 * Time: 13:58
 */
class UserModel extends Model
{
    const AUTHENTIFICATION_BY_PASSWORD = 0;
    const ALREADY_USED_EMAIL = 1;
    const BAD_EMAIL_REGEX = 2;
    const CORRECT_EMAIL = 3;

    public function isConnected(Request $request)
    {
        $id = $request->getSession()->get("id");
        $password = $request->getSession()->get("password");
        if ($id != null && $password != null) {
            $user = new UserEntity($id);
            if ($user->getAuthentification() == 0)
                return $user->getPassword() === $password;
        }
        return false;
    }

    public function getIdByNameOrEmail($nameOrEmail) {
        $db = new Database();
        $data = $db->execute("SELECT id From accounts WHERE username = ? OR email = ?", array($nameOrEmail, $nameOrEmail))->fetch();
        return $data['id'];
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