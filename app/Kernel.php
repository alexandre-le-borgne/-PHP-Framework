<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 16/12/2015
 * Time: 14:36
 */
class Kernel
{
    private static $instance = null;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (!(self::$instance))
            self::$instance = new Kernel();
        return self::$instance;
    }

    public function response()
    {
        if (isset($_GET['controller']) && isset($_GET['action'])) {
            $controller = ucfirst($_GET['controller']) . 'Controller';
            $action = ucfirst($_GET['action']);
        } else {
            $controller = 'IndexController';
            $action = 'index';
        }
        $controller = new $controller();
        $r = new ReflectionMethod($controller, $action . 'Action');
        $params = $r->getParameters();
        foreach ($params as $param) {
            if($param->getClass() == 'Request')
                return $controller->{$action . 'Action'}(new Request());
        }
        return $controller->{$action . 'Action'}();
    }
}