<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 16/12/2015
 * Time: 14:37
 */
class IndexController extends Controller
{
    public function IndexAction()
    {
        $this->loadModel('IndexModel');
        $this->render('persists/home');
    }

    public function PreregisterAction()
    {
        $this->loadModel('IndexModel');

        if (isset($_POST['username'], $_POST['email'], $_POST['password']))
        {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            $errors = array();
            if (!($this->indexmodel->availableUser($username)))
                $errors['username'] = 'Pseudonyme déjà utilisé !';
            if (!($this->indexmodel->availableEmail($email)))
                $errors['email'] = 'Email non valide';


            $data = array
            (
                'username' => $username,
                'email' => $email,
                'errors' => $errors
            );

            $this->render('forms/registerForm', $data);
        }
    }


    public function RegisterAction()
    {
        if (isset($_POST['username'], $_POST['email'], $_POST['password'], $_POST['pwdConfirm'], $_POST['birthDate']) && ($_POST['password'] == $_POST['pwdConfirm']))
        {
            $this->loadModel('IndexModel');

            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $errors = array();

            $data = array
            (
                'username' => $username,
                'email' => $email,
                'password' => $password,
                'errors' => $errors
            );

            if(!($this->indexmodel->availableUser($_POST['username'])))
                $errors['username'] = 'Pseudonyme déjà utilisé !';

            if(!($this->indexmodel->availableEmail($_POST['email'])))
                $errors['email'] = 'Email non valide';

            if(!($this->indexmodel->availablePwd($_POST['password'])))
                $errors['password'] = 'Mot de passe invalide';

            $this->render('forms/registerForm', $data);

            $this->indexmodel->addUser($_POST['username'], $_POST['email'], $_POST['password'], $_POST['birthdate']);

        }

    }

    public
    function LoginAction()
    {
        $this->render('persists/home');
    }
}