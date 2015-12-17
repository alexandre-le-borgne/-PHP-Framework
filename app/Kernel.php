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
        var_dump($_GET);
        $request = new Request();
        var_dump($request->get('url'));

        $params = explode('/', $request->get('url'));
        var_dump($params);
        $controller = 'indexController';
        $action = 'indexAction';
        if (isset($params[0]))
            $controller = ucfirst($params[0]);
        if (isset($params[1]))
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