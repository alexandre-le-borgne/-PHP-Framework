<?php

/**
 * Class Request
 */
final class Request
{
    /**
     * @var Request
     */
    private static $instance;

    /**
     * @var bool
     */
    private $internal;

    /**
     * Request constructor.
     */
    private function __construct()
    {
        $this->internal = false;
    }

    /**
     * @return bool
     */
    public function isAjaxRequest()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    /**
     * @return bool
     */
    public function isInternal()
    {
        return $this->internal;
    }

    /**
     * @param bool $internal
     */
    public function setInternal($internal)
    {
        $this->internal = $internal;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function get($name)
    {
        return isset($_GET[$name]) ? $_GET[$name] : null;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function post($name)
    {
        return isset($_POST[$name]) ? $_POST[$name] : null;
    }

    /**
     * @return Request
     */
    public static function getInstance()
    {
        if (!Request::$instance)
            Request::$instance = new Request();
        return Request::$instance;
    }

    /**
     * @return Session
     */
    public function getSession()
    {
        return Session::getInstance();
    }
}