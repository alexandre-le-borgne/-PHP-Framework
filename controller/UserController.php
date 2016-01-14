<?php

/**
 * Le Controlleur correspondant a l'utilisateur
 * - PreregisterAction est appele a la suite du preregisterForm, lors du submit
 *   et fais quelques pretraitements
 * - RegisterAction est appele a la suite du registerForm, lors du submit
 */

class UserController extends Controller
{
    public function PreRegisterAction(Request $request)
    {
        $this->loadModel('UserModel');

        $email = $request->post('email');
        $password = $request->post('password');
        $confirmPwd = $request->post('confirmPwd');

        if ((!$email && $password && $confirmPwd))
        {
            $this->render('index');
            return;
        }

        $errors = array();
        if ($this->usermodel->availableEmail($email) == UserModel::ALREADY_USED_EMAIL) {
            $errors['email'] = 'Email déjà utilisé';
        }
        if ($this->usermodel->availableEmail($email) == UserModel::BAD_EMAIL_REGEX) {
            $errors['email'] = 'Format d\'email incorrect';
        }
        if ($password != $confirmPwd) {
            $errors['password'] = 'Mot de passe différent';
        }
        if (!($this->usermodel->correctPwd($password))) {
            $errors['password'] = 'La taille du mdp doit être entre 6 et 20';
        }
        if (!(empty($errors))) {
            $data = array('errors' => $errors);
            $this->render('persists/home', $data);
            return;
        }

        $request->getSession()->set('email', $email);
        $request->getSession()->set('password', $password);

        $this->render('forms/registerForm');
    }

    public function RegisterAction(Request $request)
    {
        $username = $request->post('username');
        $birthDate = $request->post('birthDate');
        //On recupere les champs deja entres
        $email = $request->getSession()->get('email');
        $password = $request->getSession()->get('password');

        if ($username && $birthDate) {
            $this->loadModel('UserModel');

            $errors = array();
            $isError = false;
            $data = array
            (
                'username' => $username,
            );

            if (!($this->usermodel->availableUser($username))) {
                $errors['username'] = 'Pseudonyme déjà utilisé';
                $isError = true;
            }
            if ($isError) {
                $data['errors'] = $errors;
                $this->render('forms/registerForm', $data);
                return;
            }

            $this->usermodel->addUser($username, $email, $password, $birthDate);
            $this->render('persists/validateInscription');
        }
    }

    public function LoginAction(Request $request)
    {
        $this->loadModel('UserModel');
        $this->loadModel('PasswordModel');

        $id = $this->usermodel->getIdByNameOrEmail($request->post('login'));
        $password = $request->post('password');
        $user = $this->usermodel->getById($id);
        if ($user->getAuthentification() == 0) {
            $passwordEntity = $this->passwordmodel->getByUser($user);

            if (Security::equals($passwordEntity->getPassword(), $password)) {
                $request->getSession()->set("id", $id);
                $request->getSession()->set("password", $passwordEntity->getPassword());
            }
        }
        $this->render('persists/layout');
    }

    public function MailResetAction(Request $request)
    {

        $email = Security::escape($request->post('email'));

        $req = "Select username, userKey From accounts Where email = ?";

        $db = new Database();

        $data = $db->execute($req, array($email))->fetch();
        $user = $data['username'];
        $key = $data['userKey'];

        Mail::sendResetMail($email, $user, $key);

        echo "Un mail vous a été envoyé à votre adresse d'inscription, merci de suivre les instructions qu'il renferme";
    }

    public function MailValidationAction($user, $key)
    {
        $db = new Database();
        $req = "Select email, userKey, active From accounts Where username = ?";

        if($db->execute($req, array($user)) && $data = $db->execute($req, array($user))->fetch())
        {
            $email = $data['email'];
            $realKey = $data['userKey'];
            $active = $data['active'];
        }

        if($active == 1)
            $this->render("persists/mailValidation", array("message" => "Votre compte est déjà actif"));
        else
        {
            if($key == $realKey)
            {
                $this->render("persists/mailValidation", array("message" => "Votre compte a bien été activé"));
                $req = "Update accounts Set active = 1 Where username = ?";
                $db->execute($req, array($user));
                Mail::sendWelcomingMail($email);
            }
            else
                $this->render("persists/mailValidation", array("message" => "Erreur : aucun compte n'est associé à cette adresse"));
        }
    }

}