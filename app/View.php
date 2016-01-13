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
    private $oldlayout;
    private $data = array();

    private function __construct()
    {
    }

    public function extend($layout) {
        $this->oldlayout[] = $this->layout;
        $this->layout = $layout;
    }

    public function output ($var, $default = '') {
        if(isset($this->data[$var]))
            return $this->data[$var];
        else
            return $default;
    }

    public function escape($string) {
        return Security::escape($string);
    }

    public function render($view, $data = array()) {
        $this->data = $data;
        $viewspath = __DIR__.DIRECTORY_SEPARATOR.'../views/';
        $path = $viewspath.$view.'.php';
        if(file_exists($path)) {
            if(is_array($data))
                extract($data);
            ob_start();
            require $path;
            $content_for_layout = ob_get_clean();
            if(empty($this->layout)) {
                echo $content_for_layout;
            }
            else {
                $layout = $this->layout;
                $this->render($layout, array_merge($this->data, array('_content' => $content_for_layout)));
                $layout = array_pop($this->oldlayout);
            }
        }
        else {
            throw new NotFoundException("VIEW NOT FOUND | ".$path." |");
        }
    }

    public function isGranted($role) {
        return Session::getInstance()->isGranted($role);
    }

    public static function getView($view, $data = array()) {
        if (self::$view == null)
            self::$view = new View();
        self::$view->render($view, $data);
    }

    public static function getAsset($asset) {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http')."://" . $_SERVER['SERVER_NAME'].'/aaron/web/'.$asset;
    }
}