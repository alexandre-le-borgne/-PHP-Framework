<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 16/12/2015
 * Time: 15:08
 */
class IndexModel extends Model
{

    public function availableUser($username) {

        $db = new Database();

        $user = Securite::escape($username);

        $sql = "Select * From User Where username = '$user'";

        return ($db->execute($sql) == NULL);
    }

    public function availableEmail($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            return false;
        else {
            $db = new Database();

            $email = Securite::escape($email);

            $sql = "Select * From User Where email = '$email'";

            return ($db->execute($sql));
        }
    }

    public function availablePwd($password){
        return (6 <= strlen($password) && strlen($password) <= 20);
    }


    public function addUser($username, $email, $password, $birthDate){
        $db = new Database();

        $username = Securite::escape($username);
        $email = Securite::escape($email);
        $password = Securite::escape($password);
        $birthDate = Securite::escape($birthDate);

        $password = Securite::encode($password);

        $insert = "Insert Into User ('username', 'email', 'password', 'birthDate') Values ('$username', '$email', '$password', '$birthDate')";
        $db->execute($insert);
    }
}