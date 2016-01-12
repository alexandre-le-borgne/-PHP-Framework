<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 12/01/2016
 * Time: 20:15
 */
abstract class AbstractView
{
    public abstract function render($data = array());

    public static function getViewClass($view, $data = array()) {
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
            $view = new $view();
            call_user_func_array(array($view, 'render'), $data);
        }
        else {
            throw new NotFoundException("VIEW NOT FOUND | ".$path." |");
        }
    }

    public static function getView($view, $data = array()) {
        $view = new View($view);
        $view->render($data);
    }

    public static function getAsset($asset) {
        return __DIR__.DIRECTORY_SEPARATOR.'../web/'.$asset;
    }
}