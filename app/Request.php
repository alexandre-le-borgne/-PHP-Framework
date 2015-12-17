<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 17/12/2015
 * Time: 13:11
 */
class Request
{
    public function isAjaxRequest()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    public function get($name)
    {
        return isset($_GET[$name]) ? $_GET[$name] : null;
    }

    public function post($name)
    {
        return isset($_POST[$name]) ? $_POST[$name] : null;
    }

    public function getSession()
    {
        return Session::getInstance();
    }
}