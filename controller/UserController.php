<?php

/**
 * Le Controlleur correspondant a l'utilisateur
 * - PreregisterAction est appele a la suite du preregisterForm, lors du submit
 *   et fais quelques pretraitements
 * - RegisterAction est appele a la suite du registerForm, lors du submit
 */
class UserController extends Controller
{
    public function GoogleAction(Request $request)
    {
        if($request->getSession()->isGranted(Session::USER_IS_CONNECTED)) {
            $this->redirectToRoute('index');
            return;
        }

        ######### edit details ##########
        $clientId = '907331911654-dt5ibmao956tro1kh6ll0ggm21tqi122.apps.googleusercontent.com'; //Google CLIENT ID
        $clientSecret = 'AGC5BntkfKlqkNsKH-eEYTXK'; //Google CLIENT SECRET
        $redirectUrl = 'http://alex83690.alwaysdata.net/aaron/google';  //return url (url to script)

        $gClient = new Google_Client();
        $gClient->setApplicationName('Se connecter à Aaron');
        $gClient->setClientId($clientId);
        $gClient->setClientSecret($clientSecret);
        $gClient->setRedirectUri($redirectUrl);
        $gClient->setScopes(array(
            'https://www.googleapis.com/auth/userinfo.email',
            'https://www.googleapis.com/auth/userinfo.profile'
        ));

        $google_oauthV2 = new Google_Service_Oauth2($gClient);

        if (isset($_GET['code'])) {
            $gClient->authenticate($_GET['code']);
            $_SESSION['token'] = $gClient->getAccessToken();
        }

        if ($gClient->getAccessToken()) {
            $userData = $google_oauthV2->userinfo->get();
            if ($userData->getVerifiedEmail()) {
                $_SESSION['access_token'] = $gClient->getAccessToken();
                $this->loadModel('UserModel');
                /** @var UserEntity $userEntity */
                $userEntity = $this->usermodel->getByNameOrEmail($userData->getEmail());
                if ($userEntity) {
                    if ($userEntity->getAuthentification() == UserModel::AUTHENTIFICATION_BY_EXTERNAL) {
                        $request->getSession()->set('id', $userEntity->getId());
                    } // sinon c'est un compte du site, donc pas connectable avec google/facebook
                }
                else {
                    $id = $this->usermodel->addExternalUser($userData->getName(), $userData->getEmail());
                    $request->getSession()->set('id', $id);
                }
            }
        } else {
            $authUrl = $gClient->createAuthUrl();
            $data['authUrl'] = $authUrl;
        }

        if ($request->isInternal())
            $this->render('forms/googleForm', $data);
        else
            $this->redirectToRoute('index');
    }

    public
    function PreRegisterAction(Request $request)
    {
        $this->loadModel('UserModel');

        $email = $request->post('email');
        $password = $request->post('password');
        $confirmPwd = $request->post('confirmPwd');

        if ((!$email && $password && $confirmPwd)) {
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

    public
    function RegisterAction(Request $request)
    {
        $username = $request->post('username');
        //On recupere les champs deja entres
        $email = $request->getSession()->get('email');
        $password = $request->getSession()->get('password');

        if ($username) {
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

            $this->usermodel->addUser($username, $email, $password);
            $this->render('layouts/validateInscription');
        }
    }

    public
    function LoginAction(Request $request)
    {
        $this->loadModel('UserModel');
        $this->loadModel('PasswordModel');

        /** @var UserEntity $userEntity */
        $userEntity = $this->usermodel->getByNameOrEmail($request->post('login'));
        $password = $request->post('password');
        if ($userEntity && $userEntity->getAuthentification() == 0) {
            $passwordEntity = $this->passwordmodel->getByUser($userEntity);
            if (Security::equals($passwordEntity->getPassword(), $password)) {
                $request->getSession()->set("id", $userEntity->getId());
                $request->getSession()->set("password", $passwordEntity->getPassword());
            }
        }
        $this->redirectToRoute('index');
    }

    public
    function MailResetAction(Request $request)
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

    public
    function MailValidationAction($user, $key)
    {
        $db = new Database();
        $req = "Select email, userKey, active From accounts Where username = ?";

        if ($db->execute($req, array($user)) && $data = $db->execute($req, array($user))->fetch()) {
            $email = $data['email'];
            $realKey = $data['userKey'];
            $active = $data['active'];
        }

        if ($active == 1)
            $this->render("layouts/mailValidation", array("message" => "Votre compte est déjà actif"));
        else {
            if ($key == $realKey) {
                $this->render("layouts/mailValidation", array("message" => "Votre compte a bien été activé"));
                $req = "Update accounts Set active = 1 Where username = ?";
                $db->execute($req, array($user));
                Mail::sendWelcomingMail($email);
            } else
                $this->render("layouts/mailValidation", array("message" => "Erreur : aucun compte n'est associé à cette adresse"));
        }
    }

    public
    function FacebookAction()
    {

        $appId = '1563533667270416';
        $appSecret = 'e8d11a4b6bef48629c71839c86de8b01';

        $fb = new Facebook\Facebook([
            'app_id' => $appId,
            'app_secret' => $appSecret,
            'default_graph_version' => 'v2.5',
        ]);
        $helper = $fb->getRedirectLoginHelper();
        $loginUrl = $helper->getLoginUrl('http://alex83690.alwaysdata.net/aaron/facebook');

        $helper = $fb->getRedirectLoginHelper();
        try {
            $accessToken = $helper->getAccessToken();
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        if (isset($accessToken)) {
            // Logged in!
            $_SESSION['facebook_access_token'] = (string)$accessToken;

            // Now you can redirect to another page and use the
            // access token from $_SESSION['facebook_access_token']
        } elseif ($helper->getError()) {
            // The user denied the request
            exit;
        }

        $this->render("forms/facebookForm", array('loginUrl' => $loginUrl));
    }

    public
    function ForgotformAction(){
        $this->render("forms/forgotForm");
    }

}