<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 16/12/2015
 * Time: 14:37
 */
class View
{
    private $view;
    private $layout;

    public function __construct($view) {
        $this->view = $view;
    }

    public function render($data = array()) {
        $viewspath = __DIR__.DIRECTORY_SEPARATOR.'../views/';
        $path = $viewspath.$this->view.'.php';
        if(file_exists($path)) {
            if(!empty($data))
                extract($data);
            ob_start();
            require $path;
            $content_for_layout = ob_get_clean();
            if($this->layout == false)
                echo $content_for_layout;
            else
                require ($viewspath.$this->layout.'.php');
        }
        else {
            throw new TraceableException("VIEW NOT FOUND | ".$path." |");
        }
    }

    public static function getView($view) {
        $view = new View($view);
        $view->render();
    }
}