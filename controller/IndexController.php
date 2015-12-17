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

    public function RegisterAction()
    {
        if (isset($_POST['username'], $_POST['email'], $_POST['password']))
        {
            $data = array(
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'password' => count($_POST['password'])
            );
            $this->render('forms/registerForm', $data);
        }
    }

    public function LoginAction()
    {
        $this->render('persists/home');
    }
}