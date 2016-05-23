<?php

/**
 * Class Session
 */
final class Session
{
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
     * @return Session
     */
    public static function getInstance()
    {
        if (!self::$instance)
            self::$instance = new Session();
        return self::$instance;
    }
}