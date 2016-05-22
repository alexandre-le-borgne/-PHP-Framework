<?php

/**
 * Class Controller
 */
abstract class Controller
{
    /**
     * @var array
     */
    protected $models;

    /**
     * @param string $model
     * @return Model
     * @throws Exception
     */
    public function loadModel($model)
    {
        return Kernel::getInstance()->getModel($model);
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return Kernel::getInstance()->getEntityManager();
    }

    /**
     * @param string $view
     * @param array $data
     * @return string
     * @throws NotFoundException
     */
    public function render($view, $data = array())
    {
        return Kernel::getInstance()->getViewManager()->render($view, $data);
    }

    /**
     * @param string $url
     */
    public function redirect($url)
    {
        header('Location: ' . $url);
    }

    /**
     * @param string $route
     * @param array $data
     */
    public function redirectToRoute($route, $data = array())
    {
        $path = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http') . '://' . $_SERVER['SERVER_NAME'] . '/aaron/' . $route;
        foreach ($data as $v)
            $path .= "/$v";
        $this->redirect($path);
    }

    /**
     * @param string $message
     * @throws NotFoundException
     */
    public function createNotFoundException($message)
    {
        throw new NotFoundException($message);
    }
}