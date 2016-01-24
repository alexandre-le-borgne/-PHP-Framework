<?php

abstract class Controller
{
    protected $models;

    public function loadModel($model)
    {
        $modelsPath = __DIR__ . DIRECTORY_SEPARATOR . '../model/';
        require_once($modelsPath . $model . '.php');
        $model = strtolower($model);
        $this->$model = new $model;
    }

    public function render($view, $data = array())
    {
        View::getView($view, $data);
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