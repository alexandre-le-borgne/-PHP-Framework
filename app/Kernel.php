<?php

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
        $request = Request::getInstance();
        $params = explode('/', $request->get('url'));
        $route = array_shift($params);
        return $this->generateResponse($route, $params);
    }

    public function generateResponse($route = null, $params = array(), $internal = false) {
        $router = new Router();
        $request = Request::getInstance();
        $request->setInternal($internal);
        if($route)
            $route = $router->getRoute($route);
        else
            $route = $router->getDefaultRoute();
        $controller = $route->getController();
        $action = $route->getAction();
        $controller = new $controller();
        $r = new ReflectionMethod($controller, $action);
        $paramsOfFunction = $r->getParameters();
        $paramsToPass = array();
        $indexParams = 0;
        foreach ($paramsOfFunction as $param) {
            if($param->getClass() != NULL && $param->getClass()->getName() == 'Request')
                $paramsToPass[] = $request;
            else
                if(isset($params[$indexParams]))
                    $paramsToPass[] = $params[$indexParams++];
                else
                    $paramsToPass[] = null;
        }
        if(!empty($paramsToPass))
            return call_user_func_array(array($controller, $action), $paramsToPass);
        return $controller->{$action}();
    }
}