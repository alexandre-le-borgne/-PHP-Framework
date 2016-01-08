<?php

/**
 * Le Controlleur correspondant a l'utilisateur
 * - PreregisterAction est appele a la suite du preregisterForm, lors du submit
 *   et fais quelques pretraitements
 * - RegisterAction est appele a la suite du registerForm, lors du submit
 */
class UserController extends Controller
{
    public function PreregisterAction(Request $request)
    {
        $this->loadModel('UserModel');

        $email = $request->post('email');
        $password = $request->post('password');
        $confirmPwd = $request->post('confirmPwd');

        if ($email && $password && $confirmPwd)
        {
            $isError = false;
            $errors = array();
            if (!($this->usermodel->availableEmail($email)))
            {
                $errors['email'] = 'Email déjà utilisé';
                $isError = true;
            }
            if ($password != $confirmPwd)
            {
                $errors['password'] = 'Mot de passe différent';
                $isError = true;
            }
            if (!($this->usermodel->availablePwd($password)))
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

            $request->getSession()->set('email', $email);
            $request->getSession()->set('password', $password);

            $this->render('forms/registerForm');
        }
    }

    public function RegisterAction(Request $request)
    {
        $username = $request->post('username');
        $birthDate = $request->post('birthDate');
        //On recupere les champs deja entres
        $email = $request->getSession()->get('email');
        $password = $request->getSession()->get('password');

        if ($username && $birthDate)
        {
            $this->loadModel('UserModel');

            $errors = array();
            $isError = false;
            $data = array
            (
                'username' => $username,
            );

            if (!($this->usermodel->availableUser($username)))
            {
                $errors['username'] = 'Pseudonyme déjà utilisé';
                $isError = true;
            }
            if ($isError)
            {
                $data['errors'] = $errors;
                $this->render('forms/registerForm', $data);
                return;
            }

            $this->usermodel->addUser($username, $email, $password, $birthDate);
            $this->render('persists/validationInscription');
        }
        echo "test";
    }

    public function LoginAction()
    {
        $this->render('persists/home');
    }

    public function MailResetAction(Request $request){

        $email = Security::escape($request->post('email'));

        $req = "Select username, userKey From accounts Where email = $email";

        $db = new Database();

        $data = $db->execute($req)->fetch();

        $user = $data['username'];
        $key = $data['userKey'];

        Mail::sendResetMail($email, $user, $key);

        echo "Un mail vous a été envoyé à votre adresse d'inscription, merci de suivre les instructions qu'il renferme";
    }

}