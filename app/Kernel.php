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
        $router = new Router();
        $request = new Request();
        $params = explode('/', $request->get('url'));
        if (isset($params[0]) && $params[0] != '')
            $route = $router->getRoute($params[0]);
        else
            $route = $router->getDefaultRoute();
        $controller = $route->getController();
        $action = $route->getAction();
        $controller = new $controller();
        $r = new ReflectionMethod($controller, $action);
        $params = $r->getParameters();
        foreach ($params as $param) {

            if($param->getClass() == 'Request') {
                echo $controller."required Request";
                return $controller->{$action}($request);
            }
        }
        return $controller->{$action}();
    }
}