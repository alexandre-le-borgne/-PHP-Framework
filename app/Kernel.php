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
        $request = Request::getInstance();
        $params = explode('/', $request->get('url'));
        if (isset($params[0]) && $params[0] != '')
            $route = $router->getRoute($params[0]);
        else
            $route = $router->getDefaultRoute();
        $controller = $route->getController();
        $action = $route->getAction();
        $controller = new $controller();
        $r = new ReflectionMethod($controller, $action);
        $paramsOfFunction = $r->getParameters();
        $paramsToPass = array();
        $indexParamsUrl = 1;
        foreach ($paramsOfFunction as $param) {
            if($param->getClass() != NULL && $param->getClass()->getName() == 'Request')
                $paramsToPass[] = $request;
            else
                if(isset($params[$indexParamsUrl]))
                    $paramsToPass[] = $params[$indexParamsUrl++];
                else
                    $paramsToPass[] = null;
        }
        var_dump($controller);
        var_dump($action);
        var_dump($paramsToPass);
        if(!empty($paramsToPass))
            return call_user_func_array(array($controller, $action), $paramsToPass);
        return $controller->{$action}();
    }
}