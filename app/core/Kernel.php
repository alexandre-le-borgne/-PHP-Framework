<?php

class Kernel extends App
{
    private static $instance = null;
    
    private $models;

    /**
     * @var EntityManager $entityManager
     */
    private $entityManager;

    /**
     * @var ViewManager $viewManager
     */
    private $viewManager;

    private function __construct()
    {
        $this->models = array();
        $this->entityManager = new EntityManager($this->getDatabase());
        $this->viewManager = new ViewManager();
    }

    public static function getInstance()
    {
        if (!(self::$instance))
            self::$instance = new Kernel();
        return self::$instance;
    }
    
    public function getModel($model) {
        if(!isset($this->models[$model])) {
            /**
             * @var $class PersistableEntity
             */
            $class = ucfirst($model.'Entity');
            if(!class_exists($class))
                throw new Exception('Class '.$class.' does not exist');
            $this->models[$model] = $class::getModel();
        }
        return $this->models[$model];
    }

    public function getUrlFromPath($path) {
        $path = str_replace(DIRECTORY_SEPARATOR,'/', $this->getPath($path));
        $path = str_replace($_SERVER['DOCUMENT_ROOT'],'',$path);
        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https' : 'http';
        return $protocol.'://'.$_SERVER['HTTP_HOST'].'/'.$path;
    }

    public function getPath($path) {
        return __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.$path;
    }

    public function getEntityManager() {
        return $this->entityManager;
    }

    public function getViewManager() {
        return $this->viewManager;
    }

    public function showException(TraceableException $e) {
        echo $this->getViewManager()->render('core/exception', $e->getData());
    }

    public function response()
    {
        $request = Request::getInstance();
        $params = explode('/', $request->get('url'));
        $route = array_shift($params);
        echo $this->generateResponse($route, $params)->getResponse();
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
            $response = call_user_func_array(array($controller, $action), $paramsToPass);
        else
            $response = $controller->{$action}();
        return new Response($route->getController(), $route->getAction(), $response);
    }
}