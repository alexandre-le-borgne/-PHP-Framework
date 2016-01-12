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
    private $data = array();

    public function set($data)
    { // NOT USEFUL, KEEP IT ?
        $this->data = array_merge($this->data, $data);
    }

    public function loadModel($model)
    {
        $modelsPath = __DIR__ . DIRECTORY_SEPARATOR . '../model/';
        require_once($modelsPath . $model . '.php');
        $model = strtolower($model);
        $this->$model = new $model;
    }

    public function render($view, $data = array())
    {
        $view = new View($view);
        $view->render($data);
    }

    public function renderClass($view, $data = array()) {
        $view = strtolower($view);
        if($view[0] != '/')
            $view = '/'.$view;
        $view = '/views' . $view;
        $folderpath = substr($view, 0, strrpos($view, '/'));
        $view = ucwords($view, '/');
        $class = substr($view, strrpos($view, '/'));
        $path = __DIR__.DIRECTORY_SEPARATOR.'..'.$folderpath.$class.'.php';
        if(file_exists($path)) {
            require_once $path;
            $view = str_replace('/', '\\', $view);
            $view = new $view;
            call_user_func_array(array($view, 'render'), $data);
        }
        else {
            throw new NotFoundException("VIEW NOT FOUND | ".$path." |");
        }
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
        redirect($path);
    }

    public function createNotFoundException($description)
    {
        throw new NotFoundException($description);
    }
}