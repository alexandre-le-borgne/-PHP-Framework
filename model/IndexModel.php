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
        $sql = "Select * From User Where username = '$username'";
        return ($db->execute($sql) == NULL);
    }

    public function valideEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public function availableEmail($email)
    {
        if ($this->valideEmail($email))
        {
            $db = new Database();
            $sql = "Select * From User Where email = '$email'";
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
        $key = Security::getKey($password);
        $password = Security::encode($password);


        $insert = "Insert Into User ('username', 'email', 'password', 'birthDate', 'cle') Values ('$username', '$email', '$password', '$birthDate', '$key')";
        $db->execute($insert);

        Mail::sendVerificationMail($username, $email, $key);
        $this->render(views/persists/validationInscription);
    }
}