<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 16/12/2015
 * Time: 14:37
 */
class View
{
    private static $view;
    private $layout;

    private function __construct()
    {
    }

    public function extend($layout) {
        $this->layout = $layout;
    }

    public function output ($var, $default = '') {
        if(isset($var))
            echo $var;
        else
            echo $default;
    }

    public function escape($string) {
        echo Security::escape($string);
    }

    public function render($view, $data = array()) {
        $datacopy = $data;
        $data['view'] = $this;
        $viewspath = __DIR__.DIRECTORY_SEPARATOR.'../views/';
        $path = $viewspath.$view.'.php';
        if(file_exists($path)) {
            extract($data);
            ob_start();
            require $path;
            $content_for_layout = ob_get_clean();
            if(!$this->layout) {
                echo $content_for_layout;
            }
            else {
                $layout = $this->layout;
                $this->layout = null;
                $this->render($layout, array_merge($datacopy, array("_content" => $content_for_layout)));
            }
        }
        else {
            throw new NotFoundException("VIEW NOT FOUND | ".$path." |");
        }
    }

    public static function getView($view, $data = array()) {
        if (self::$view == null)
            self::$view = new View();
        self::$view->render($view, $data);
    }

    public static function getAsset($asset) {
        return __DIR__.DIRECTORY_SEPARATOR.'../web/'.$asset;
    }
}