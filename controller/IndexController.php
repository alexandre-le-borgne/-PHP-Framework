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

    public function PreregisterAction(Request $request)
    {
        $session = $request->getSession();
        $session->set('', 'grosse');

        if (isset($_POST['username'], $_POST['email'], $_POST['password']))
        {
            $data = array(
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'password' => strlen($_POST['password'])
            );
            $this->render('forms/registerForm', $data);
        }
    }

    public function RegisterAction()
    {
        if (isset($_POST['username'], $_POST['email'], $_POST['password'], $_POST['pwdConfirm'], $_POST['birthDate']) && ($_POST['password'] == $_POST['pwdConfirm']))
        {
            $this->loadModel('IndexModel');
            $indexmodel->addUser($_POST['username'], $_POST['email'], $_POST['password'], $_POST['birthdate']);
        }
    }

    public function LoginAction()
    {
        $this->render('persists/home');
    }
}