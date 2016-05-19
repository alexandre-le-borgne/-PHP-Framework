<?php

class Kernel extends App
{
    private static $instance = null;
    
    private $models;
    private $entityManager;
    private $database;
    
    private function __construct()
    {
        $this->models = array();
        $this->database = $this->getDatabase();
        $this->entityManager = new EntityManager($this->database);
    }

    public static function getInstance()
    {
        if (!(self::$instance))
            self::$instance = new Kernel();
        return self::$instance;
    }
    
    public function getModel($model) {
        if(!isset($this->models[$model])) {
            $class = ucfirst($model.'Model');
            if(!class_exists($class))
                throw new Exception('Class '.$class.' does not exist');
            $this->models[$model] = new $class($this->entityManager, $this->database);
        }
        return $this->models[$model];
    }

    public function response()
    {
        $request = Request::getInstance();
        $params = explode('/', $request->get('url'));
        $route = array_shift($params);
        return $this->generateResponse($route, $params);
    }

    public function generateResponse($route = null, $params = array(), $internal = false) {
        $router = new Router($this->getRoutes());
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