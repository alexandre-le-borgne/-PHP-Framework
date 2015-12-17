<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 16/12/2015
 * Time: 15:08
 */
class IndexModel extends Model
{

    public function availableAccount($username) {

        $db = new Database();

        $user = Securite::escape($username);

        $sql = "Select * From User Where username = '$user'";

        return ($db->execute($sql) == NULL);
    }

    public function addUser($username, $email, $password, $birthdate){
        $db = new Database();

        $username = Securite::escape($username);
        $email = Securite::escape($email);
        $password = Securite::escape($password);
        $birthdate = Securite::escape($birthdate);

        $password = Securite::encode($password);

        $insert = "Insert Into User ('username', 'email', 'password', 'birthDate') Values ('$username', '$email', '$password', '$birthdate')";
        $db->execute($insert);
    }
}