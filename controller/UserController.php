<?php

/**
 * Le Controlleur correspondant a l'utilisateur
 */
class UserController extends Controller
{
    public function ChannelAction(Request $request, $channel)
    {
        if (!$channel)
        {
            $this->redirectToRoute('index');
        }
        else
        {
            $this->loadModel('UserModel');
            /** @var UserEntity $userEntity */
            $userEntity = $this->usermodel->getByNameOrEmail($channel);
            if ($userEntity)
            {
                $this->loadModel('ArticleModel');
                $articles = $this->articlemodel->getArticlesByUserId($userEntity->getId(), 0, 500);
                $data = array('channel' => $userEntity->getUsername(), 'articles' => $articles);
                $this->render('layouts/home', $data);
            }
            else
            {
                $this->render('layouts/error', array('error' => 'La chaîne n\'existe pas'));
            }
        }
    }

    public function LoginAction(Request $request, $state)
    {
        if ($request->getSession()->isGranted(Session::USER_IS_CONNECTED))
        {
            $this->redirectToRoute('index');
        }
        else
        {
            $this->loadModel('UserModel');
            $this->loadModel('PasswordModel');

            /** @var UserEntity $userEntity */
            $login = $request->post('login');
            $password = $request->post('password');
            $error = '';
            if ($login && $password)
            {
                if ($request->getSession()->isGranted(Session::USER_IS_INACTIVE))
                {
                    $error = 'Veuillez activer votre compte avec le mail que nous vous avons envoyé !';
                }
                else
                {
                    $userEntity = $this->usermodel->getByNameOrEmail($login);
                    if ($userEntity)
                    {
                        if ($userEntity->getAuthentification() == 0)
                        {
                            $passwordEntity = $this->passwordmodel->getByUser($userEntity);
                            if (Security::equals($passwordEntity->getPassword(), $password))
                            {
                                $request->getSession()->set("id", $userEntity->getId());
                                $request->getSession()->set("password", $passwordEntity->getPassword());
                                $this->redirectToRoute('index');
                                return;
                            }
                            $error = "Votre mot de passe est incorrect !";
                        }
                        else
                        {
                            $error = 'Vous devez utilisez Facebook ou Google pour vous connecter !';
                        }
                    }
                    else
                    {
                        $error = 'Oups ! Votre compte est inexistant !';
                    }
                }
            }
            $this->render('forms/loginForm', array('errors' => $error));
        }
    }

    public function LogoutAction(Request $request)
    {
        $request->getSession()->clear();
        $this->redirectToRoute('index');
    }

    public function GoogleAction(Request $request)
    {
        if ($request->getSession()->isGranted(Session::USER_IS_CONNECTED))
        {
            $this->redirectToRoute('index');
            return;
        }

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

        if (isset($_GET['code']))
        {
            $gClient->authenticate($_GET['code']);
        }

        if ($gClient->getAccessToken())
        {
            $userData = $google_oauthV2->userinfo->get();
            if ($userData->getVerifiedEmail())
            {
                $this->loadModel('UserModel');
                /** @var UserEntity $userEntity */
                $userEntity = $this->usermodel->getByNameOrEmail($userData->getEmail());
                if ($userEntity)
                {
                    if ($userEntity->getAuthentification() == UserModel::AUTHENTIFICATION_BY_EXTERNAL)
                    {
                        $request->getSession()->set('id', $userEntity->getId());
                    }
                    else
                    {
                        $data['errors'] = 'Le compte existe déjà. Utilisez le mot de passe pour vous connecter.';
                    }
                }
                else
                {
                    $id = $this->usermodel->addExternalUser($userData->getName(), $userData->getEmail());
                    $request->getSession()->set('id', $id);
                }
            }
        }
        else
        {
            $authUrl = $gClient->createAuthUrl();
            $data['authUrl'] = $authUrl;
        }

        if ($request->isInternal())
            $this->render('forms/googleForm', $data);
        else
            $this->redirectToRoute('index');
    }

    public function FacebookAction(Request $request)
    {
        if ($request->getSession()->isGranted(Session::USER_IS_CONNECTED))
        {
            $this->redirectToRoute('index');
            return;
        }

        $appId = '1563533667270416';
        $appSecret = 'e8d11a4b6bef48629c71839c86de8b01';

        foreach ($_COOKIE as $k => $v)
        {
            if (strpos($k, "FBRLH_") !== FALSE)
            {
                $_SESSION[$k] = $v;
            }
        }

        $fb = new Facebook\Facebook([
            'app_id' => $appId,
            'app_secret' => $appSecret,
            'default_graph_version' => 'v2.5',
        ]);

        foreach ($_COOKIE as $k => $v)
        {
            if (strpos($k, "FBRLH_") !== FALSE)
            {
                $_SESSION[$k] = $v;
            }
        }

        $helper = $fb->getRedirectLoginHelper();

        if (isset($_SESSION['facebook_access_token']))
        {
            $accessToken = $_SESSION['facebook_access_token'];
            $userData = $fb->get('/me?fields=id,name,email', $accessToken)->getDecodedBody();
            $this->loadModel('UserModel');
            /** @var UserEntity $userEntity */
            $userEntity = $this->usermodel->getByNameOrEmail($userData['email']);
            if ($userEntity)
            {
                if ($userEntity->getAuthentification() == UserModel::AUTHENTIFICATION_BY_EXTERNAL)
                {
                    $request->getSession()->set('id', $userEntity->getId());
                } // sinon c'est un compte du site, donc pas connectable avec google/facebook
            }
            else
            {
                $id = $this->usermodel->addExternalUser($userData['name'], $userData['email']);
                $request->getSession()->set('id', $id);
            }
            if (!$request->isInternal())
                $this->redirectToRoute('index');
            else
            {
                $helper = $fb->getRedirectLoginHelper();
                $permissions = ['public_profile', 'email', 'user_likes']; // optional
                $loginUrl = $helper->getLoginUrl('http://alex83690.alwaysdata.net/aaron/facebook', $permissions);
                foreach ($_SESSION as $k => $v)
                {
                    if (strpos($k, "FBRLH_") !== FALSE)
                    {
                        if (!setcookie($k, $v))
                        {
                            //what??
                        }
                        else
                        {
                            $_COOKIE[$k] = $v;
                        }
                    }
                }
                $this->render('forms/facebookForm', array('loginUrl' => $loginUrl, 'errors' => 'Le compte existe déjà. Utilisez le mot de passe pour vous connecter.'));
            }
        }
        else
        {
            // We don't have the accessToken
            // But are we in the process of getting it ?
            if (isset($_REQUEST['code']))
            {
                try
                {
                    $accessToken = $helper->getAccessToken();
                } catch (Facebook\Exceptions\FacebookResponseException $e)
                {
                    $this->redirectToRoute('index');
                    return;
                } catch (Facebook\Exceptions\FacebookSDKException $e)
                {
                    $this->redirectToRoute('index');
                    return;
                }
                $error = '';
                if (isset($accessToken))
                {
                    $_SESSION['facebook_access_token'] = (string)$accessToken;
                    $userData = $fb->get('/me?fields=id,name,email', $accessToken)->getDecodedBody();
                    $this->loadModel('UserModel');
                    /** @var UserEntity $userEntity */
                    if (isset($userData['email']))
                    {
                        $userEntity = $this->usermodel->getByNameOrEmail($userData['email']);
                        if ($userEntity)
                        {
                            if ($userEntity->getAuthentification() == UserModel::AUTHENTIFICATION_BY_EXTERNAL)
                            {
                                $request->getSession()->set('id', $userEntity->getId());
                            }
                            else
                            {
                                $error = 'Le compte existe déjà. Utilisez le mot de passe pour vous connecter.';
                            }

                        }
                        else
                        {
                            $id = $this->usermodel->addExternalUser($userData['name'], $userData['email']);
                            $request->getSession()->set('id', $id);
                        }
                    }
                    if ($request->isInternal())
                    {
                        $helper = $fb->getRedirectLoginHelper();
                        $permissions = ['public_profile', 'email', 'user_likes']; // optional
                        $loginUrl = $helper->getLoginUrl('http://alex83690.alwaysdata.net/aaron/facebook', $permissions);
                        foreach ($_SESSION as $k => $v)
                        {
                            if (strpos($k, "FBRLH_") !== FALSE)
                            {
                                if (!setcookie($k, $v))
                                {
                                    //what??
                                }
                                else
                                {
                                    $_COOKIE[$k] = $v;
                                }
                            }
                        }
                        $this->render("forms/facebookForm", array('loginUrl' => $loginUrl, 'errors' => $error));
                    }
                    else
                    {
                        $this->redirectToRoute('index');
                    }
                }
            }
            else
            {
                // Well looks like we are a fresh dude, login to Facebook!
                $helper = $fb->getRedirectLoginHelper();
                $permissions = ['public_profile', 'email', 'user_likes']; // optional
                $loginUrl = $helper->getLoginUrl('http://alex83690.alwaysdata.net/aaron/facebook', $permissions);
                foreach ($_SESSION as $k => $v)
                {
                    if (strpos($k, "FBRLH_") !== FALSE)
                    {
                        if (!setcookie($k, $v))
                        {
                            //what??
                        }
                        else
                        {
                            $_COOKIE[$k] = $v;
                        }
                    }
                }
                if ($request->isInternal())
                    $this->render("forms/facebookForm", array('loginUrl' => $loginUrl));
            }

        }
    }

    public function RegisterAction(Request $request)
    {
        if ($request->getSession()->isGranted(Session::USER_IS_CONNECTED))
        {
            $this->redirectToRoute('index');
        }
        else
        {
            $email = $request->post('email');
            $password = $request->post('password');
            $confirmPwd = $request->post('confirmPwd');
            $username = $request->post('username');
            if ($username && $email && $password && $confirmPwd)
            {
                $this->loadModel('UserModel');

                $errors = '';
                if ($this->usermodel->availableEmail($email) == UserModel::ALREADY_USED_EMAIL)
                {
                    $errors = 'Email déjà utilisé';
                }
                else if ($this->usermodel->availableEmail($email) == UserModel::BAD_EMAIL_REGEX)
                {
                    $errors = 'Format d\'email incorrect';
                }
                else if ($password != $confirmPwd)
                {
                    $errors = 'Mot de passe différent';
                }
                else if (!($this->usermodel->correctPwd($password)))
                {
                    $errors = 'La taille du mdp doit être entre 6 et 20';
                }
                else if (!($this->usermodel->availableUser($username)))
                {
                    $errors = 'Pseudonyme déjà utilisé';
                }
                else
                {
                    $this->usermodel->addUser($username, $email, $password);
                    $this->render('forms/loginForm', array('validate' => 'Veuillez valider votre compte via le mail que nous vous avons envoyé'));
                    return;
                }
                $data = array('errors' => $errors);
                $this->render('forms/registerForm', $data);
            }
            else
            {
                $this->render('forms/registerForm');
            }
        }
    }

    public function MailValidationAction(Request $request, $user, $key)
    {
        $db = new Database();
        $req = "Select id, email, userKey, active From accounts Where username = ?";
        $result = $db->execute($req, array($user));
        if ($result && ($data = $result->fetch()))
        {
            $email = $data['email'];
            $realKey = $data['userKey'];
            $active = $data['active'];
            if ($active == 1)
            {
                $this->render("forms/loginForm", array("errors" => "Votre compte est déjà actif"));
            }
            else
            {
                if ($key == $realKey)
                {
                    /** @var UserEntity $userEntity */
                    $this->loadModel('UserModel');
                    $userEntity = $this->usermodel->getByNameOrEmail($user);
                    $userEntity->setActive(1);
                    $userEntity->persist();
                    Mail::sendWelcomingMail($email);
                    //$this->redirectToRoute('index', array('actif'));
                    $this->loadModel('UserModel');
                    if ($request->getSession()->isGranted(Session::USER_IS_CONNECTED))
                        $this->render('layouts/home', array("mailValidationMessage" => "Votre compte a bien été activé"));
                    else
                        $this->render("forms/loginForm", array("errors" => "Votre compte a bien été activé"));
                }
                else
                {
                    //$this->redirectToRoute('index', array('noaccount'));
                    $this->render("forms/loginForm", array("errors" => "La clé d'activation est incorrect"));
                }
            }
        }
        else
        {
            $this->render("forms/loginForm", array("errors" => "Le compte n'existe pas."));
        }
    }

    public function PwdforgotAction(Request $request)
    {
        $email = $request->post('email');

        $this->loadModel('UserModel');

        $this->usermodel->forgotPassword($email);
        $errors = "Un mail vous a été envoyé, allez vérifier vos mails";

        $data = array('errors' => $errors);
        $this->render('forms/loginForm', $data);
    }

    public function ForgotFormAction()
    {
        $this->render('/forms/forgotForm');
    }

    public function ResetFormAction($user, $key)
    {
        session_start();
        $_SESSION['user'] = $user;
        $_SESSION['key'] = $key;
        $this->render('/forms/resetForm');
    }

    public function ResetPasswordAction(Request $request)
    {
        session_start();
        $this->loadModel('UserModel');

        $this->usermodel->resetPassword($_SESSION['user'], $_SESSION['key'], $request->post('password'));
        $_SESSION = array();
        session_destroy();
        $errors = "Votre mot de passea bien été mis à jour";

        $data = array('errors' => $errors);
        $this->render('forms/loginForm', $data);

    }

    public function ProfileAction(Request $request)
    {
        $this->loadModel('CategoryModel');
        $this->loadModel('EmailModel');
        $this->loadModel('TwitterModel');
        $this->loadModel('RssModel');

        $data = array();

        $categories = $this->categorymodel->getByUserId($request->getSession()->get('id'));
        foreach ($categories as $category)
        {
            $streamCategories = $this->categorymodel->getStreamCategoriesByCategoryId($category->getId());

            $twitterStreams = array();
            $emailStreams = array();
            $rssStreams = array();

            foreach ($streamCategories as $streamCategory)
            {
                if ($streamCategory->getStreamType() == ArticleModel::TWITTER)
                {
                    $var = $this->twittermodel->getStreamById($streamCategory->getStream());
                    if ($var)
                        $twitterStreams[] = $var;
                }
                else if ($streamCategory->getStreamType() == ArticleModel::EMAIL)
                {
                    $var = $this->emailmodel->getStreamById($streamCategory->getStream());
                    if ($var)
                        $emailStreams = $var;
                }
                else if ($streamCategory->getStreamType() == ArticleModel::RSS)
                {
                    $var = $this->rssmodel->getStreamById($streamCategory->getStream());
                    if ($var)
                        $rssStreams = $var;
                }
            }

            $categoryStreams = array(
                'twitter' => $twitterStreams,
                'email' => $emailStreams,
                'rss' => $rssStreams);

            $data[] = array('title' => $category->getTitle(), 'id' => $category->getId(), 'categories' => $categoryStreams);
        }

        $this->render('layouts/profile', array('categories' => $data));
    }

    public function FollowChannelAction(Request $request, $channel)
    {
        if (!$channel)
        {
            $this->redirectToRoute('index');
            return;
        }
        $this->loadModel('UserModel');
        $id = $request->getSession()->get('id');
        $this->usermodel->follow($channel, $id);
        $this->redirectToRoute('index');
    }
}