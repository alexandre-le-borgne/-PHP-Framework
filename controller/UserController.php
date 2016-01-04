<?php

/**
 * Le Controlleur correspondant a l'utilisateur
 * - PreregisterAction est appele a la suite du preregisterForm, lors du submit
 *   et fais quelques pretraitements
 * - RegisterAction est appele a la suite du registerForm
 */
class UserController extends Controller
{
    public function PreregisterAction(Request $request)
    {
        $this->loadModel('IndexModel');

        $email = $request->post('email');
        $password = $request->post('password');
        $confirmPwd = $request->post('confirmPwd');

        if ($email && $password && $confirmPwd)
        {
            $isError = false;
            $errors = array();
            if (!($this->indexmodel->availableEmail($email)))
            {
                $errors['email'] = 'Email déjà utilisé';
                $isError = true;
            }
            if ($password != $confirmPwd)
            {
                $errors['password'] = 'Mot de passe différent';
                $isError = true;
            }
            if (!($this->indexmodel->availablePwd($password)))
            {
                $errors['password'] = 'La taille du mdp doit être entre 6 et 20';
                $isError = true;
            }
            if ($isError)
            {
                $data = array('errors' => $errors);
                $this->render('persists/home', $data);
                return;
            }

            $this->render('forms/registerForm');
        }
    }

    public function RegisterAction()
    {
        //$session = $request->getSession();
        if (isset($_POST['username'], $_POST['birthDate']))
        {
            $this->loadModel('IndexModel');

            $username = $_POST['username'];


            $errors = array();

            $data = array
            (
                'username' => $username,
                'errors' => $errors
            );

            if (!($this->indexmodel->availableUser($_POST['username'])))
                $errors['username'] = 'Pseudonyme déjà utilisé';


            $this->render('forms/registerForm', $data);

            $this->indexmodel->addUser($_POST['username'], $_POST['email'], $_POST['password'], $_POST['birthdate']);
        }
    }

    public function LoginAction()
    {
        $this->render('persists/home');
    }
}