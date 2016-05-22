<?php

/**
 * Class Session
 */
final class Session
{
    const USER_IS_NOT_CONNECTED = 0;
    const USER_IS_INACTIVE = 1;
    const USER_IS_CONNECTED = 2;
    const USER_IS_ADMIN = 3;

    /**
     * @var Session
     */
    private static $instance;

    /**
     * Session constructor.
     */
    private function __construct()
    {
        session_start();
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function get($name)
    {
        if (isset($_SESSION[$name]))
            return $_SESSION[$name];
        return null;
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    /**
     * Clear the session
     */
    public function clear()
    {
        session_destroy();
        unset($_SESSION);
    }

    /**
     * @return bool
     */
    private function isConnected()
    {
        return false;
    }

    /**
     * @param string $role
     * @return bool
     */
    public function isGranted($role)
    {
        $session = Request::getInstance()->getSession();

        switch ($role)
        {
            case self::USER_IS_INACTIVE:
                return false;
                break;
            case self::USER_IS_ADMIN:
                return false;
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