<?php

/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 16/12/2015
 * Time: 14:37
 */
class View
{
    private static $instance;
    private $data = array();

    private function __construct()
    {
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
        if(!empty($data))
            $this->data = $data;
        print_r($view);
        print_r($this->data);
        echo '<hr><br><br>';
        $viewspath = __DIR__.DIRECTORY_SEPARATOR.'../views/';
        $path = $viewspath.$view.'.php';

        if(file_exists($path)) {
            $data['view'] = new ViewPart();

            extract($data);
            ob_start();

            require $path;

            $content_for_layout = ob_get_clean();

            if($data['view']->super()) {
                $this->data['_content'] = $content_for_layout;
                $this->render($data['view']->super(), $this->data);
            }
            else {
                echo $content_for_layout;
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
        if (self::$instance == null)
            self::$instance = new View();
        self::$instance->render($view, $data);
    }

    public static function getAsset($asset) {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http')."://" . $_SERVER['SERVER_NAME'].'/aaron/web/'.$asset;
    }
}