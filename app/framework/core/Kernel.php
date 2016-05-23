<?php

/**
 * Class Kernel
 */
final class Kernel extends App
{
    /**
     * @var Kernel|null
     */
    private static $instance;

    /**
     * @var array
     */
    private $models;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var ViewManager
     */
    private $viewManager;

    /**
     * Kernel constructor.
     */
    private function __construct()
    {
        $this->models = array();
        $this->entityManager = new EntityManager($this->getDatabase());
        $this->viewManager = new ViewManager();
    }

    /**
     * Kernel clone.
     */
    private function __clone() {}

    /**
     * @return Kernel
     */
    public static function getInstance()
    {
        if (!isset(self::$instance))
            self::$instance = new Kernel();
        return self::$instance;
    }

    /**
     * @param string $model
     * @return Model
     * @throws Exception
     */
    public function getModel($model)
    {
        if (!isset($this->models[$model]))
        {
            /**
             * @var $class PersistableEntity
             */
            $class = ucfirst($model . 'Entity');
            if (!class_exists($class))
                throw new Exception('Class ' . $class . ' does not exist');
            $this->models[$model] = $class::getModel();
        }
        return $this->models[$model];
    }

    /**
     * @param string $path
     * @return string
     */
    public function getUrlFromPath($path)
    {
        $path = str_replace(DIRECTORY_SEPARATOR, '/', $this->getPath($path));
        $path = str_replace($_SERVER['DOCUMENT_ROOT'], '', $path);
        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https' : 'http';
        return $protocol . '://' . $_SERVER['HTTP_HOST'] . '/' . $path;
    }

    /**
     * @param string $path
     * @return string
     */
    public function getPath($path)
    {
        return __DIR__ . DIRECTORY_SEPARATOR .'..'.DIRECTORY_SEPARATOR .'..'.DIRECTORY_SEPARATOR .'..'. DIRECTORY_SEPARATOR . $path;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @return ViewManager
     */
    public function getViewManager()
    {
        return $this->viewManager;
    }

    /**
     * @param TraceableException $e
     * @throws NotFoundException
     */
    public function showException(TraceableException $e)
    {
        echo $this->getViewManager()->render('core/exception', $e->getData());
    }

    /**
     * Show the response of the Kernel
     */
    public function response()
    {
        $request = Request::getInstance();
        $params = explode('/', $request->get('url'));
        $route = array_shift($params);
        echo $this->generateResponse($route, $params)->getResponse();
    }

    /**
     * @param string $route
     * @param array $params
     * @param bool $internal
     * @return Response
     */
    public function generateResponse($route = null, $params = array(), $internal = false)
    {
        $router = new Router($this->getRoutes());
        $request = Request::getInstance();
        $request->setInternal($internal);
        if ($route)
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
        foreach ($paramsOfFunction as $param)
        {
            if ($param->getClass() != NULL && $param->getClass()->getName() == 'Request')
                $paramsToPass[] = $request;
            else if (isset($params[$indexParams]))
                $paramsToPass[] = $params[$indexParams++];
            else
                $paramsToPass[] = null;
        }
        if (!empty($paramsToPass))
            $response = call_user_func_array(array($controller, $action), $paramsToPass);
        else
            $response = $controller->{$action}();
        return new Response($route->getController(), $route->getAction(), $response);
    }
}