<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 04/01/2016
 * Time: 13:58
 */
class UserModel extends Model
{
    public function isConnected(Request $request)
    {
        $id = $request->getSession()->get("id");
        $password = $request->getSession()->get("password");
        if ($id != null && $password != null)
        {
            $user = new UserEntity($id);
            if ($user->getAuthentification() == 0)
                return Security::equals($user->getPassword(), $password);
        }
    }

    public function availableUser($username)
    {
        $db = new Database();
        $sql = "SELECT * FROM accounts WHERE username = '$username'";
        return $db->execute($sql);
    }

    public function availableEmail($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            return false;
        else
        {
            $db = new Database();
            $sql = "SELECT * FROM accounts WHERE email = '$email'";
            return ($db->execute($sql));
        }
    }

    public function availablePwd($password)
    {
        return (6 <= strlen($password) && strlen($password) <= 20);
    }


    public function addUser($username, $email, $password, $birthDate)
    {
        $db = new Database();
        $key = Security::generateKey();
        $password = Security::encode($password);

        $db->execute("INSERT INTO accounts ('username', 'email', 'authentification', 'birthDate', 'cle') VALUES ('?', '?', '0', '?', '?')", array($username, $email, $birthDate, $key));
        $id = $this->$db->lastInsertId();
        $db->execute("INSERT INTO passwords ('user', 'password') VALUES ('?', '?')", array($id, $password));

        Mail::sendVerificationMail($username, $email, $key);
    }
}