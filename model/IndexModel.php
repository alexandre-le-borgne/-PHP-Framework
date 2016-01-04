<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 16/12/2015
 * Time: 15:08
 */
class IndexModel extends Model
{
    public function availableUser($username)
    {
        $db = new Database();
        $sql = "Select * From accounts Where username = '$username'";
        return ($db->execute($sql) == NULL);
    }

    public function availableEmail($email)
    {
        //verifie l'existence dans la base de donnee de l'email
        $db = new Database();
        $sql = "Select * From accounts Where email = '$email'";
        return ($db->execute($sql));
    }

    public function availablePwd($password)
    {
        return (6 <= strlen($password) && strlen($password) <= 20);
    }


    public function addUser($username, $email, $password, $birthDate)
    {
        $db = new Database();
        $key = Security::getKey($password);
        $password = Security::encode($password);


        $insert = "Insert Into accounts ('username', 'email', 'password', 'birthDate', 'cle') Values ('$username', '$email', '$password', '$birthDate', '$key')";
        $db->execute($insert);

        Mail::sendVerificationMail($username, $email, $key);
    }
}