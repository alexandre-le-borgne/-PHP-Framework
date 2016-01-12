<?php

/**
 * Created by PhpStorm.
 * User: l14011190
 * Date: 14/12/15
 * Time: 14:17
 */

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
        $view = new View();
        $view->render($view, $data);
    }

    public static function getAsset($asset) {
        return __DIR__.DIRECTORY_SEPARATOR.'../web/'.$asset;
    }

    public function redirect($url)
    {
        header('Location: ' . $url);
    }

    public function redirectToRoute($route, $data = array())
    {
        $path = "$route";
        foreach($data as $v)
            $path .= "/$v";
        $this->redirect($path);
    }

    public function createNotFoundException($description)
    {
        throw new NotFoundException($description);
    }
}