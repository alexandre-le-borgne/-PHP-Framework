<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 17/12/2015
 * Time: 13:13
 */
class Session
{
    const USER_IS_NOT_CONNECTED = 0;
    const USER_IS_CONNECTED = 1;
    const USER_IS_ADMIN = 2;
    const USER_IS_INACTIVE = 3;

    private static $instance;

    private function __construct()
    {
        session_start();
    }

    public function get($name)
    {
        if (isset($_SESSION[$name]))
            return $_SESSION[$name];
        return null;
    }

    public function set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    public function clear()
    {
        session_destroy();
        unset($_SESSION);
    }

    private function isConnected()
    {
        $id = $this->get("id");
        if ($id != null)
        {
            $userModel = new UserModel();
            $user = $userModel->getById($id);
            /** @var UserEntity $user */
            if ($user)
            {
                if (!($user->getActive()))
                    return false;
                if ($user->getAuthentification() == UserModel::AUTHENTIFICATION_BY_PASSWORD)
                {
                    $password = $this->get("password");
                    if ($password != null)
                    {
                        $passwordModel = new PasswordModel();
                        $passwordEntity = $passwordModel->getByUser($user);
                        return $passwordEntity->getPassword() === $password;
                    }
                }
                else if ($user->getAuthentification() == UserModel::AUTHENTIFICATION_BY_EXTERNAL)
                {
                    return true;
                }
            }
        }
        return false;
    }

    public function isGranted($role)
    {
        $session = Request::getInstance()->getSession();
        $model = new UserModel();
        switch ($role)
        {
            case self::USER_IS_INACTIVE:
                $user = $model->getById($session->getInstance()->get('id'));
                if ($user)
                    return !$user->getActive();
                break;
            case self::USER_IS_ADMIN:
                if ($session->isConnected())
                {
                    $user = $model->getById($session->get('id'));
                    return ($user->getAccountLevel() == UserModel::ACCOUNT_LEVEL_ADMIN);
                }
                break;
            case self::USER_IS_CONNECTED:
                return $session->isConnected();
            case self::USER_IS_NOT_CONNECTED:
                return true;

        }
        return false;
    }

    /**
     * @return Session
     */
    public static function getInstance()
    {
        if (!self::$instance)
            self::$instance = new Session();
        return self::$instance;
    }
}