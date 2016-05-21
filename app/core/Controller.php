<?php

abstract class Controller
{
    protected $models;

    public function loadModel($model)
    {
        return Kernel::getInstance()->getModel($model);
    }

    public function getEntityManager()
    {
        return Kernel::getInstance()->getEntityManager();
    }

    public function render($view, $data = array())
    {
        return Kernel::getInstance()->getViewManager()->render($view, $data);
    }

    public function redirect($url)
    {
        header('Location: ' . $url);
    }

    public function redirectToRoute($route, $data = array())
    {
        $path = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http') . '://' . $_SERVER['SERVER_NAME'] . '/aaron/' . $route;
        foreach ($data as $v)
            $path .= "/$v";
        $this->redirect($path);
    }

    public function createNotFoundException($description)
    {
        throw new NotFoundException($description);
    }
}