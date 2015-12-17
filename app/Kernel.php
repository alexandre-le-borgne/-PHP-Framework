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
        $request = new Request();
        $params = explode('/', $request->get('url'));
        $controller = 'index';
        $action = 'index';
        if (isset($params[0]) && $params[0] != '')
            $controller = ucfirst($params[0]);
        if (isset($params[1]) && $params[1] != '')
            $action = ($params[1]) . 'Action';
        $controller = ucfirst($controller) . 'Controller';
        $action = ucfirst($action) . 'Action';
        $controller = new $controller();
        $r = new ReflectionMethod($controller, $action);
        $params = $r->getParameters();
        foreach ($params as $param) {
            if($param->getClass() == 'Request')
                return $controller->{$action}($request);
        }
        return $controller->{$action}();
    }
}